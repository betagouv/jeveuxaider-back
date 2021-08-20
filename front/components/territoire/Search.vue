<template>
  <div>
    <section class="section-search">
      <div class="pt-12 pb-32 bg-white">
        <div class="container mx-auto px-4">
          <h2 class="text-center">
            <p class="uppercase text-[#f56565] font-extrabold text-sm mb-4">
              Trouver une mission
            </p>

            <p
              class="text-3xl lg:text-4xl leading-none font-extrabold tracking[-1px] lg:tracking[-2px]"
            >
              Parmi les dernières missions <br class="hidden md:block" />de
              bénévolat {{ territoire.suffix_title }}
            </p>
          </h2>
        </div>
      </div>

      <hr class="opacity-25" />

      <div
        class="pb-12 bg-[#fafaff]"
        :class="[{ 'pb-44': territoire.type != 'city' }]"
      >
        <div class="container mx-auto px-4">
          <div
            class="flex flex-wrap justify-center transform -translate-y-24"
            :class="[
              { '-mb-24': missions.length },
              { '-mb-28': !missions.length },
            ]"
          >
            <nuxt-link
              v-for="mission in missions.slice(0, 3)"
              :key="mission.id"
              class="card--mission--wrapper"
              :to="`/missions-benevolat/${mission.id}/${mission.slug}`"
            >
              <CardMission :mission="mission" />
            </nuxt-link>
          </div>

          <div v-if="moreLink" class="text-center mt-6">
            <nuxt-link :to="moreLink">
              <button
                class="leading-none uppercase shadow-lg text-xs font-extrabold rounded-full text-gray-500 bg-white py-4 px-8 hover:scale-105 transform transition will-change-transform"
              >
                <span v-if="!missions.length">Voir les missions</span>
                <span v-else>Plus de missions</span>
              </button>
            </nuxt-link>
          </div>
        </div>
      </div>

      <hr v-if="territoire.type != 'city'" class="opacity-25" />
    </section>
  </div>
</template>

<script>
export default {
  props: {
    territoire: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      missions: [],
    }
  },
  async fetch() {
    const missions = await this.$api.fetchTerritoirePromotedMissions(
      this.territoire.id
    )
    this.missions = missions.data
  },
  computed: {
    moreLink() {
      let link = null
      switch (this.territoire.type) {
        case 'department':
          link = `/missions-benevolat?refinementList[department_name][0]=${this.$options.filters.fullDepartmentFromValue(
            this.territoire.department
          )}`
          break
        case 'city':
          link = `/missions-benevolat?refinementList[type][0]=Mission en présentiel&aroundLatLng=${this.territoire.latitude},${this.territoire.longitude}&place=${this.territoire.zips[0]}&aroundRadius=35000`
          break
      }
      return link
    },
  },
}
</script>

<style lang="postcss" scoped>
.card--mission--wrapper {
  width: 100%;
  @apply border-0 shadow-none p-0 mb-6;
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
