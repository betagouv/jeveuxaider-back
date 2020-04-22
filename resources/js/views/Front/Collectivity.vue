<template>
  <div>
    <AppHeader />

    <div class="bg-blue-900 pb-5">
      <div class="container mx-auto px-4">
        <div class="pt-5">
          <h1 class="text-3xl font-bold text-white">{{ collectivity.title }}</h1>
        </div>
      </div>
    </div>

    <div class="lg:flex">
      <div class="border-t lg:border-t-0 lg:w-1/2 py-8 md:py-16 lg:p-24">
        <div class="uppercase pb-2 mt-3 text-xl text-gray-500 sm:mt-5">
          Crise sanitaire du
          <b>COVID-19</b>
        </div>
        <div class="sm:text-center lg:text-left">
          <h2
            class="text-4xl tracking-tight leading-10 font-extrabold text-gray-900 sm:text-5xl sm:leading-none md:text-6xl"
          >
            Engagez-vous
          </h2>
          <p
            class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-2xl md:max-w-3xl"
          >
            Face à l’épidémie de Covid-19
            le Gouvernement appelle à la mobilisation
            générale des solidarités.
          </p>

          <div class="mt-6 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-2 sm:gap-8">
            <div>
              <div
                class="mt-2 pb-1 text-sm leading-6 font-medium text-gray-500"
              >Je suis une structure associative</div>
              <router-link
                to="/register/responsable"
                class="btn-primary w-full"
                style="max-width:320px;"
              >
                <span class="text-lg font-bold">Proposer une mission</span>
              </router-link>
            </div>
            <div>
              <div class="mt-2 pb-1 text-sm leading-6 font-medium text-gray-500">Je suis volontaire</div>

              <router-link
                to="/register/volontaire"
                class="btn-secondary w-full"
                style="max-width:320px;"
              >
                <span class="text-lg font-bold">Je veux aider</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>
      <div class="hidden lg:block lg:w-1/2">
        <img
          class="object-cover object-center w-full h-full max-h-250 lg:max-h-full"
          src="/images/hotel-de-ville-collectivite.jpg"
        />
      </div>
    </div>

    <div class="bg-blue-900">
      <div class="container mx-auto py-12 pt-16 px-4 sm:py-16 sm:px-6 lg:px-8 lg:py-0 lg:pt-10">
        <div class="max-w-6xl mx-auto text-center">
          <h2 class="text-3xl leading-9 font-extrabold text-white sm:text-4xl sm:leading-10">
            Trouvez une mission de bénévolat
          </h2>
          <p class="text-xl leading-8 text-indigo-200 mt-2">
            <router-link to="/regles-de-securite" target="_blank">Consulter les règles de sécurité ›</router-link>
          </p>
        </div>  

        <missions-search :facet-filters="[`department: ${collectivity.department}`]"></missions-search>

      </div>
    </div>

    <div class="bg-gray-50 border-b border-gray-200">
      <div class="container mx-auto py-12 px-4 sm:px-6 lg:py-24 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-20 lg:items-center">
          <div>
            <h2 class="text-3xl pb-4 leading-10 font-extrabold text-gray-900 sm:text-4xl">
              A chacun sa
              <span class="text-blue-500">mission</span>
            </h2>
            <p
              class="mt-2 max-w-3xl text-xl pb-8 leading-7 text-gray-500"
            >Contribuez à la lutte contre l’épidémie en menant des 5 types d'actions de solidarité dans votre commune :</p>

            <ul>
              <li class="flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="h-6 w-6 text-green-500"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </div>
                <p
                  class="ml-3 text-lg leading-6 text-gray-700"
                >Je fais les courses de produits essentiels pour mes voisins les plus fragiles.</p>
              </li>
              <li class="mt-4 flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="h-6 w-6 text-green-500"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </div>
                <p
                  class="ml-3 text-lg leading-6 text-gray-700"
                >Je distribue des produits de première nécessité (aliments, hygiène, …) et des repas aux plus démunis</p>
              </li>
              <li class="mt-4 flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="h-6 w-6 text-green-500"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </div>
                <p
                  class="ml-3 text-lg leading-6 text-gray-700"
                >Je garde des enfants de soignants ou d’une structure de l’Aide Sociale à l’Enfance</p>
              </li>
              <li class="mt-4 flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="h-6 w-6 text-green-500"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </div>
                <p
                  class="ml-3 text-lg leading-6 text-gray-700"
                >Je maintiens un lien (téléphone, visio, mail, …) avec des personnes fragiles isolées (âgées, malades, situation de handicap, de pauvreté, de précarité, etc.)</p>
              </li>
              <li class="mt-4 flex items-start">
                <div class="flex-shrink-0">
                  <svg
                    class="h-6 w-6 text-green-500"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M5 13l4 4L19 7"
                    />
                  </svg>
                </div>
                <p
                  class="ml-3 text-lg leading-6 text-gray-700"
                >J’aide à distance les élèves à faire leurs devoirs.</p>
              </li>
            </ul>
          </div>
          <div class="mt-8 grid grid-cols-2 gap-1 md:grid-cols-3 lg:mt-0 lg:grid-cols-2">
            <div class="col-span-1 flex justify-center py-8 px-8 bg-white rounded-lg shadow-sm">
              <img class="max-h-14" src="/images/police-nationale.jpg" />
            </div>

            <div class="col-span-1 flex justify-center py-8 px-8 bg-white rounded-lg shadow-sm">
              <img class="max-h-14" src="/images/restaurants-du-coeur.jpg" />
            </div>
            <div class="col-span-1 flex justify-center py-8 px-8 bg-white rounded-lg shadow-sm">
              <img class="max-h-14" src="/images/petits-freres-des-pauvres.jpg" />
            </div>
            <div class="col-span-1 flex justify-center py-8 px-8 bg-white rounded-lg shadow-sm">
              <img class="max-h-14" src="/images/meudon-bien-etre.jpg" />
            </div>
            <div class="col-span-1 flex justify-center py-8 px-8 bg-white rounded-lg shadow-sm">
              <img class="max-h-14" src="/images/secours-populaire-francais.jpg" />
            </div>
            <div class="col-span-1 flex justify-center py-8 px-8 bg-white rounded-lg shadow-sm">
              <img class="max-h-14" src="/images/la-source.jpg" />
            </div>
            <div class="pt-6 col-span-2 justify-center inline-flex">
              <router-link
                to="/register/responsable"
                class="inline-flex items-center justify-center shadow-md px-5 py-2 text-sm bg-white leading-6 font-medium rounded-full text-gray-500 hover:bg-gray-50 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                style="max-width:320px;"
              >Ajoutez votre structure</router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script>
import { getCollectivity } from "@/api/app";
import MissionsSearch from "@/components/MissionsSearch"

export default {
  name: "FrontCollectivity",
  components: {
    MissionsSearch,
  },
  props: {
    slug: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      collectivity: {},
    };
  },
  created() {
    this.$store.commit("setLoading", true);
    getCollectivity(this.slug)
      .then(response => {
        this.collectivity = { ...response.data };
        this.$store.commit("setLoading", false);
      })
      .catch(() => {
        this.loading = false;
      });
  },
  methods: {}
};
</script>
