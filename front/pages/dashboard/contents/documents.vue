<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">
          Contenus - Documents
        </div>
      </div>
      <div class>
        <nuxt-link :to="`/dashboard/contents/document/add`">
          <el-button type="primary"> Ajouter un document </el-button>
        </nuxt-link>
      </div>
    </div>
    <div class="px-12 mb-12">
      <TabsContents index="/dashboard/contents/documents" />
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
    const { data } = await this.$api.fetchDocuments(this.query)
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
      this.$router.push(`/dashboard/contents/document/${id}/edit`)
    },
    handleClickDelete(id) {
      this.$confirm(
        `Êtes vous sur de vouloir supprimer ce document ?`,
        'Supprimer ce document',
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          type: 'error',
        }
      ).then(() => {
        this.$api.deleteDocument(id).then(() => {
          this.$message({
            type: 'success',
            message: `Le document a été supprimé.`,
          })
          this.$fetch()
        })
      })
    },
  },
}
</script>
