<template>
  <div class="">
    <div class="px-12 header flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          Réseau {{ reseau.name }}
        </div>
        <div class="mb-1 font-bold text-[1.75rem] text-[#242526]">
          Modèles de mission
        </div>
        <div class="mb-8 text-gray-500">
          Retrouvez ci-dessous tous les modèles de missions proposées aux
          antennes de votre réseau
        </div>
      </div>
      <div>
        <nuxt-link
          :to="`/dashboard/reseaux/${$route.params.id}/modeles-de-mission/add`"
        >
          <el-button type="primary">Créer un modèle de mission</el-button>
        </nuxt-link>
      </div>
    </div>

    <NavTabReseau
      v-if="$store.getters.contextRole == 'admin'"
      :reseau="reseau"
    />

    <div class="px-12">
      <div class="grid grid-cols-4 gap-6 max-w-7xl">
        <CardMissionTemplate
          title="Créez un modèle de mission"
          description="Ce modèle pourra ensuite être proposé par les antennes de votre réseau afin de faciliter la publication de ses missions."
          image-url="/images/card-add.png"
          state-text="Validation par un référent"
          state-style="warning"
          @click.native="
            $router.push(
              `/dashboard/reseaux/${$route.params.id}/modeles-de-mission/add`
            )
          "
        />

        <CardMissionTemplate
          v-for="missionTemplate in missionTemplates"
          :key="missionTemplate.id"
          :title="missionTemplate.title"
          :description="missionTemplate.subtitle"
          :image-url="
            missionTemplate.photo
              ? missionTemplate.photo.large
              : '/images/card-thumbnail-default@2x.jpg'
          "
          :state-text="
            missionTemplate.published ? 'En attente de validation' : null
          "
          :state-style="missionTemplate.published ? 'warning' : null"
          action-title="Éditer"
          @click.native="
            $router.push(
              `/dashboard/reseaux/${$route.params.id}/modeles-de-mission/${missionTemplate.id}/edit`
            )
          "
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    if (!['admin', 'tete_de_reseau'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }

    if (store.getters.contextRole == 'tete_de_reseau') {
      if (store.getters.profile.tete_de_reseau_id != params.id) {
        return error({ statusCode: 403 })
      }
    }
    const reseau = await $api.getReseau(params.id)

    const { data: missionTemplates } = await $api.fetchMissionTemplates({
      'filter[of_reseau]': params.id,
    })

    return {
      reseau,
      missionTemplates: missionTemplates.data,
    }
  },
}
</script>
