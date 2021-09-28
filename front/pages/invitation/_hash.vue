<template>
  <div class="relative bg-[#070191] overflow-hidden">
    <img
      class="z-1 object-cover absolute h-screen lg:h-auto"
      alt="JeVeuxAider"
      :srcSet="bgHeroMultipleSizes.srcSet"
      :src="bgHeroMultipleSizes.src"
    />

    <div class="pb-12 mt-12 px-4 relative w-full lg:inset-y-0 text-center z-10">
      <div class="">
        <h2
          class="mt-6 mb-4 md:mb-0 text-center text-3xl font-bold text-white leading-8 px-4"
        >
          Invitation à rejoindre la Réserve Civique
        </h2>
        <p class="text-center text-base text-[#c3ddfd]">
          Engagez-vous pour faire vivre les valeurs de la République
        </p>
      </div>
    </div>

    <div
      class="relative mt-2 pb-16 sm:mx-auto sm:w-full sm:max-w-md text-left z-10"
    >
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <div class="mb-8 text-center">
          <template v-if="invitation.role == 'benevole'">
            Vous avez été invité(e) à rejoindre la plateforme d'engagement
            <strong>JeVeuxAider.gouv.fr</strong> de la Réserve Civique.
          </template>
          <template v-if="invitation.role == 'responsable_organisation'">
            Vous avez été invité(e) à rejoindre l'organisation
            <strong>{{ invitation.invitable.name }}</strong> en tant que
            responsable.
          </template>
          <template v-if="invitation.role == 'responsable_antenne'">
            Vous avez été invité(e) à créer l'antenne XXX du réseau
            <strong>{{ invitation.invitable.name }}</strong
            >.
          </template>
          <template v-if="invitation.role == 'responsable_reseau'">
            Vous avez été invité(e) à superviser le réseau
            <strong>{{ invitation.invitable.name }}</strong
            >.
          </template>
          <template v-if="invitation.role == 'responsable_territoire'">
            Vous avez été invité(e) à rejoindre le territoire
            <strong>{{ invitation.invitable.name }}</strong> en tant que
            responsable.
          </template>
          <template v-if="invitation.role == 'referent_departemental'">
            Vous avez été invité(e) à rejoindre le département
            <strong>{{
              invitation.properties.referent_departemental
                | labelFromValue('departments')
            }}</strong>
            en tant que référent.
          </template>
          <template v-if="invitation.role == 'referent_regional'">
            Vous avez été invité(e) à rejoindre la région
            <strong>{{ invitation.properties.referent_regional }}</strong>
            en tant que référent.
          </template>
        </div>

        <div v-if="!$store.getters.isLogged" class="relative">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-[#d2d6dc]"></div>
          </div>
          <div class="relative flex justify-center text-xl">
            <span class="px-2 bg-white font-bold text-gray-900">
              <template v-if="invitation.is_registered"
                >Je me connecte</template
              >
              <template v-else>Je créé mon compte</template>
            </span>
          </div>
        </div>

        <template v-if="invitation.is_registered">
          <InvitationLoginForm
            v-if="!$store.getters.isLogged || processing"
            :invitation="invitation"
            @on-processing="handleProcessing"
          />
          <InvitationAcceptForm v-else :invitation="invitation" />
        </template>
        <template v-else>
          <InvitationRegisterForm :invitation="invitation" />
        </template>
      </div>
    </div>
  </div>
</template>

<script>
const bgHeroMultipleSizes = require('@/assets/images/bg-jva.jpg?resize&sizes[]=320&sizes[]=640&sizes[]=960&sizes[]=1200&sizes[]=1800&sizes[]=2400&sizes[]=3900')

export default {
  async asyncData({ $api, params, error }) {
    const invitation = await $api.getInvitation(params.hash)
    if (!invitation) {
      return error({ statusCode: 404 })
    }
    return {
      invitation,
    }
  },
  data() {
    return {
      bgHeroMultipleSizes,
      loading: false,
      processing: false,
      invitation: null,
    }
  },
  methods: {
    handleProcessing(value) {
      this.processing = value
    },
  },
}
</script>
