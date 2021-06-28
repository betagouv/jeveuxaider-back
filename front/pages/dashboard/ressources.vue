<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-8 font-bold text-2-5xl text-gray-800">Ressources</div>
      </div>
    </div>

    <div class="flex flex-wrap px-12 mb-3">
      <SearchFiltersQueryMain
        name="search"
        placeholder="Rechercher par mots clés"
        :initial-value="query['filter[search]']"
        @changed="onFilterChange"
      />
    </div>

    <el-table
      v-loading="$fetchState.pending"
      :data="tableData"
      :highlight-current-row="true"
    >
      <el-table-column label="" width="90" align="center">
        <template slot-scope="scope">
          <img
            v-if="scope.row.type == 'file'"
            :src="
              require(`@/assets/images/dynamic/${$options.filters.icoFromMimeType(
                scope.row.file.mime_type
              )}.svg`)
            "
            alt="File"
            class="h-10 w-auto mx-auto"
          />
          <div v-else class="flex items-center justify-center">
            <div
              class="w-10"
              v-html="
                require('@/assets/images/icones/heroicon/link.svg?include')
              "
            ></div>
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Ressource" min-width="320">
        <template slot-scope="scope">
          <div class="flex items-center">
            <div class="mr-8 flex-1">
              <div>{{ scope.row.title }}</div>
              <div class="text-sm text-gray-600">
                <template v-if="scope.row.type == 'file'">
                  {{ scope.row.file.size | fileSizeOctets }}
                </template>
                <template v-else>Lien externe</template>
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
          <div class="text-sm text-gray-600">
            <template v-if="scope.row.type == 'file'">
              <el-button
                type="secondary"
                icon="el-icon-download"
                @click.prevent="onDownloadFile(scope.row.file)"
                >Télécharger</el-button
              >
            </template>
            <template v-else>
              <el-button
                type="secondary"
                icon="el-icon-link"
                @click.prevent="onClickLink(scope.row)"
                >Ouvrir le lien</el-button
              ></template
            >
          </div>
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
  asyncData({ store, error }) {
    if (!['referent', 'responsable'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
  },
  async fetch() {
    const { data } = await this.$api.fetchDocuments(this.query)
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  watch: {
    '$route.query': '$fetch',
  },
  methods: {
    onDownloadFile(file) {
      window.open(file.url, '_blank')
    },
    onClickLink(document) {
      window.open(document.link, '_blank')
    },
  },
}
</script>
