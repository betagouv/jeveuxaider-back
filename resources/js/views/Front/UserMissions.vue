<template>
  <div class="bg-gray-100 h-full flex flex-col flex-grow">
    <AppHeader />

    <div class="bg-blue-900 pb-32">
      <div class="container mx-auto px-4">
        <div class="pt-10">
          <h1 class="text-3xl font-bold text-white">Missions auxquelles j'ai candidaté</h1>
        </div>
      </div>
    </div>

    <div v-if="!loading" class="-mt-32">
      <div class="container mx-auto px-4 my-12">
        <div
          v-if="participations.data && !participations.data.length"
          class="bg-white rounded-lg shadow-lg overflow-hidden px-6 py-8 lg:p-12"
        >Vous n'avez aucune participation pour le moment.</div>

        <div
          v-else
          v-for="participation in participations.data"
          :key="participation.id"
          class="bg-white rounded-lg overflow-hidden shadow-lg mb-12"
        >
          <div class="border-b border-gray-200">
            <a
              :href="`/missions/${participation.mission.id}`"
              class="block hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition duration-150 ease-in-out"
            >
              <div class="flex items-center px-6 py-8">
                <div class="min-w-0 flex-1 flex items-start">
                  <div class="hidden sm:block flex-shrink-0" style="margin-top:2px;">
                    <img
                      v-if="participation.mission.structure.logo"
                      class="h-12 w-12 rounded-full"
                      :src="participation.mission.structure.logo"
                      :alt="participation.mission.structure.name"
                    />
                    <div
                      v-else
                      class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center"
                    >{{ participation.mission.structure.name[0] }}</div>
                  </div>
                  <div class="min-w-0 flex-1 sm:px-4 md:grid md:grid-cols-2 md:gap-4">
                    <div class="col-span-2 lg:col-span-1 mb-4 md:mb-0">
                      <div
                        class="font-semibold text-blue-800 truncate"
                      >{{ participation.mission.name|labelFromValue('mission_domaines') }}</div>
                      <div class="mt-1 flex items-center text-sm ext-gray-900 font-semibold">
                        <span class="truncate">
                          {{
                          participation.mission.structure.name
                          }}
                        </span>
                      </div>
                    </div>

                    <div
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
                        {{ participation.mission.city }} ({{
                        participation.mission.department
                        }})
                      </div>
                      <div
                        v-if="participation.mission.periodicite"
                        class="mx-2 my-1 flex items-center text-s leading-5 text-gray-500"
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
                        {{ participation.mission.periodicite }}
                      </div>

                      <div class="mx-2 my-1 flex items-center text-sm leading-5 text-gray-500">
                        <span
                          :class="participationStateTheme(participation)"
                          class="inline-flex font-semibold rounded-full"
                        >{{ participation.state }}</span>
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
            </a>
          </div>

          <div class="px-6 py-8">
            <div class="flex flex-wrap -m-2">
              <div
                class="m-2 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white"
              >
                <svg
                  class="flex-shrink-0 mr-2 h-5 w-5 text-gray-400"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"
                  />
                </svg>
                <span>
                  Responsable :
                  {{ participation.mission.tuteur.full_name }}
                </span>
              </div>

              <button
                v-if="participation.mission.tuteur.mobile"
                type="button"
                class="m-2 relative shadow-sm inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white"
              >
                <svg
                  class="-ml-1 mr-2 h-5 w-5 text-gray-400"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"
                  />
                </svg>
                <span>{{ participation.mission.tuteur.mobile }}</span>
              </button>

              <a
                :href="
                  `mailto:${participation.mission.tuteur.email}`
                "
                class="m-2"
              >
                <button
                  type="button"
                  class="relative shadow-sm inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-50 active:text-gray-800"
                >
                  <svg
                    class="-ml-1 mr-2 h-5 w-5 text-gray-400"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884zM18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"
                      clip-rule="evenodd"
                    />
                  </svg>
                  <span>Messagerie</span>
                </button>
              </a>

              <button
                v-if="participation.state == 'En attente de validation'"
                type="button"
                class="m-2 lg:ml-auto relative shadow-sm inline-flex items-center px-4 py-2 border border-red-500 text-red-500 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:bg-red-100"
                @click="onClickCancel(participation)"
              >Annuler ma candidature</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <AppFooter class="mt-auto" />
  </div>
</template>

<script>
import { fetchProfileParticipations } from "@/api/user";
import { cancelParticipation } from "@/api/participation";

export default {
  name: "FrontUserMissions",
  components: {},
  data() {
    return {
      loading: true,
      participations: {}
    };
  },
  created() {
    this.$store.commit("setLoading", true);
    fetchProfileParticipations(this.$store.getters.user.profile.id)
      .then(response => {
        this.form = response.data;
        this.participations = { ...response.data };
        this.$store.commit("setLoading", false);
        this.loading = false;
      })
      .catch(() => {
        this.loading = false;
      });
  },
  methods: {
    onClickCancel(participation){
      this.$confirm(
          `Vous êtes sur le point d'annuler votre candidature. Voulez-vous continuer ?`,
          "Annuler ma candidature",
          {
            confirmButtonText: "Annuler ma candidature",
            confirmButtonClass: "el-button--danger",
            cancelButtonText: "Retour",
            center: true,
            type: "error"
          }
        ).then(() => {
          cancelParticipation(participation.id).then(() => {
            participation.state = 'Candidature annulée'
            this.$message({
              type: "success",
              message: `Votre candidature a été annulée.`
            });
          });
        });
    },
    participationStateTheme(participation) {
      switch (participation.state) {
        case "En attente de validation":
          return "text-orange-400";
        case "Validé":
          return "text-green-800";
        case "Mission en cours":
          return "text-green-400";
        case "Refusé":
          return "text-red-600";
        case "Terminé":
          return "text-green-600";
      }
    }
  }
};
</script>
