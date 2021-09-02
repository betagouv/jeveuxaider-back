<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-8 font-bold text-[1.75rem] text-[#242526]">
          Contenus - Domaines
        </div>
      </div>
      <div class>
        <nuxt-link :to="`/dashboard/contents/domaine/add`">
          <el-button type="primary"> Ajouter un domaine </el-button>
        </nuxt-link>
      </div>
    </div>
    <div class="px-12 mb-12">
      <TabsContents index="/dashboard/contents/domaines" />
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
      <el-table-column label="Domaine d'action" min-width="320">
        <template slot-scope="scope">
          <div class="text-gray-900">{{ scope.row.name }}</div>
          <div class="font-light text-gray-600 text-xs">
            <nuxt-link
              :to="`/domaines-action/${scope.row.slug}`"
              target="_blank"
              >/domaines-action/{{ scope.row.slug }}</nuxt-link
            >
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Contexte" min-width="320">
        <template slot-scope="scope">
          <el-tag
            v-if="scope.row.published"
            type="success"
            class="m-1 ml-0"
            size="small"
            >En ligne</el-tag
          >
          <el-tag
            v-if="!scope.row.published"
            type="info"
            class="m-1 ml-0"
            size="small"
            >Non publié</el-tag
          >
        </template>
      </el-table-column>
      <el-table-column prop="updated_at" label="Modifiée le" min-width="120">
        <template slot-scope="scope">
          <div class="text-sm text-gray-600">
            {{ scope.row.updated_at | fromNow }}
          </div>
        </template>
      </el-table-column>
      <el-table-column
        label="Actions"
        width="165"
        class-name="dropdown-wrapper"
      >
        <template slot-scope="scope">
          <el-dropdown
            size="small"
            split-button
            trigger="click"
            @click="handleClickEdit(scope.row.id)"
            @command="handleCommand"
          >
            <i class="el-icon-edit mr-2"></i>Modifier
            <el-dropdown-menu slot="dropdown">
              <el-dropdown-item
                :command="{ action: 'delete', id: scope.row.id }"
                >Supprimer</el-dropdown-item
              >
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
    const { data } = await this.$api.fetchThematiques(this.query)
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
      this.$router.push(`/dashboard/contents/domaine/${id}/edit`)
    },
    handleClickDelete(id) {
      this.$confirm(
        `Êtes vous sur de vouloir supprimer ce domaine ?`,
        'Supprimer ce domaine',
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          type: 'error',
        }
      ).then(() => {
        this.$api.deleteThematique(id).then(() => {
          this.$message({
            type: 'success',
            message: `Le domaine a été supprimé.`,
          })
          this.$fetch()
        })
      })
    },
  },
}
</script>
