<template>
  <div class="mission-search p-4 sm:p-6 md:p-8">
    <div class="flex items-center">
      <div
        class="hidden sm:block flex-shrink-0 bg-primary rounded-md p-3 text-center"
      >
        <img class="" :src="mission.domaine_image" style="width: 28px;" />
      </div>
      <div class="min-w-0 flex-1 sm:pl-4">
        <div
          class="flex items-center justify-between flex-wrap sm:flex-no-wrap -m-2"
        >
          <div class="m-2 min-w-0 flex-shrink">
            <div
              class="text-sm leading-5 uppercase font-medium text-gray-500 truncate"
              v-text="mission.domaine_name"
            />
            <div
              class="text-sm md:text-base lg:text-lg xl:text-xl font-semibold text-gray-900 truncate flex"
            >
              <span> {{ mission.name }}</span>
              <svg
                v-if="mission.provider == 'api_engagement'"
                style="width: 20px; display: none;"
                class="ml-2 external-link"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"
                />
                <path
                  d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"
                />
              </svg>
            </div>
          </div>

          <div
            v-if="mission.has_places_left && mission.places_left > 0"
            class="m-2 flex-shrink-0 border-transparent px-4 py-2 border text-xs lg:text-sm font-medium rounded-full text-white shadow-md"
            style="background: #31c48d;"
          >
            <template>
              {{ mission.places_left | formatNumber }}
              {{
                mission.places_left
                  | pluralize(['bénévole recherché', 'bénévoles recherchés'])
              }}
            </template>
          </div>
          <div
            v-else
            class="m-2 flex-shrink-0 border-transparent px-4 py-2 border text-xs lg:text-sm font-medium rounded-full text-white shadow-md"
            style="background: #d2d6dc;"
          >
            Complet
          </div>
        </div>
      </div>
    </div>

    <div class="flex justify-between">
      <div class="flex items-start text-sm text-gray-500 mt-4">
        <svg
          v-if="mission.city"
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
          v-if="mission.city"
          v-text="
            `${mission.city} (${mission.department}) - ${mission.structure.name}`
          "
        />
        <span v-else v-text="`${mission.structure.name}`" />
      </div>
      <div v-if="mission.publisher_name" class="mt-1 text-sm flex items-center">
        <div class="mr-4">
          <div class="text-gray-500">
            Mission proposée par
          </div>
          <div class="font-bold text-black">
            {{ mission.publisher_name }}
          </div>
        </div>
        <div>
          <img
            style="max-width: 90px;"
            src="https://jagispourlanature.org/themes/custom/jagis/src/images/logo-jagis-pour-la-nature.svg"
            alt="J'agis pour la Nature"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    mission: {
      type: Object,
      default: null,
    },
  },
}
</script>

<style>
.mission-search:hover .external-link {
  display: block !important;
}
</style>
