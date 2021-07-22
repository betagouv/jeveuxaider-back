<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          Pour les petits curieux
        </div>
        <div class="mb-12 font-bold text-2-5xl text-gray-800">TOPITO</div>
      </div>
    </div>

    <div class="px-12">
      <div class="flex flex-col space-y-12">
        <div class="grid grid-cols-3 gap-6">
          <BenevolesDuMoment />
          <UtilisateursLesPlusActifs />
        </div>
        <div class="grid grid-cols-3 gap-6">
          <OrganisationsMissions />
          <OrganisationsParticipations />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BenevolesDuMoment from '@/components/topito/BenevolesDuMoment'
import UtilisateursLesPlusActifs from '@/components/topito/UtilisateursLesPlusActifs'
import OrganisationsMissions from '@/components/topito/OrganisationsMissions'
import OrganisationsParticipations from '@/components/topito/OrganisationsParticipations'

export default {
  components: {
    BenevolesDuMoment,
    UtilisateursLesPlusActifs,
    OrganisationsMissions,
    OrganisationsParticipations,
  },
  layout: 'dashboard',
  asyncData({ $api, params, error, store }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }

    // const benevoles = await $api.fetchTopitoBenevolesDuMoment()
    // const utiliseurs = await $api.fetchTopitoUtilisateursLesPlusActifs()
    // const organisationsMissions = await $api.fetchTopitoOrganisationsMissions()
    // const organisationsParticipations =
    //   await $api.fetchTopitoOrganisationsParticipations()
  },
}
</script>

<style></style>
