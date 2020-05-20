<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters["user/contextRoleLabel"] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">
          Ressources
        </div>
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
    <div class="px-12 flex flex-wrap">
      <div
        v-for="document in tableData"
        :key="document.id"
        class
      >
        <el-card
          shadow="never"
          class="mr-5 mb-5 p-5 hover:border-blue-900 cursor-pointer"
          style="width: 400px"
        >
          <div class="flex items-center">
            <div
              class="mr-4 rounded-full bg-gray-200 text-gray-700 flex items-center justify-center"
              style="width: 50px; height: 50px"
            >
              <font-awesome-icon
                size="lg"
                :icon="document.file.mime_type|icoFromMimeType"
              />
            </div>
            <div class="mr-8 flex-1">
              <div>{{ document.title }}</div>
              <div class="text-sm text-gray-600">
                {{ document.file.size|fileSizeOctets }}
              </div>
            </div>
            <div>
              <el-button
                type="secondary"
                icon="el-icon-download"
                @click.prevent="onDownloadFile(document.file)"
              />
            </div>
          </div>
        </el-card>
      </div>
    </div>
    <div
      v-if="totalRows"
      class="px-12 my-3 flex items-center"
    >
      <el-pagination
        background
        layout="prev, pager, next"
        :total="totalRows"
        :page-size="15"
        :current-page="Number(query.page)"
        @current-change="onPageChange"
      />
      <div
        class="text-secondary text-xs ml-3"
      >
        Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
      </div>
    </div>
  </div>
</template>

<script>
import { fetchDocuments } from "@/api/app";
import QueryMainSearchFilter from "@/components/QueryMainSearchFilter.vue";
import TableWithFilters from "@/mixins/TableWithFilters";

export default {
  name: "Ressources",
  components: {
    QueryMainSearchFilter
  },
  mixins: [TableWithFilters],
  data() {
    return {
      loading: true
    };
  },
  beforeRouteUpdate(to, from, next) {
    this.query = { ...to.query };
    this.fetchDatas();
    next();
  },
  methods: {
    fetchRows() {
      return fetchDocuments(this.query);
    },
    onDownloadFile(file) {
      window.open(file.url, "_blank");
    }
  }
};
</script>
