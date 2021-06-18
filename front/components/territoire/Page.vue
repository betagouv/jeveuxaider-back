<template>
  <div>
    <TerritoireBanner :territoire="territoire" />

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
      @ready="setCities"
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
  computed: {
    logo() {
      return this.territoire.logo?.thumb
    },
  },
  methods: {
    setCities($event) {
      this.$set(this, 'cities', $event)
    },
  },
}
</script>

<style lang="sass" scoped></style>
