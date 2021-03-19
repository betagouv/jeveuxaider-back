<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">
          Contenus - Pages
        </div>
      </div>
      <div class>
        <nuxt-link :to="`/dashboard/contents/page/add`">
          <el-button type="primary"> Ajouter une page </el-button>
        </nuxt-link>
      </div>
    </div>
    <div class="px-12 mb-12">
      <TabsContents index="/dashboard/contents/pages" />
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <SearchFiltersQueryMain
          name="search"
          placeholder="Rechercher par mots clés..."
          :initial-value="query['filter[search]']"
          @changed="onFilterChange"
        />
      </div>
    </div>
    <el-table
      v-loading="$fetchState.pending"
      :data="tableData"
      :highlight-current-row="true"
    >
      <el-table-column label="#" min-width="70" align="center">
        <template slot-scope="scope">
          <div>{{ scope.row.id }}</div>
        </template>
      </el-table-column>
      <el-table-column label="Question" min-width="320">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.title }}
          </div>
        </template>
      </el-table-column>
      <el-table-column prop="updated_at" label="Modifiée le" min-width="120">
        <template slot-scope="scope">
          <div class="text-sm text-gray-600">
            {{ scope.row.updated_at | fromNow }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Actions" width="165">
        <template slot-scope="scope">
          <el-dropdown
            size="small"
            split-button
            trigger="click"
            class="flex"
            @click="handleClickEdit(scope.row.id)"
            @command="handleCommand"
          >
            <i class="el-icon-edit mr-2" />Modifier
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item
                :command="{ action: 'delete', id: scope.row.id }"
              >
                Supprimer
              </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
        </template>
      </el-table-column>
    </el-table>
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
  asyncData({ $api, params, store, error }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
  },
  data() {
    return {}
  },
  async fetch() {
    this.query = this.$route.query
    const { data } = await this.$api.fetchPages(this.query)
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  computed: {},
  watch: {
    '$route.query': '$fetch',
  },
  methods: {
    handleCommand(command) {
      if (command.action == 'delete') {
        this.handleClickDelete(command.id)
      } else {
        this.$router.push(command)
      }
    },
    handleClickEdit(id) {
      this.$router.push(`/dashboard/contents/page/${id}/edit`)
    },
    handleClickDelete(id) {
      this.$confirm(
        `Êtes vous sur de vouloir supprimer cette page ?`,
        'Supprimer cette page',
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          type: 'error',
        }
      ).then(() => {
        this.$api.deletePage(id).then(() => {
          this.$message({
            type: 'success',
            message: `La page a été supprimée.`,
          })
          this.$fetch()
        })
      })
    },
  },
}
</script>
