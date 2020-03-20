<template>
  <div>
    <AppHeader />
    <ais-instant-search
        :search-client="searchClient"
        :index-name="indexName"
    >
        <div class="bg-blue-900 pb-32">
            <div class="container mx-auto px-4">
                <div class="pt-10">
                    <h1 class="text-3xl font-bold text-white">Missions disponibles</h1>
                </div>
                <ais-search-box v-if="missionsAreReady" />
            </div>
        </div>

        <div class="-mt-32">
        <div class="container mx-auto px-4 my-12">
            <div
            class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
            >
                Missions en cours de validation...
                <ais-hits v-if="missionsAreReady">
                    <div slot="item" slot-scope="{ item }">
                        <h2>{{ item.name }}</h2>
                    </div>
                </ais-hits>

                <div class="pagination"><ais-pagination v-if="missionsAreReady" /></div>

            </div>
        </div>
        </div>
    </ais-instant-search>
    <AppFooter />
  </div>
</template>

<script>
import { AisInstantSearch, AisSearchBox, AisHits, AisPagination } from 'vue-instantsearch';
import algoliasearch from 'algoliasearch/lite';
import 'instantsearch.css/themes/algolia-min.css'

export default {
    name: "FrontMissions",
    components: {
        AisInstantSearch,
        AisSearchBox,
        AisHits,
        AisPagination
    },
   data() {
    return {
        missionsAreReady: false,
      searchClient: algoliasearch(
        process.env.MIX_ALGOLIA_APP_ID,
        process.env.MIX_ALGOLIA_SEARCH_KEY
      ),
    };
  },
  computed: {
      indexName() {
          return process.env.MIX_ALGOLIA_INDEX
      }
  }
};
</script>
