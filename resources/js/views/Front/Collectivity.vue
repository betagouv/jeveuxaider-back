<template>
  <div>
    <div class="relative">
      <img
        v-if="collectivity.image && collectivity.image.large"
        :src="collectivity.image.large"
        :alt="collectivity.title"
        class="absolute object-cover object-center w-full h-full"
      />

      <div :class="['bg-blue-900', 'absolute', 'inset-0', {'opacity-75': collectivity.image}]"></div>

      <div class="relative pt-1 pb-12 lg:py-12">
        <AppHeader background="bg-transparent" border="" :showMenu="false">
          <template v-slot:menu>
            <div class="hidden sm:block ml-2 mr-auto w-auto order-2">
              <div class="text-xl md:text-2xl font-medium text-white leading-none">• {{ collectivity.title }}</div>
            </div>
          </template>
        </AppHeader>
        <div class="container mx-auto px-4">
          <div class="py-8 text-center sm:text-left">
            <h2 class="text-4xl leading-none font-bold text-white sm:text-5xl md:text-6xl">
              Engagez-vous dans
              <br class="hidden sm:block"/>
              <span class="text-white">votre département</span>
            </h2>

            <p class="mt-5 text-base text-gray-100 max-w-xl sm:text-lg md:text-xl">
              Le département <b>{{ collectivity.title }}</b> vous propose un espace d'engagement ouvert à tous, que vous soyez bénévole dans l’âme ou bien un acteur local du monde associatif.
            </p>

            <div class="mt-5 sm:mt-8 sm:flex sm:justify-start">
              <div class=" text-center">
                <div class="pb-1 text-sm font-medium text-gray-100">
                  Je suis une structure associative
                </div>
                <router-link
                  to="/register/responsable"
                  class="btn-primary"
                >
                  <span class="text-lg font-bold">Proposer une mission</span>
                </router-link>
              </div>
              <div class="mt-3 sm:mt-0 sm:ml-6 text-center">
                <div class="pb-1 text-sm font-medium text-gray-100">
                  Je suis volontaire
                </div>
                <router-link
                  to="/register/volontaire"
                  class="btn-white"
                >
                  <span class="text-lg font-bold">Je veux aider</span>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="container mx-auto px-4">
      <div class="py-16">
        <div class=" text-center">
            <h2 class="text-3xl leading-10 font-bold tracking-tight text-gray-900 sm:text-5xl sm:leading-14 leading-none">
              L'engagement en <span class="text-blue-900">{{ collectivity.title }}</span>
            </h2>
            <p class="mt-4 mx-auto max-w-3xl text-xl pb-8 text-gray-500 text-center">
              La Réserve Civique facilite la mise en relation entre les <b>volontaires</b> et leurs prochaines <b>structures publiques et associatives</b>.
            </p>

            <dl v-if="!loading" class="mt-2 text-center sm:max-w-3xl sm:mx-auto sm:grid sm:grid-cols-3 sm:gap-8">
              <div class="flex flex-col">
                <dd class="text-5xl leading-none font-bold text-gray-800">
                  {{ collectivity.stats.volontaires_count }}
                </dd>
                <dt class="mt-2 text-lg font-medium text-gray-800">
                  Volontaires
                </dt>
              </div>
              <div class="flex flex-col mt-10 sm:mt-0">
                <dd class="text-5xl leading-none font-bold text-gray-800">
                  {{ collectivity.stats.structures_count }}
                </dd>
                <dt class="mt-2 text-lg font-medium text-gray-800">
                  Structures
                </dt>
              </div>

              <div class="flex flex-col mt-10 sm:mt-0">
                <dd class="text-5xl leading-none font-bold text-gray-800">
                  {{ collectivity.stats.participations_count }}
                </dd>
                <dt class="mt-2 text-lg font-medium text-gray-800">
                  Participations
                </dt>
              </div>
            </dl>
        </div>
      </div>
    </div>

    <div class="container mx-auto px-4">
      <div class="mb-16 text-center text-base font-semibold uppercase text-gray-500 tracking-wider">
        Parmi les domaines d'actions populaires
      </div>

      <div v-if="!loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
        <div v-for="(domain, key) in collectivity.stats.domains" :key="key">
          <div
            class="inline-flex bg-blue-900 rounded-md p-3 text-center mb-5"
          >
            <img
              class
              :src="$options.filters.domainIcon(domain.key)"
              style="width:28px;"
            />
          </div>
          <div class="text-lg font-medium text-gray-900">{{ domain.key | cleanDomaineAction }}.</div>
          <div class="mt-2 text-base text-gray-500">{{ domain.name }}</div>
        </div>
      </div>
    </div>

    <div class="container mx-auto px-4">
      <div class="mb-16 text-center text-base font-semibold uppercase text-gray-500 tracking-wider">
        Retrouvez toutes les missions disponibles dans votre commune
      </div>
      <div class="pb-16 flex flex-wrap items-center justify-center" v-if="!loading">
        <div v-for="(city, key) in collectivity.stats.cities" :key="key" class="inline-flex mx-2 px-4 mb-6 py-2 rounded-full text-md font-semibold shadow-md tracking-wide uppercase bg-white text-gray-800 hover:bg-gray-50">
          <router-link :to="`/missions?search=${city.name}`">{{ city.name }}</router-link>
        </div>
      </div>
    </div>

    <div class="bg-blue-900">
      <div class="container mx-auto py-12 pt-16 px-4 sm:py-16 sm:px-6 lg:px-8">
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
      loading: true,
      collectivity: {},
    };
  },
  created() {
    this.$store.commit("setLoading", true);
    getCollectivity(this.slug)
      .then(response => {
        this.collectivity = { ...response.data };
        this.$store.commit("setLoading", false);
        this.loading = false
      })
      .catch(() => {
        this.loading = false;
      });
  },
  methods: {}
};
</script>
