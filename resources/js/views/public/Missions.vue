<template>
  <div class="bg-gray-100">
    <breadcrumb
      theme="dark"
      :items="[{ label: 'Missions de bénévolat', link: '/missions' }]"
    />

    <MissionsSearch />
  </div>
</template>

<script>
import metadatas from '@/utils/metadatas.json'
import MissionsSearch from '@/components/MissionsSearch'

export default {
  name: 'FrontMissions',
  metaInfo: metadatas.filter((item) => item.name == 'FrontMissions')[0]
    .metaInfo,
  components: {
    MissionsSearch,
  },
  data() {
    return {}
  },
}
</script>
