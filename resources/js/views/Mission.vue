<template>
  <div class="bg-gray-100">
    <AppHeader />

    <div class="bg-primary pb-32">
      <div v-if="!loading" class="container mx-auto px-4">
        <div class="pt-10">
          <h1
            v-if="mission.has_places_left"
            class="text-3xl font-bold text-white"
          >
            Mission disponible
          </h1>
          <h1 v-else class="text-3xl font-bold text-white">Mission complète</h1>
        </div>
        <div class="my-4 flex flex-wrap">
          <span
            v-if="mission.template"
            class="bg-gray-100 text-blue-900 rounded px-2 py-1 mr-3 mt-3"
            >{{ mission.template.domaine.name.fr }}</span
          >
          <span
            v-else
            class="bg-gray-100 text-blue-900 rounded px-3 py-1 mr-3 mt-3"
            >{{ mission.domaine.name.fr }}</span
          >
          <template v-if="mission.tags">
            <span
              v-for="tag in mission.tags"
              :key="tag.id"
              class="bg-gray-100 text-blue-900 rounded px-2 py-1 mr-3 mt-3"
            >
              {{ tag.name.fr }}
            </span>
          </template>
        </div>
      </div>
    </div>

    <template v-if="!loading">
      <div class="-mt-32 mb-12">
        <div class="container mx-auto px-4 mt-12">
          <div class="bg-white rounded-lg shadow-lg">
            <div class="lg:flex">
              <div class="flex-grow px-6 py-8 lg:flex-shrink-1 lg:p-12">
                <h3
                  class="text-2xl leading-tight font-extrabold text-gray-900 sm:text-3xl"
                >
                  {{ mission.name }}
                </h3>
                <div class="mt-8">
                  <div
                    class="flex flex-wrap justify-center sm:justify-start items-center text-center sm:text-left"
                  >
                    <div v-if="structure" class="flex-shrink-0 sm:pr-4">
                      <img
                        v-if="structure.logo"
                        class="h-14 w-14 rounded-full"
                        :src="structure.logo"
                        alt
                      />
                      <div
                        v-else
                        class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center"
                      >
                        {{ structure.name[0] }}
                      </div>
                    </div>
                    <div>
                      <h3
                        v-if="structure"
                        class="text-2xl leading-tight font-medium text-gray-900"
                      >
                        {{ structure.name }}
                      </h3>

                      <div class="mt-4 sm:mt-2">
                        <div
                          class="flex flex-wrap items-center justify-center sm:justify-start text-sm leading-tight text-gray-500 -m-1"
                        >
                          <svg
                            class="flex-shrink-0 m-1 h-5 w-5 text-gray-400 w-full sm:w-auto"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                              clip-rule="evenodd"
                            />
                          </svg>
                          <span v-if="mission.full_address" class="m-1">
                            {{ mission.full_address }}
                          </span>
                          <span v-else-if="mission.departement" class="m-1"
                            >Département : {{ mission.departement }}</span
                          >
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mt-8 text-center sm:text-left">
                  <span
                    class="px-4 py-1 mr-2 mt-4 shadow-md inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-500"
                    >{{ mission.format }}</span
                  >
                  <span
                    class="px-4 py-1 mr-2 mt-4 shadow-md inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-500"
                    >{{ mission.type }}</span
                  >
                </div>

                <div class="mt-12">
                  <div class="flex items-center">
                    <h4
                      class="pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700"
                    >
                      Objectif de la mission
                    </h4>
                    <div class="flex-1 border-t-2 border-gray-200" />
                  </div>
                  <div
                    class="mt-8 ml-3 text-gray-700"
                    v-html="mission.objectif"
                  ></div>
                </div>

                <div class="mt-12">
                  <div class="flex items-center">
                    <h4
                      class="pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700"
                    >
                      Description de la mission et règles à appliquer
                      impérativement
                    </h4>
                    <div class="flex-1 border-t-2 border-gray-200" />
                  </div>
                  <div
                    class="mt-8 ml-3 text-gray-700"
                    v-html="mission.description"
                  ></div>
                </div>

                <div
                  v-if="mission.information && mission.state == 'Validée'"
                  class="mt-12"
                >
                  <div class="flex items-center">
                    <h4
                      class="pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700"
                    >
                      Commentaires de la structure
                    </h4>
                    <div class="flex-1 border-t-2 border-gray-200" />
                  </div>
                  <div
                    class="mt-8 ml-3 text-gray-700 bg-gray-100 p-5 rounded"
                    v-html="mission.information"
                  ></div>
                </div>

                <div class="mt-16">
                  <div class="flex items-center">
                    <h4
                      class="pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700"
                    >
                      Publics bénéficiaires
                    </h4>
                    <div class="flex-1 border-t-2 border-gray-200" />
                  </div>

                  <div class="mt-8">
                    <ul class="flex flex-wrap -m-1">
                      <li
                        v-for="(publicBeneficiaire,
                        key) in mission.publics_beneficiaires"
                        :key="key"
                        class="flex items-start lg:col-span-1 w-full sm:w-1/2 p-1"
                      >
                        <div class="flex-shrink-0" style="margin-top: 2px;">
                          <svg
                            class="h-5 w-5 text-green-400"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                              clip-rule="evenodd"
                            />
                          </svg>
                        </div>
                        <p class="ml-3 text-gray-700">
                          {{ publicBeneficiaire }}
                        </p>
                      </li>
                    </ul>
                  </div>
                </div>

                <div
                  v-if="mission.state == 'Validée' && mission.commentaire"
                  class="mt-16"
                >
                  <div class="flex flex-wrap items-center">
                    <h4
                      class="pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700"
                    >
                      Commentaire par l'organisation
                    </h4>
                    <div
                      class="flex-1 border-t-2 border-gray-200 mt-2 sm:mt-0"
                    />
                  </div>
                  <div
                    class="mt-6 text-gray-500"
                    v-html="mission.commentaire"
                  ></div>
                </div>

                <div
                  v-if="mission.template && [4].includes(mission.template.id)"
                  class="mt-16"
                >
                  <div class="flex flex-wrap items-center">
                    <h4
                      class="pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700"
                    >
                      Quelques pistes pour l'écoute téléphonique
                    </h4>
                    <div
                      class="flex-1 border-t-2 border-gray-200 mt-2 sm:mt-0"
                    />
                  </div>

                  <div class="mt-6 text-gray-500">
                    <p>
                      <a
                        class="inline-flex items-center hover:underline"
                        target="_blank"
                        href="/files/PFP_Asso_Fiche_telephonie_COVID19__2020_03_23_.pdf"
                      >
                        Télécharger la fiche pratique
                        <svg
                          style="margin-top: 2px;"
                          data-v-18b25a0c
                          fill="currentColor"
                          viewBox="0 0 20 20"
                          class="h-4 w-4 text-gray-400"
                        >
                          <path
                            data-v-18b25a0c
                            fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"
                          />
                        </svg>
                      </a>
                    </p>
                  </div>
                </div>
              </div>

              <div
                class="aside text-center bg-gray-100 lg:rounded-r-lg lg:flex-shrink-0"
              >
                <div class="sticky top-0 py-8 px-6 lg:p-12">
                  <p class="text-3xl leading-none font-extrabold text-gray-900">
                    Rejoignez
                    <br />le mouvement
                  </p>
                  <p
                    class="mt-6 text-sm tracking-wider text-gray-500 uppercase"
                  >
                    L'organisation recherche
                  </p>

                  <div class="text-sm">
                    <span
                      class="px-6 py-1 shadow-md inline-flex text-lg font-semibold rounded-full bg-green-100 text-green-800"
                    >
                      {{ mission.participations_max | formatNumber }}
                      {{
                        mission.participations_max
                          | pluralize(['bénévole', 'bénévoles'])
                      }}
                    </span>
                  </div>

                  <div class="mt-4 text-sm">
                    <div v-if="mission.has_places_left">
                      {{ mission.places_left | formatNumber }}
                      {{
                        mission.places_left
                          | pluralize(['place restante', 'places restantes'])
                      }}
                    </div>
                    <div v-else>Complet</div>
                  </div>

                  <div class="mt-4 text-sm">
                    <div
                      class="mt-6 text-center inline-flex justify-center flex-wrap text-gray-500"
                    >
                      <svg
                        class="flex-shrink-0 mr-2 h-5 w-5 text-gray-400"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                          clip-rule="evenodd"
                        />
                      </svg>
                      <div v-if="mission.start_date" class="w-full sm:w-auto">
                        <span class="text-gray-400 mr-1 text-xs">Du</span>
                        <span class="mr-1">
                          {{ mission.start_date | formatMedium }}
                        </span>
                      </div>
                      <div v-if="mission.end_date" class="w-full sm:w-auto">
                        <span class="text-gray-400 mr-1 text-xs">Au</span>
                        {{ mission.end_date | formatMedium }}
                      </div>
                      <div v-if="!mission.start_date && !mission.end_date">
                        Disponibilité aussitôt que possible
                      </div>
                    </div>
                  </div>

                  <div
                    class="mt-4 flex items-center justify-center text-5xl leading-none font-extrabold text-gray-900"
                  />

                  <div class="mt-6">
                    <template v-if="mission.state">
                      <template v-if="mission.state == 'Validée'">
                        <template v-if="mission.has_places_left">
                          <template v-if="$store.getters.isLogged">
                            <el-button
                              v-if="canRegistered"
                              class="inline-flex items-center justify-center text-xl px-10 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                              @click="handleClick"
                              >Proposer votre aide</el-button
                            >
                            <router-link
                              v-else
                              to="/user/missions"
                              class="inline-flex items-center justify-center text-xl px-10 py-3 border border-transparent text-base font-medium rounded-md text-green-800 bg-green-100 hover:bg-green-200 cursor-pointer focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                              >Vous êtes déjà inscrit !</router-link
                            >
                          </template>

                          <template v-else>
                            <router-link
                              to="/login"
                              class="inline-flex items-center justify-center text-xl px-10 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                              >Proposer votre aide</router-link
                            >
                          </template>
                        </template>
                      </template>

                      <template v-else>
                        <span
                          class="bg-orange-300 inline-flex items-center justify-center px-10 py-3 rounded-md text-sm font-medium"
                        >
                          Cette mission a le statut
                          {{ mission.state.toLowerCase() }}
                        </span>
                      </template>
                    </template>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
    <template v-else>
      <front-mission-loading />
    </template>

    <div v-if="otherMissions.total > 0" class="container mx-auto px-4 pb-12">
      <div class="bg-white shadow overflow-hidden rounded-lg">
        <div
          class="bg-white px-4 py-3 flex items-center justify-between sm:px-6"
        >
          <div>
            <p class="text-2xl sm:leading-10 font-bold text-gray-900">
              Autres missions proposées par cette structure
            </p>
          </div>
        </div>
        <ul>
          <li
            v-for="otherMission in otherMissions.data"
            :key="otherMission.id"
            class="border-t border-gray-200"
          >
            <router-link
              class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out"
              :to="`/missions/${otherMission.id}`"
            >
              <div class="p-4 sm:p-6 md:p-8">
                <div class="flex items-center">
                  <div
                    class="hidden sm:block flex-shrink-0 bg-primary rounded-md p-3 text-center"
                  >
                    <img
                      v-if="otherMission.template"
                      class
                      :src="otherMission.template.image"
                      style="width: 28px;"
                    />
                    <img
                      v-else
                      class
                      :src="otherMission.domaine.image"
                      style="width: 28px;"
                    />
                  </div>
                  <div class="min-w-0 flex-1 sm:pl-4">
                    <div
                      class="flex items-center justify-between flex-wrap sm:flex-no-wrap -m-2"
                    >
                      <div class="m-2 min-w-0 flex-shrink">
                        <div
                          class="text-sm leading-5 uppercase font-medium text-gray-500 truncate"
                          v-text="otherMission.type"
                        />
                        <div
                          class="text-sm md:text-base lg:text-lg xl:text-xl font-semibold text-gray-900 truncate"
                        >
                          {{ otherMission.name }}
                        </div>
                      </div>

                      <div
                        v-if="
                          otherMission.has_places_left &&
                          otherMission.places_left > 0
                        "
                        class="m-2 flex-shrink-0 border-transparent px-4 py-2 border text-xs lg:text-sm font-medium rounded-full text-white shadow-md"
                        style="background: #31c48d;"
                      >
                        <template
                          v-if="
                            otherMission.has_places_left &&
                            otherMission.places_left > 0
                          "
                        >
                          {{ otherMission.places_left | formatNumber }}
                          {{
                            otherMission.places_left
                              | pluralize([
                                'bénévole recherché',
                                'bénévoles recherchés',
                              ])
                          }}
                        </template>
                        <template v-else>Complet</template>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mt-4 flex items-start text-sm text-gray-500">
                  <svg
                    class="flex-shrink-0 mr-2 h-5 w-5 text-gray-400"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                      clip-rule="evenodd"
                    />
                  </svg>
                  <span
                    v-text="
                      `${otherMission.city} (${otherMission.department}) - ${mission.structure.name}`
                    "
                  />
                </div>
              </div>
            </router-link>
          </li>
        </ul>
      </div>
    </div>
    <AppFooter />
  </div>
