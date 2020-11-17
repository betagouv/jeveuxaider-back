<template>
  <div class="mission-search p-4 sm:p-6 md:p-8">
    <div class="flex items-center">
      <div
        class="hidden sm:block flex-shrink-0 rounded-md p-3 text-center"
        :class="color ? `bg-${color}` : 'bg-primary'"
      >
        <img class="" :src="mission.domaine_image" style="width: 28px" />
      </div>
      <div class="min-w-0 flex-1 sm:pl-4">
        <div
          class="flex items-center justify-between flex-wrap sm:flex-no-wrap -m-2"
        >
          <div class="m-2 min-w-0 flex-shrink">
            <div
              class="text-sm leading-5 uppercase font-medium text-gray-500 truncate"
              v-text="mission.structure.name"
            />
            <div
              class="text-sm md:text-base lg:text-lg xl:text-xl font-semibold text-gray-900 truncate flex items-center"
            >
              <span class="truncate"> {{ mission.name }}</span>
              <svg
                v-if="mission.provider == 'api_engagement'"
                style="width: 20px; display: none"
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
            style="background: #31c48d"
          >
            <template v-if="mission.publisher_name == 'Benevolt'">
              Plusieurs bénévoles recherchés
            </template>
            <template v-else>
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
            style="background: #d2d6dc"
          >
            <span v-if="mission.has_places_left === false">Complet</span>
            <span v-else>Nombre de places non défini</span>
          </div>
        </div>
      </div>
    </div>

    <div class="flex flex-col lg:flex-row lg:justify-between">
      <div
        class="flex items-center flex-wrap text-s leading-5 text-gray-500 mt-4"
      >
        <template
          v-if="mission.city && mission.type == 'Mission en présentiel'"
        >
          <span
            v-if="mission.department"
            class="mr-3 mt-1 px-2.5 py-1.5 border border-gray-200 text-xs leading-4 font-medium rounded-full text-gray-500 bg-white"
            >Mission en présentiel - {{ mission.city }} ({{
              mission.department
            }})</span
          >
          <span
            v-else
            class="mr-3 mt-1 px-2.5 py-1.5 border border-gray-200 text-xs leading-4 font-medium rounded-full text-gray-500 bg-white"
            >Mission en présentiel - {{ mission.city }}</span
          >
        </template>
        <template v-else>
          <span
            class="mr-3 mt-1 px-2.5 py-1.5 border border-gray-200 text-xs leading-4 font-medium rounded-full text-gray-500 bg-white"
            >Mission à distance</span
          >
        </template>

        <span
          v-if="mission.domaines[0]"
          class="mr-3 mt-1 px-2.5 py-1.5 border border-gray-200 text-xs leading-4 font-medium rounded-full text-gray-500 bg-white"
          >{{ mission.domaines[0] }}</span
        >
      </div>
      <div
        v-if="
          mission.publisher_name && mission.publisher_name != 'Réserve Civique'
        "
        class="mt-3 lg:mt-1 text-sm flex items-center"
      >
        <div class="mr-4">
          <div class="text-gray-500">Mission proposée par</div>
          <div class="font-bold text-black">
            {{ mission.publisher_name }}
          </div>
        </div>
        <div>
          <img
            style="max-width: 90px"
            :src="mission.publisher_logo"
            :alt="mission.publisher_name"
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
    color: {
      type: String,
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
