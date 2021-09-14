<template>
  <div>
    <TerritoireBanner :territoire="territoire" />

    <div v-if="territoire.type == 'city' && logo" class="bg-white pt-12">
      <img
        :src="logo"
        :alt="territoire.name"
        class="mx-auto"
        style="max-height: 110px"
      />
    </div>

    <TerritoireSearch :territoire="territoire" />
    <TerritoirePromote :territoire="territoire" />

    <TerritoireCities
      v-if="territoire.type == 'city'"
      :territoire="territoire"
      :cities="cities"
    />

    <TerritoireAssociations
      v-if="
        territoire.promoted_organisations &&
        territoire.promoted_organisations.length
      "
      :territoire="territoire"
    />
    <TerritoireEngagement :territoire="territoire" />
    <TerritoireSubscribe :territoire="territoire" />
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
      cities: [],
    }
  },
  async fetch() {
    if (this.territoire.type == 'city') {
      const { data: cities } = await this.$api.getCitiesWithAvailableMissions(
        this.territoire.id
      )
      this.cities = cities
    }
  },
  computed: {
    logo() {
      return this.territoire.logo?.thumb
    },
  },
}
</script>
