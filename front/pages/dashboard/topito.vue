<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          Pour les petits curieux
        </div>
        <div class="mb-12 font-bold text-2-5xl text-gray-800">TOPITO</div>
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
  methods: {
    onChangeTimeframe(filter) {
      this.$refs.benevolesDuMoment.fetch(filter)
      this.$refs.utilisateursLesPlusActifs.fetch(filter)
      this.$refs.organisationsMissions.fetch(filter)
      this.$refs.organisationsParticipations.fetch(filter)
    },
  },
}
</script>

<style></style>
