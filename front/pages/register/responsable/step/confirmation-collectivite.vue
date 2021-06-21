<template>
  <div class="relative bg-blue-800">
    <img
      class="z-1 object-cover absolute h-screen lg:h-full"
      alt="Je Veux Aider"
      :srcSet="bgHeroMultipleSizes.srcSet"
      :src="bgHeroMultipleSizes.src"
      width="100%"
      height="100%"
    />
    <div class="relative p-6 lg:p-12">
      <div class="mb-6 lg:mb-12 text-center text-white">
        <h1 class="text-4xl lg:text-5xl font-medium leading-12 mb-4">
          Féliciations <span class="font-bold">{{ firstName }}</span> !<br />
          Votre collectivité est en cours de validation
        </h1>
        <div class="text-lg">
          Vous recevrez un email de confirmation lorsque votre compte aura été
          validé par l'équipe de JeVeuxAider.gouv.fr
        </div>
      </div>
      <div
        class="max-w-5xl mx-auto relative grid grid-cols-1 lg:grid-cols-2 gap-8"
      >
        <div class="bg-white rounded-lg p-8">
          <div class="text-black text-3xl font-extrabold leading-9 text-center">
            Suivez le guide
          </div>
          <div class="text-center text-gray-500 my-6">
            Découvrez la plateforme et sa gestion simplifiée des candidatures de
            bénévoles
          </div>
          <div class="sm:col-span-">
            <span class="block w-full rounded-md shadow-sm">
              <a
                target="_blank"
                href="https://app.livestorm.co/jeveuxaider/session-decouverte-collectivites-territoriales"
                class="
                  shadow-lg
                  block
                  w-full
                  text-center
                  rounded-lg
                  z-10
                  border border-transparent
                  bg-blue-800
                  px-4
                  sm:px-6
                  py-4
                  text-lg
                  sm:text-xl
                  leading-6
                  font-bold
                  text-white
                  hover:bg-blue-900
                  focus:outline-none
                  focus:shadow-outline-indigo
                  transition
                  ease-in-out
                  duration-150
                "
              >
                <span class="hidden lg:inline"
                  >Participer à la session d'accueil
                </span>
                <span class="inline lg:hidden">Session d'accueil</span>
              </a>
            </span>
          </div>
        </div>
        <div class="bg-white rounded-lg p-8">
          <div class="text-black text-3xl font-extrabold leading-9 text-center">
            Publiez votre 1ère mission
          </div>
          <div class="text-center text-gray-500 my-6">
            Proposez dès maintenant vos missions sur la plateforme et recrutez
            vos premiers bénévoles
          </div>
          <div class="sm:col-span-">
            <span class="block w-full rounded-md shadow-sm">
              <el-button
                type="primary"
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
                >C'est parti !</el-button
              >
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const bgHeroMultipleSizes = require('@/assets/images/bg-jva.jpg?resize&sizes[]=320&sizes[]=640&sizes[]=960&sizes[]=1200&sizes[]=1800&sizes[]=2400&sizes[]=3900')

export default {
  asyncData({ $api, store, error }) {
    if (!store.getters.structure || !store.getters.structure.collectivity) {
      return error({ statusCode: 403 })
    }
    return {
      structureId: store.getters.structure.id,
    }
  },
  data() {
    return {
      bgHeroMultipleSizes,
      loading: false,
    }
  },
  computed: {
    firstName() {
      return this.$store.getters.profile
        ? this.$store.getters.profile.first_name
        : null
    },
  },
  created() {},
  methods: {
    onSubmit() {
      this.$router.push(`/dashboard/structure/${this.structureId}/missions/add`)
    },
  },
}
</script>

<style lang="sass" scoped></style>
