<template>
  <Volet v-if="$store.getters['volet/active']">
    <div class="text-xs text-gray-600 uppercase text-center mt-8 mb-6">
      {{ row.name }}
    </div>
    <!-- <el-card shadow="never" class="overflow-visible relative"> RES </el-card> -->
    <StructureApiSearchInputWithResults
      :initial-value="row.name"
      @submitted="onOrganisationSubmit"
    />
  </Volet>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      structureApi: null,
      form: {},
    }
  },
  computed: {
    row() {
      return this.$store.getters['volet/row']
    },
  },
  watch: {
    row: {
      immediate: true,
      deep: false,
      handler(newValue, oldValue) {
        this.form = { ...newValue }
      },
    },
  },
  methods: {
    onOrganisationSubmit() {
      console.log('onOrganisationSubmit')
      this.$emit('updated')
    },
  },
}
</script>
