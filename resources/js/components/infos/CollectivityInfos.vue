<template>
  <div class="text-xs text-gray-600 relative">
    <div v-if="collectivity.state" class="mb-2 flex">
      <div class="card-label">
        Statut
      </div>
      <div class="text-gray-900 flex-1">
        {{ collectivity.state | labelFromValue('collectivities_states') }}
      </div>
    </div>
    <div v-if="collectivity.title" class="mb-2 flex">
      <div class="card-label">
        Titre
      </div>
      <div class="text-gray-900 flex-1">
        {{ collectivity.title }}
      </div>
    </div>
    <div v-if="collectivity.zips" class="mb-2 flex">
      <div class="card-label">
        Codes postaux
      </div>
      <div class="text-gray-900 flex-1">
        {{ collectivity.zips.join(', ') }}
      </div>
    </div>
    <div v-if="collectivity.description" class="mb-2 flex">
      <div class="card-label">
        Description
      </div>
      <div class="text-gray-900 flex-1">
        <ReadMore
          more-class="cursor-pointer uppercase font-bold text-xs text-gray-800"
          more-str="Lire plus"
          :text="collectivity.description"
          :max-chars="120"
        ></ReadMore>
      </div>
    </div>
  </div>
</template>

<script>
import ReadMore from '@/components/ReadMore'

export default {
  name: 'CollectivityInfos',
  components: {
    ReadMore,
  },
  props: {
    collectivity: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {}
  },
  created() {},

  methods: {},
}
</script>
