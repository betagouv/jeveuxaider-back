<template>
  <div>
    <AppHeader />

    <template v-if="!loading">
      <div class="bg-blue-900 pb-32">
        <div class="container mx-auto px-4">
          <div class="pt-10">
            <h1 class="text-3xl font-bold text-white">
              {{ page.title }}
            </h1>
          </div>
        </div>
      </div>

      <div class="-mt-32">
        <div class="container mx-auto px-4 my-12">
          <div
            class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16 text-gray-700 items-center"
          >
            <div
              style="max-width: 680px;"
              class="mx-auto wysiwyg-field"
              v-html="page.description"
            />
          </div>
        </div>
      </div>
    </template>
    <template v-else>
      <front-page-loading />
    </template>
    <AppFooter />
  </div>
</template>

<script>
import { getPage } from '@/api/app'
import FrontPageLoading from '@/components/loadings/FrontPageLoading'

export default {
  name: 'FrontPage',
  components: {
    FrontPageLoading,
  },
  props: {
    id: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      loading: true,
      page: null,
    }
  },
  created() {
    getPage(this.id)
      .then((response) => {
        this.page = response.data
        this.loading = false
      })
      .catch(() => {
        this.loading = false
      })
  },
}
</script>
