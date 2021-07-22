<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          TOPITO Pour les petits curieux
        </div>
        <div class="mb-12 font-bold text-2-5xl text-gray-800 uppercase">
          {{ title }}
        </div>
      </div>
      <div>
        <DropdownTimeframeButton @change="onChangeTimeframe" />
      </div>
    </div>

    <div class="px-12">
      <div class="flex flex-col space-y-12">
        <div class="grid grid-cols-3 gap-6">
          <TopitoBenevolesDuMoment ref="benevolesDuMoment" />
          <TopitoUtilisateursLesPlusActifs ref="utilisateursLesPlusActifs" />
        </div>
        <div class="grid grid-cols-3 gap-6">
          <TopitoOrganisationsMissions ref="organisationsMissions" />
          <TopitoOrganisationsParticipations
            ref="organisationsParticipations"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  asyncData({ $api, params, error, store }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
  },
  data() {
    return {
      filters: {
        daterange: 'current-month',
      },
    }
  },
  computed: {
    title() {
      let title = 'Classements'

      if (this.filters.daterange == 'current-month') {
        title = this.$dayjs().format('MMMM YYYY')
      }
      if (this.filters.daterange == 'last-month') {
        title = this.$dayjs().subtract(1, 'month').format('MMMM YYYY')
      }
      if (this.filters.daterange == 'current-year') {
        title = 'Année ' + this.$dayjs().format('YYYY')
      }
      if (this.filters.daterange == 'last-year') {
        title = 'Année ' + this.$dayjs().subtract(1, 'year').format('YYYY')
      }
      if (this.filters.daterange == 'all') {
        title = 'Classements depuis le début'
      }
      return title
    },
  },
  methods: {
    onChangeTimeframe(value) {
      this.filters = { daterange: value }
      this.$refs.benevolesDuMoment.fetch(this.filters)
      this.$refs.utilisateursLesPlusActifs.fetch(this.filters)
      this.$refs.organisationsMissions.fetch(this.filters)
      this.$refs.organisationsParticipations.fetch(this.filters)
    },
  },
}
</script>

<style></style>
