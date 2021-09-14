<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          TOPITO Pour les petits curieux (bêta)
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
        <div class="grid grid-cols-2 gap-6">
          <TopitoParticipations :daterange="daterange" />
          <TopitoMarketPlace :daterange="daterange" />
          <TopitoOrganisationsMissions :daterange="daterange" />
          <TopitoOrganisationsParticipations :daterange="daterange" />
          <TopitoUsersActivities :daterange="daterange" />
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
      daterange: 'last-30-days',
    }
  },
  computed: {
    title() {
      let title = 'Classements'

      if (this.daterange == 'last-30-days') {
        title = 'Les 30 derniers jours'
      }
      if (this.daterange == 'last-7-days') {
        title = 'Les 7 derniers jours'
      }
      if (this.daterange == 'current-month') {
        title = this.$dayjs().format('MMMM YYYY')
      }
      if (this.daterange == 'last-month') {
        title = this.$dayjs().subtract(1, 'month').format('MMMM YYYY')
      }
      if (this.daterange == 'current-year') {
        title = 'Année ' + this.$dayjs().format('YYYY')
      }
      if (this.daterange == 'last-year') {
        title = 'Année ' + this.$dayjs().subtract(1, 'year').format('YYYY')
      }
      if (this.daterange == 'all') {
        title = 'Classements depuis le début'
      }
      return title
    },
  },
  methods: {
    onChangeTimeframe(value) {
      this.daterange = value
    },
  },
}
</script>

<style></style>