</template>

<script>
import { getMission } from '@/api/mission'
import { addParticipation } from '@/api/participation'
import { fetchStructureAvailableMissions } from '@/api/structure'
import FrontMissionLoading from '@/components/loadings/FrontMissionLoading'

export default {
  name: 'Mission',
  components: {
    FrontMissionLoading,
  },
  props: {
    id: {
      type: Number,
      default: null,
    },
  },
  data() {
    return {
      loading: true,
      mission: {},
      otherMissions: {},
    }
  },
  computed: {
    structure() {
      return this.mission.structure
    },
    hasParticipation() {
      return this.$store.getters.profile.participations.filter(
        (participation) => participation.mission_id == this.id
      )
    },
    canRegistered() {
      return this.hasParticipation.length > 0 ? false : true
    },
  },
  created() {
    getMission(this.id)
      .then((response) => {
        this.form = response.data
        this.mission = { ...response.data }
        this.loading = false
        fetchStructureAvailableMissions(this.mission.structure.id, {
          exclude: this.id,
        })
          .then((response) => {
            this.otherMissions = response.data
          })
          .catch(() => {
            this.loading = false
          })
      })
      .catch(() => {
        this.loading = false
      })
  },
  methods: {
    handleClick() {
      this.$confirm(
        'Êtes vous sur de vouloir participer à cette mission ?<br>',
        'Confirmation',
        {
          center: true,
          confirmButtonText: 'Oui, je participe',
          cancelButtonText: 'Annuler',
          // type: "warning",
          dangerouslyUseHTMLString: true,
        }
      )
        .then(() => {
          this.loading = true
          addParticipation(this.mission.id, this.$store.getters.profile.id)
            .then(() => {
              this.$router.push('/user/missions')
              this.$message({
                message:
                  'Votre participation a été enregistrée et est en attente de validation !',
                type: 'success',
              })
              this.loading = false
            })
            .catch(() => {
              this.loading = false
            })
        })
        .catch(() => {})
    },
  },
}
</script>

<style lang="sass" scoped>
.aside
  @screen lg
    max-width: 410px
</style>
