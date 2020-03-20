<template>
  <div class="bg-gray-100">
    <AppHeader />

    <div class="bg-blue-900 pb-32">
      <div class="container mx-auto px-4">
        <div class="pt-10">
          <h1 class="text-3xl font-bold text-white">Mission disponible</h1>
        </div>
      </div>
    </div>

    <div class="-mt-32 mb-12">
      <div class="container mx-auto px-4 mt-12">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
          <div class="lg:flex">
            <div class="flex-grow px-6 py-8 lg:flex-shrink-1 lg:p-12">
              <h3
                class="text-2xl leading-tight font-extrabold text-gray-900 sm:text-3xl"
              >{{ mission.name }}</h3>

              <div class="mt-12">
                <div
                  class="flex flex-wrap justify-center sm:justify-start items-center text-center sm:text-left"
                >
                  <div v-if="structure && structure.logo" class="flex-shrink-0 sm:pr-4">
                    <img class="h-14 w-14 rounded-full" :src="structure.logo" alt />
                  </div>
                  <div>
                    <h3
                      v-if="structure"
                      class="text-2xl leading-tight font-medium text-gray-900"
                    >{{ structure.name }}</h3>

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
                        <span class="m-1" v-if="structure">{{ structure.full_address }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mt-8 text-center sm:text-left">
                <span
                  class="px-4 py-1 mr-2 mt-4 shadow-md inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-500"
                >{{ mission.format }}</span>
                <span
                  class="px-4 py-1 mr-2 mt-4 shadow-md inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-500"
                >{{ mission.type }}</span>
                <span
                  v-if="mission.periodicite"
                  class="px-4 py-1 mr-2 mt-4 shadow-md inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-500"
                >{{ mission.periodicite }}</span>
              </div>

              <div class="mt-12">
                <div class="flex items-center">
                  <h4
                    class="flex-shrink-0 pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700"
                  >Domaine d'action</h4>
                  <div class="flex-1 border-t-2 border-gray-200"></div>
                </div>
                <div class="mt-8">
                  <ul class="flex flex-wrap -m-1">
                    <li class="flex items-start lg:col-span-1 w-full sm:w-1/2 p-1">
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
                      <p class="ml-3 text-gray-700">{{ mission.name }}</p>
                    </li>
                  </ul>
                </div>
              </div>

              <div class="mt-16">
                <div class="flex items-center">
                  <h4
                    class="flex-shrink-0 pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700"
                  >Publics bénéficiaires</h4>
                  <div class="flex-1 border-t-2 border-gray-200"></div>
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
                      <p class="ml-3 text-gray-700">{{ publicBeneficiaire }}</p>
                    </li>
                  </ul>
                </div>
              </div>

              <div class="mt-16">
                <div class="flex items-center">
                  <h4
                    class="flex-shrink-0 pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700"
                  >Publics volontaires</h4>
                  <div class="flex-1 border-t-2 border-gray-200"></div>
                </div>
                <div class="mt-8">
                  <ul class="flex flex-wrap -m-1">
                    <li
                      v-for="(publicVolontaire,
                      key) in mission.publics_volontaires"
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
                      <p class="ml-3 text-gray-700">{{ publicVolontaire }}</p>
                    </li>
                  </ul>
                </div>
              </div>

              <div class="mt-16">
                <div class="flex items-center">
                  <h4
                    class="flex-shrink-0 pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700"
                  >Commentaire par la structure</h4>
                  <div class="flex-1 border-t-2 border-gray-200"></div>
                </div>

                <div class="mt-6 text-gray-500">
                  <p>{{ mission.description }}</p>
                </div>
              </div>
            </div>

            <div
              class="py-8 px-6 text-center bg-gray-100 lg:flex-shrink-0 lg:flex lg:flex-col lg:justify-center lg:p-12"
            >
              <p class="text-3xl leading-none font-extrabold text-gray-900">
                Rejoignez
                <br />le mouvement
              </p>
              <p class="mt-6 text-sm tracking-wider text-gray-500 uppercase">La structure recherche</p>

              <div class="text-sm">
                <span
                  class="px-6 py-1 shadow-md inline-flex text-lg font-semibold rounded-full bg-green-100 text-green-800"
                >
                  {{ mission.participations_max }}
                  {{
                  mission.participations_max
                  | pluralize(["volontaire", "volontaires"])
                  }}
                </span>
              </div>

              <div class="mt-4 text-sm">
                <div class="mt-6 text-center inline-flex d text-gray-500">
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
                  <div v-if="mission.start_date">
                    <span class="text-gray-400 mr-1 text-xs">Du</span>
                    <span class="mr-1">
                      {{
                      mission.start_date | formatMedium
                      }}
                    </span>
                  </div>
                  <div v-if="mission.end_date">
                    <span class="text-gray-400 mr-1 text-xs">Au</span>
                    {{ mission.end_date | formatMedium }}
                  </div>
                  <div
                    v-if="!mission.start_date && !mission.end_date"
                  >Disponibilité aussitôt que possible</div>
                </div>
              </div>

              <div
                class="mt-4 flex items-center justify-center text-5xl leading-none font-extrabold text-gray-900"
              ></div>

              <div class="mt-6">
                <div class="rounded-md shadow-md rounded-full">
                  <template v-if="$store.getters.isLogged">
                    <el-button
                      v-if="canRegistred"
                      @click="handleClick"
                      class="flex items-center justify-center text-xl px-10 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                    >Proposer votre aide</el-button>
                    <router-link
                      v-else
                      to="/user/missions"
                      class="flex items-center justify-center text-xl px-10 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                    >Vous êtes déjà inscrit !</router-link>
                  </template>
                  <template v-else>
                    <router-link
                      to="/login"
                      class="flex items-center justify-center text-xl px-10 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                    >Proposer votre aide</router-link>
                  </template>
                </div>
                <div
                  class="mt-2 text-center inline-flex text-xs text-gray-500"
                >Vous serez mis en relation avec la structure</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="otherMissions.total > 0" class="container mx-auto px-4 pb-12">
      <div class="bg-white shadow overflow-hidden rounded-lg">
        <div class="bg-white px-4 py-3 flex items-center justify-between sm:px-6">
          <div>
            <p
              class="text-2xl sm:leading-10 font-bold text-gray-900"
            >Autres missions proposées par cette structure</p>
          </div>
        </div>
        <ul>
          <li
            v-for="mission in otherMissions.data"
            :key="mission.id"
            class="border-t border-gray-200"
          >
            <router-link
              :to="`/missions/${mission.id}`"
              class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out"
            >
              <div class="flex items-center px-4 py-4 sm:px-6">
                <div class="min-w-0 flex-1 flex items-start">
                  <div class="hidden sm:block flex-shrink-0" style="margin-top:2px;">
                    <img
                      class="h-12 w-12 rounded-full"
                      src="https://s3.eu-west-2.amazonaws.com/plateforme-prod/uploads/5737034557ef5b8c02c0e46513b98f90/c9c2676be6cd123b7f24038717efe1b3.jpg"
                      alt
                    />
                  </div>
                  <div class="min-w-0 flex-1 sm:px-4 md:grid md:grid-cols-2 md:gap-4">
                    <div class="col-span-2 lg:col-span-1 mb-4 md:mb-0">
                      <div class="font-semibold text-blue-800 truncate">{{ mission.name }}</div>
                      <div class="mt-1 flex items-center text-sm ext-gray-900 font-semibold">
                        <span class="truncate">{{ mission.structure.name }}</span>
                      </div>
                    </div>

                    <div
                      v-if="mission.department"
                      class="flex flex-wrap item-center -mx-2 -my-1 col-span-2 lg:col-span-1 text-sm"
                    >
                      <div class="mx-2 my-1 flex items-center text-s leading-5 text-gray-500">
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
                        {{ mission.city }} ({{ mission.department }})
                      </div>
                      <div class="mx-2 my-1 flex items-center text-s leading-5 text-gray-500">
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
                        {{ mission.periodicite }}
                      </div>

                      <div class="mx-2 my-1 flex items-center text-sm leading-5 text-gray-500">
                        <span
                          class="px-3 inline-flex text-s leading-5 font-semibold rounded-full bg-green-100 text-green-800"
                        >
                          {{ mission.participations_max }}
                          {{
                          mission.participations_max
                          | pluralize(["volontaire", "volontaires"])
                          }}
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div>
                  <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      fill-rule="evenodd"
                      d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                      clip-rule="evenodd"
                    />
                  </svg>
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
import { getMission } from "@/api/mission";
import { addParticipation } from "@/api/participation";
import { fetchStructureAvailableMissions } from "@/api/structure";

