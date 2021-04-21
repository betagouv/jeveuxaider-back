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
    <div class="rounded-lg bg-white max-w-lg mx-auto overflow-hidden">
      <div
        class="px-8 py-6 bg-white text-black text-3xl font-extrabold leading-9 text-center"
      >
        Enrichissez vos informations
      </div>
      <div class="p-8 bg-gray-50 border-t border-gray-200">
        <div class="mb-8 text-md text-gray-500">
          Recherche Algolia sur les compétences (TODO).
        </div>

        <div class="sm:col-span-">
          <span class="block w-full rounded-md shadow-sm">
            <el-button
              type="primary"
              :loading="loading"
              class="shadow-lg block w-full text-center rounded-lg z-10 border border-transparent bg-green-400 px-4 sm:px-6 py-4 text-lg sm:text-xl leading-6 font-bold text-white hover:bg-green-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150"
              @click="onSubmit"
              >Trouver une mission</el-button
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
  async asyncData({ $api, store }) {
    const tags = await $api.fetchTags({ 'filter[type]': 'domaine' })
    return {
      domaines: tags.data.data,
      form: { ...store.getters.user.profile },
    }
  },
  data() {
    return {
      loading: false,
      form: { ...this.$store.getters.user.profile },
      rules: {
        domaines: {
          required: true,
          message: "Sélectionnez au moins un domaine d'action",
          trigger: 'blur',
        },
      },
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
    onSubmit() {
      this.$router.push('/missions-benevolat')
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep .el-step__description
  @apply hidden
    @screen sm
      @apply block
</style>
