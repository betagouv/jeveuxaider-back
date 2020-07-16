<template>
  <el-card
    shadow="never"
    class="mr-5 mb-5 p-5 uppercase hover:border-blue-900 cursor-pointer"
    style="width: 330px;"
  >
    <div @click.prevent="onClick">
      <div class="label mb-3 text-lg font-bold text-secondary">
        Places disponibles
      </div>
      <template v-if="data">
        <div class="count text-primary font-medium text-2xl">
          {{ data.total_places_available | formatNumber }}
        </div>
        <div class="mt-5">
          <div class="my-1">
            <span class>{{
              data.total_missions_available | formatNumber
            }}</span>
            <span class="text-xs text-gray-500">missions disponibles</span>
          </div>
          <div class="my-1">
            <span class>{{ data.total_places | formatNumber }}</span>
            <span class="text-xs text-gray-500">places offertes</span>
          </div>
          <div class="my-1">
            <span class>{{ data.taux_occupation }}%</span>
            <span class="text-xs text-gray-500"> de taux d'occupation</span>
          </div>
        </div>
      </template>
      <template v-else>
        <i class="el-icon-loading" />
      </template>
    </div>
  </el-card>
</template>

<script>
import { statistics } from '../api/app'

export default {
  name: 'CardPlacesLeftCount',
  data() {
    return {
      data: null,
    }
  },
  created() {
    statistics('places').then((response) => {
      this.data = response.data
    })
  },
  methods: {
    onClick() {
      this.$router.push({
        name: 'DashboardMissions',
        query: { 'filter[state]': 'Validée', 'filter[place]': true },
      })
    },
  },
}
</script>
