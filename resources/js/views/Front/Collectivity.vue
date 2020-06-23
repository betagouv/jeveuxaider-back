<template>
  <div>
    <AppHeader :show-menu="false">
      <template v-slot:menu>
        <div class="hidden sm:block ml-2 mr-auto w-auto order-2">
          <div
            class="text-xl md:text-2xl font-medium text-gray-500 leading-none"
          >
            • {{ collectivity.title }}
          </div>
        </div>
      </template>
    </AppHeader>

    <template v-if="collectivity.type == 'department'">
      <collectivity-department
        :collectivity="collectivity"
      ></collectivity-department>
    </template>

    <template v-if="collectivity.type == 'commune'">
      <collectivity-commune :collectivity="collectivity"></collectivity-commune>
    </template>

    <AppFooter />
  </div>
</template>

<script>
import { getCollectivity } from '@/api/app'
import CollectivityDepartment from '@/views/Front/CollectivityDepartment'
import CollectivityCommune from '@/views/Front/CollectivityCommune'

export default {
  name: 'FrontCollectivity',
  components: {
    CollectivityDepartment,
    CollectivityCommune,
  },
  props: {
    slug: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      collectivity: {},
    }
  },
  created() {
    this.$store.commit('setLoading', true)
    getCollectivity(this.slug)
      .then((response) => {
        this.collectivity = { ...response.data }
      })
      .catch(() => {})
  },
  methods: {},
}
</script>
