<template>
  <div class="has-full-table">
    <div
      class="header px-12 flex"
      :class="{ 'mb-8': $store.getters.contextRole == 'responsable' }"
    >
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Organisation</div>
        <div class="flex items-center flex-wrap">
          <div class="font-bold text-2-5xl text-gray-800 mr-2">
            {{ structure.name }}
          </div>
          <TagModelState v-if="structure.state" :state="structure.state" />
          <el-tag
            v-if="structure.is_reseau"
            size="medium"
            class="m-1 ml-0"
            type="danger"
          >
            Tête de réseau
          </el-tag>
          <el-tag v-if="structure.reseau_id" class="m-1 ml-0" size="medium">
            {{ structure.reseau_id | reseauFromValue }}
          </el-tag>
        </div>
        <div
          v-if="structure.statut_juridique == 'Association'"
          class="font-light text-gray-600 flex items-center"
        >
          <div
            :class="
              structure.state == 'Validée' ? 'bg-green-500' : 'bg-red-500'
            "
            class="rounded-full h-2 w-2 mr-2 flex-none"
          ></div>
          <nuxt-link
            v-if="structure.state == 'Validée'"
            :to="structure.full_url"
            target="_blank"
            class="underline hover:no-underline"
          >
            {{ $config.appUrl }}{{ structure.full_url }}
          </nuxt-link>
          <span v-else class="cursor-default">
            {{ $config.appUrl }}{{ structure.full_url }}
          </span>
        </div>
      </div>
      <div>
        <DropdownStructureButton
          v-if="$store.getters.contextRole == 'admin'"
          :structure="structure"
        />
      </div>
    </div>
    <NavTabStructure
      v-if="$store.getters.contextRole != 'responsable'"
      :structure="structure"
    />
    <TableActivities :table-data="tableData" />
    <div class="m-3 flex items-center">
      <el-pagination
        background
        layout="prev, pager, next"
        :total="totalRows"
        :page-size="15"
        :current-page="Number(query.page)"
        @current-change="onPageChange"
      />
      <div class="text-secondary text-xs ml-3">
        Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
      </div>
    </div>
  </div>
</template>

<script>
import TableWithFilters from '@/mixins/table-with-filters'

export default {
  mixins: [TableWithFilters],
  layout: 'dashboard',
  async asyncData({ $api, params }) {
    const structure = await $api.getStructure(params.id)
    return {
      structure,
    }
  },
  async fetch() {
    const { data } = await this.$api.fetchActivities({
      'filter[subject_id]': this.$route.params.id,
      'filter[subject_type]': 'Structure',
      page: this.$route.query.page || 1,
    })
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  watch: {
    '$route.query': '$fetch',
  },
  methods: {},
}
</script>

<style scoped lang="sass">
.el-menu--horizontal
  @apply px-12
  > .el-menu-item
    @apply mr-8 p-0 font-medium
      border-bottom: solid 3px #070191
</style>
