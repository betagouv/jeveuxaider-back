<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">Ressources</div>
      </div>
    </div>

    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <query-main-search-filter
          name="search"
          placeholder="Rechercher par mots clés..."
          :initial-value="query['filter[search]']"
          @changed="onFilterChange"
        />
      </div>
    </div>
    <el-table
      v-loading="loading"
      :data="tableData"
      :highlight-current-row="true"
    >
      <el-table-column label="" width="70">
        <template slot-scope="scope">
          <el-avatar class="bg-primary flex items-center justify-center">
            <font-awesome-icon
              size="lg"
              :icon="scope.row.file.mime_type | icoFromMimeType"
            />
          </el-avatar>
        </template>
      </el-table-column>
      <el-table-column label="Ressource" min-width="320">
        <template slot-scope="scope">
          <div class="flex items-center">
            <div class="mr-8 flex-1">
              <div>{{ scope.row.title }}</div>
              <div class="text-sm text-gray-600">
                {{ scope.row.file.size | fileSizeOctets }}
              </div>
            </div>
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
      <el-table-column label="Actions" width="205">
        <template slot-scope="scope">
          <el-button
            type="secondary"
            icon="el-icon-download"
            @click.prevent="onDownloadFile(scope.row.file)"
            >Télécharger</el-button
          >
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
      ></el-pagination>
      <div class="text-secondary text-xs ml-3">
        Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
      </div>
    </div>
  </div>
</template>

<script>
import { fetchDocuments } from '@/api/app'
import QueryMainSearchFilter from '@/components/QueryMainSearchFilter.vue'
import TableWithFilters from '@/mixins/TableWithFilters'

export default {
  name: 'Ressources',
  components: {
    QueryMainSearchFilter,
  },
  mixins: [TableWithFilters],
  data() {
    return {
      loading: true,
    }
  },
  beforeRouteUpdate(to, from, next) {
    this.query = { ...to.query }
    this.fetchDatas()
    next()
  },
  methods: {
    fetchRows() {
      return fetchDocuments(this.query)
    },
    onDownloadFile(file) {
      window.open(file.url, '_blank')
    },
  },
}
</script>
