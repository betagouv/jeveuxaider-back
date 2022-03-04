<template>
  <Volet
    v-if="$store.getters['volet/active']"
    title="API Engagement"
    class="flex flex-col h-full"
  >
    <div class="text-xs text-gray-600 uppercase text-center mt-8 mb-6">
      <nuxt-link
        class="font-semibold text-sm my-3 text-primary text-center"
        :to="`/dashboard/structure/${row.id}`"
        target="_blank"
      >
        {{ row.name }}
      </nuxt-link>
    </div>

    <StructureApiSearchInputWithResults
      class="flex-1 overflow-y-auto"
      :initial-search="row.rna ? row.rna : row.name"
      :initial-city="row.rna ? null : row.city"
      :structure="row"
      @selected="onOrganisationSelected"
    />

    <div class="border-t pt-4 flex items-end justify-end gap-2">
      <el-button :loading="loadingNA" @click="onSubmitRnaNA"
        >RNA non applicable</el-button
      >
      <el-button
        v-if="rna && rna != 'N/A'"
        type="primary"
        :loading="loading"
        @click="onSubmit"
        >Assigner</el-button
      >
    </div>
  </Volet>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      loadingNA: false,
      rna: null,
      api_id: null,
    }
  },
  computed: {
    row() {
      return this.$store.getters['volet/row']
    },
  },
  // watch: {
  //   row: {
  //     immediate: true,
  //     deep: false,
  //     handler(newValue, oldValue) {
  //       this.form = { ...newValue }
  //     },
  //   },
  // },
  methods: {
    onOrganisationSelected(selected) {
      this.rna = selected.rna
      this.api_id = selected._id
    },
    async onSubmit() {
      this.loading = true
      await this.$api.assignStructureRna(this.row.id, {
        rna: this.rna,
        api_id: this.api_id,
      })
      this.loading = false
      this.rna = null
      this.api_id = null

      this.$emit('updated')
    },
    async onSubmitRnaNA() {
      this.loadingNA = true
      await this.$api.assignStructureRna(this.row.id, {
        rna: 'N/A',
        api_id: 'N/A',
      })
      this.loadingNA = false
      this.rna = null
      this.api_id = null
      this.$emit('updated')
    },
  },
}
</script>
