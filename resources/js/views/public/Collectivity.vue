<template>
  <div>
    <AppHeader :show-menu="false">
      <template v-slot:append-logo>
        <div
          v-if="collectivity"
          class="hidden sm:block ml-2 mr-auto w-auto order-2"
        >
          <div
            class="text-xl md:text-xl font-medium text-gray-500 leading-none"
          >
            • {{ collectivity.name }}
          </div>
        </div>
      </template>
    </AppHeader>

    <template v-if="!$store.getters.loading">
      <template v-if="collectivity.type == 'department'">
        <breadcrumb
          :items="[
            { label: 'Départements', link: '/territoires' },
            { label: collectivity.name },
          ]"
        />
        <collectivity-department
          :collectivity="collectivity"
        ></collectivity-department>
      </template>

      <template v-if="collectivity.type == 'commune'">
        <breadcrumb
          :items="[
            { label: 'Collectivités', link: '/territoires' },
            { label: collectivity.name },
          ]"
        />
        <collectivity-commune
          :collectivity="collectivity"
        ></collectivity-commune>
      </template>
    </template>
  </div>
</template>

<script>
import { getCollectivity } from '@/api/app'
import CollectivityDepartment from '@/views/public/CollectivityDepartment'
import CollectivityCommune from '@/views/public/CollectivityCommune'

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
        if (!this.collectivity.published) {
          this.$message({
            message: "Cette collectivité n'est pas encore accessible !",
            type: 'warning',
          })
          this.$router.push('/403')
        }
        this.$store.commit('setLoading', false)
      })
      .catch(() => {})
  },
  methods: {},
}
</script>
