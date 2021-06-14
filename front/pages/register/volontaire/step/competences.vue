<template>
  <div class="relative">
    <portal to="sidebar"
      ><div class="text-xl lg:text-2xl font-bold mb-6 lg:mb-12">
        Ça ne devrait pas prendre plus de 3 minutes 😉
      </div>
      <Steps :steps="steps"
    /></portal>
    <div class="mb-6 lg:mb-12 text-center text-white">
      <h1 class="text-4xl lg:text-5xl font-medium leading-12 mb-4">
        Dites-nous en plus<br />
        sur vous
        <span class="font-bold">{{ $store.getters.profile.first_name }}</span>
      </h1>
    </div>
    <div class="max-w-lg mx-auto">
      <div
        class="
          px-8
          py-6
          bg-white
          rounded-t-lg
          text-black text-3xl
          font-extrabold
          leading-9
          text-center
        "
      >
        Enrichissez vos informations
      </div>
      <div class="p-8 bg-gray-50 border-t border-gray-200 rounded-b-lg">
        <div class="mb-8 text-md text-gray-500">
          Enrichissez votre profil avec les compétences que vous souhaitez
          mettre au service des organisations publiques ou associatives.
        </div>

        <div class="mb-8">
          <div class="form-register-steps el-form--label-top">
            <label for="compentences" class="el-form-item__label"
              >Renseignez vos compétences</label
            >
            <ProfileAlgoliaSkillsInput
              :items="form.skills"
              @add-item="handleSelectItems"
            />
          </div>
        </div>

        <div v-if="form.skills.length" class="mb-10">
          <div class="flex flex-wrap -m-1">
            <div
              v-for="item in form.skills"
              :key="item.id"
              class="
                flex
                items-center
                space-x-4
                px-4
                py-3
                rounded-lg
                border border-blue-800
                bg-white
                m-1
              "
            >
              <div class="flex-none text-sm text-blue-800 font-bold">
                {{ item.name.fr }}
              </div>
              <div
                class="
                  flex-none
                  cursor-pointer
                  w-4
                  h-4
                  text-blue-800
                  hover:text-blue-900
                "
                @click="handleRemoveSkill(item.id)"
                v-html="
                  require('@/assets/images/icones/heroicon/close.svg?include')
                "
              />
            </div>
          </div>
        </div>

        <div class="sm:col-span-">
          <span class="block w-full rounded-md shadow-sm">
            <el-button
              type="primary"
              :loading="loading"
              class="
                shadow-lg
                block
                w-full
                text-center
                rounded-lg
                z-10
                border border-transparent
                bg-green-400
                px-4
                sm:px-6
                py-4
                text-lg
                sm:text-xl
                leading-6
                font-bold
                text-white
                hover:bg-green-500
                focus:outline-none
                focus:border-indigo-700
                focus:shadow-outline-indigo
                transition
                ease-in-out
                duration-150
              "
              @click="onSubmit"
              >Terminer</el-button
            >
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'register-steps',
  asyncData({ $api, store }) {
    return {
      form: { ...store.getters.profile },
    }
  },
  data() {
    return {
      loading: false,
      steps: [
        {
          name: 'Rejoignez le mouvement',
          status: 'complete',
          href: '/register/volontaire/step/profile',
        },
        {
          name: 'Votre profil',
          status: 'complete',
          href: '/register/volontaire/step/profile',
        },
        {
          name: 'Vos préférences',
          status: 'complete',
          href: '/register/volontaire/step/preferences',
        },
        {
          name: 'Vos compétences',
          status: 'current',
        },
      ],
    }
  },
  methods: {
    handleSelectItems(item) {
      this.$set(this.form, 'skills', [...this.form.skills, item])
    },
    handleRemoveSkill(id) {
      this.form.skills = this.form.skills.filter((item) => item.id !== id)
    },
    async onSubmit() {
      this.loading = true
      await this.$store.dispatch('user/updateProfile', {
        id: this.$store.getters.profile.id,
        ...this.form,
      })
      this.loading = false
      window.plausible &&
        window.plausible('Inscription bénévole - Étape 4 - Compétences')
      this.$router.push('/user/infos')
    },
  },
}
</script>
