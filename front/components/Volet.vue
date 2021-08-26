<template>
  <div class="component--volet">
    <div class="flex justify-between items-start mb-6">
      <template v-if="link">
        <router-link
          :to="link"
          class="text-xl font-semibold hover:text-primary"
          >{{ title }}</router-link
        >
      </template>
      <template v-else>
        <div class="text-xl font-semibold">{{ title }}</div>
      </template>

      <i
        class="el-icon-close cursor-pointer text-xl font-semibold mt-1 text-gray-400 hover:text-gray-900"
        @click="handleCloseVolet"
      />
    </div>
    <slot> </slot>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: [String, Boolean],
      required: false,
      default: 'Informations',
    },
    link: {
      type: [String, Boolean],
      required: false,
      default: false,
    },
  },
  methods: {
    handleCloseVolet() {
      this.$store.commit('volet/hide')
    },
  },
}
</script>
