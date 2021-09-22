<template>
  <div>
    <div class="header px-12 flex">
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
    <div class="px-12">
      <ul class="flex flex-wrap transform">
        <li
          v-for="missionTemplate in missionTemplates"
          :key="missionTemplate.id"
          class="card--mission--wrapper"
        >
          <nuxt-link
            :to="`/dashboard/reseaux/${$route.params.id}/modeles-de-mission/${missionTemplate.id}/edit`"
            class="card--mission transition duration-300 ease-in-out shadow hover:shadow-lg h-auto flex flex-col flex-1 bg-white rounded-lg overflow-hidden"
          >
            <div class="thumbnail--wrapper relative will-change-transform">
              <div
                v-if="!missionTemplate.published"
                class="absolute bg-yellow-600 text-white text-sm font-medium px-4 py-1 mx-auto left-[60px] rounded-b-md"
              >
                En attente de validation
              </div>
              <img
                v-if="missionTemplate.photo"
                :src="missionTemplate.photo.large"
                alt="photo du template"
                class="w-full h-full object-cover"
                width="300px"
                height="143px"
              />
            </div>

            <div class="mb-auto p-4">
              <div class="pill-2">{{ missionTemplate.domaine.name.fr }}</div>

              <client-only>
                <v-clamp
                  tag="h2"
                  :max-lines="3"
                  autoresize
                  class="name font-black text-black text-lg relative"
                >
                  {{ missionTemplate.title }}

                  <template slot="after" slot-scope="{ clamped }">
                    <!-- Tooltip if clamped -->
                    <span
                      v-if="clamped"
                      v-tooltip="{
                        delay: { show: 700, hide: 100 },
                        content: missionTemplate.title,
                        hideOnTargetClick: true,
                        placement: 'top',
                      }"
                      class="absolute w-full h-full top-0 left-0"
                    />
                  </template>
                </v-clamp>
              </client-only>

              <h3
                class="structure mt-4 mb-1 truncate text-gray-500 text-sm"
                v-text="reseau.name"
              />
            </div>

            <div class="footer border-t p-4 text-center relative">
              <span class="text-sm font-bold text-primary"> Éditer </span>
            </div>
          </nuxt-link>
        </li>
        <li class="card--mission--wrapper">
          <nuxt-link
            :to="`/dashboard/reseaux/${$route.params.id}/modeles-de-mission/add`"
          >
            <div
              class="card--mission transition duration-300 ease-in-out shadow hover:shadow-lg h-auto flex flex-col flex-1 bg-white rounded-lg overflow-hidden"
            >
              <div
                class="absolute bg-yellow-600 text-white text-sm font-medium px-4 py-1 mx-auto left-[60px] rounded-b-md"
              >
                Validation par un référent
              </div>
              <div
                class="relative flex items-center justify-center"
                style="height: 143px"
              >
                <svg
                  width="59"
                  height="59"
                  viewBox="0 0 59 59"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                  class="mt-8"
                >
                  <path
                    d="M24.8346 8.83401H9.0013C7.3216 8.83401 5.71069 9.50127 4.52296 10.689C3.33523 11.8767 2.66797 13.4876 2.66797 15.1673V50.0007C2.66797 51.6804 3.33523 53.2913 4.52296 54.479C5.71069 55.6667 7.3216 56.334 9.0013 56.334H43.8346C45.5143 56.334 47.1252 55.6667 48.313 54.479C49.5007 53.2913 50.168 51.6804 50.168 50.0007V34.1673M45.6903 4.35634C46.2745 3.75145 46.9734 3.26896 47.7461 2.93703C48.5188 2.60511 49.3498 2.4304 50.1908 2.42309C51.0317 2.41578 51.8657 2.57603 52.644 2.89447C53.4224 3.21292 54.1295 3.68319 54.7241 4.27784C55.3188 4.8725 55.7891 5.57962 56.1075 6.35797C56.4259 7.13631 56.5862 7.97028 56.5789 8.81122C56.5716 9.65215 56.3969 10.4832 56.0649 11.2559C55.733 12.0286 55.2505 12.7274 54.6456 13.3117L27.4566 40.5007H18.5013V31.5453L45.6903 4.35634Z"
                    stroke="black"
                    stroke-opacity="0.1"
                    stroke-width="4"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
              </div>

              <div class="mb-auto p-4">
                <div
                  class="name font-black text-black text-lg relative text-center"
                >
                  Créez un nouveau modèle de mission
                </div>

                <div class="mt-4 mb-1 text-center text-gray-500 text-sm">
                  Ce modèle pourra ensuite être proposé par les antennes de
                  votre réseau afin de faciliter la publication de ses missions.
                </div>
              </div>

              <div class="footer border-t p-4 text-center relative">
                <span class="text-sm font-bold text-primary">
                  Créer un modèle
                </span>
              </div>
            </div>
          </nuxt-link>
        </li>
      </ul>
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
  data() {
    return {}
  },
}
</script>

<style lang="postcss" scoped>
.card--mission {
  backface-visibility: hidden;
  transform: translate3d(0, 0, 0);
  &:hover {
    .thumbnail--wrapper img {
      transform: scale(1.05);
    }
  }
}

.thumbnail--wrapper {
  height: 143px;
  @apply bg-gray-200 overflow-hidden;
  img {
    transition: all 0.25s;
  }
}

.footer {
  .places-left {
    color: #30c48d;
    font-size: 13px;
    &.is-full {
      color: #d42b3b;
    }
  }
}

.name {
  line-height: 22px;
}

.footer {
  border-color: #d9d9d9;
}

.pill-2 {
  border-radius: 35px;
  background-color: #ebf4ff;
  font-size: 11px;
  letter-spacing: 0.01em;
  color: #070191;
  @apply font-bold uppercase py-1 px-3 mb-4 inline-flex;
}

.card--mission--wrapper {
  width: 100%;
  @apply border-0 p-0 mb-6;
  @screen sm {
    width: 280px;
    @apply m-3 flex flex-col;
  }
  @screen md {
    width: 330px;
  }
  @screen lg {
    width: 304px;
  }
  @screen xl {
    width: 330px;
  }
}
</style>