export default {
  name: "Mission",
  props: {
    id: {
      type: Number,
      default: null
    }
  },
  computed: {
    structure() {
      return this.mission.structure;
    },
    canRegistred() {
      return true;
    }
  },
  data() {
    return {
      loading: false,
      mission: {},
      otherMissions: {}
    };
  },
  created() {
    this.$store.commit("setLoading", true);
    getMission(this.id)
      .then(response => {
        this.form = response.data;
        this.mission = { ...response.data };
        this.$store.commit("setLoading", false);
        fetchStructureAvailableMissions(this.mission.structure.id)
          .then(response => {
            this.otherMissions = { ...response.data };
          })
          .catch(() => {
            this.loading = false;
          });
      })
      .catch(() => {
        this.loading = false;
      });
  },
  methods: {
    handleClick() {
      this.$confirm(
        "Êtes vous sur de vouloir participer à cette mission ?<br>",
        "Confirmation",
        {
          center: true,
          confirmButtonText: "Oui, je participe",
          cancelButtonText: "Annuler",
          // type: "warning",
          dangerouslyUseHTMLString: true
        }
      )
        .then(() => {
          this.loading = true;
          addParticipation(this.mission.id, this.$store.getters.profile.id)
          .then(() => {
            this.$router.push('/user/missions')
            this.$message({
              message: "Votre participation a été enregistrée et est en attente de validation !",
              type: "success"
            });
            this.loading = false
          }).catch(() => {
            this.loading = false;
          });
        })
        .catch(() => {});
    }
  }
};
</script>
