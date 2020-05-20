<template>
  <div class="bg-gray-100">
    <AppHeader />

    <div class="bg-blue-900 pb-32">
      <div class="container mx-auto px-4">
        <div class="pt-10">
          <h1 class="text-3xl font-bold text-white">
            FAQ
          </h1>
        </div>
      </div>
    </div>

    <div class="-mt-32">
      <div class="container mx-auto px-4 my-12">
        <div
          class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
        >
          <h2 class="text-3xl leading-tight font-extrabold text-gray-900">
            FAQ relative à la mobilisation citoyenne
          </h2>
          <div class="mt-6 border-t-2 border-gray-100 pt-10">
            <p class="text-lg lg:text-xl font-medium text-gray-900">
              Pour faire face, collectivement, à la crise sanitaire actuelle, le
              Président de la République a appelé les Français à «&nbsp;inventer
              de nouvelles solidarités&nbsp;». Le caractère exceptionnel de
              cette crise appelle, en effet, un engagement exceptionnel de
              chacun d’entre nous. En particulier pour que les activités
              associatives essentielles à la vie de la Nation puissent se
              poursuivre. Depuis toujours au cœur des liens indéfectibles entre
              les citoyens, entre les générations, entre les territoires, les
              associations doivent donc aujourd’hui prioriser leurs actions pour
              que les impacts directs et indirects de la lutte contre le
              Coronavirus ne laissent personne dans le besoin. Cela, tout en
              respectant, scrupuleusement, les règles de sécurité d’accueil des
              bénéficiaires et d’intervention des bénévoles.
            </p>
          </div>
        </div>

        <div v-if="!$store.getters.loading">
          <div
            v-for="faq in faqs"
            :key="faq.id"
            class="mt-8 bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
          >
            <div class="flex flex-wrap -m-4">
              <div class="p-4 w-full lg:w-1/2">
                <div class="text-lg lg:text-xl font-medium text-gray-900">
                  {{ faq.title }}
                </div>
              </div>
              <div class="p-4 w-full lg:w-1/2 text-gray-500">
                <div class="faq-description" v-html="faq.description" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script>
import { fetchFaqs } from '@/api/app'

export default {
  name: 'FrontFaq',
  data() {
    return {
      loading: false,
      faqs: {},
    }
  },
  created() {
    this.$store.commit('setLoading', true)
    fetchFaqs({ pagination: 0 })
      .then((response) => {
        this.faqs = response.data.data
        this.$store.commit('setLoading', false)
        this.loading = false
      })
      .catch(() => {
        this.loading = false
      })
  },
}
</script>
