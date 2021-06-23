<template>
  <div>
    <TerritoireBanner :territoire="territoire" :cities="cities" />

    <div
      v-if="territoire.type == 'collectivity' && logo"
      class="bg-white pt-12"
    >
      <img
        :src="logo"
        :alt="territoire.name"
        class="mx-auto"
        style="max-height: 110px"
      />
    </div>

    <TerritoireSearch :territoire="territoire" :cities="cities" />
    <TerritoirePromote :territoire="territoire" />

    <TerritoireCities
      v-if="territoire.type == 'collectivity'"
      :territoire="territoire"
      :cities="cities"
    />

    <TerritoireAssociations :territoire="territoire" />
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
    if (this.territoire.type == 'collectivity') {
      const { data: cities } = await this.$api.getCollectivityCities(
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

<style lang="sass" scoped></style>
