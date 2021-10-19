<template>
  <div class="">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          Réseau {{ reseau.name }}
        </div>
        <div class="mb-8 font-bold text-[1.75rem] text-[#242526]">
          Éditer un modèle de mission
        </div>
      </div>
      <div>
        <nuxt-link
          :to="`/dashboard/reseaux/${$route.params.id}/modeles-de-mission`"
        >
          <el-button>Retour</el-button>
        </nuxt-link>
      </div>
    </div>
    <div class="px-12 mb-20">
      <FormMissionTemplate
        class="max-w-2xl"
        :template="template"
        :on-submit-end="
          () => {
            $router.push(
              `/dashboard/reseaux/${$route.params.id}/modeles-de-mission`
            )
            $message({
              message: 'Votre modèle de mission a été modifié.',
              type: 'success',
            })
          }
        "
      />
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
    const template = await $api.getMissionTemplate(params.mid)

    return {
      reseau,
      template,
    }
  },
}
</script>
