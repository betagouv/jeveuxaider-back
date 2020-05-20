<template>
  <div>
    <div class="bg-blue-900 pb-32">
      <div class="container mx-auto px-4">
        <div class="pt-14 overflow-hidden" style="height: 85px;">
          <content-loader
            height="10"
            primary-color="#1e429f"
            secondary-color="#1e429f"
            :animate="false"
            class="object-cover"
            style="height: 10px;"
          >
            <rect x="0" y="0" rx="3" ry="3" width="200" height="10" />
          </content-loader>
        </div>
      </div>
    </div>
    <div class="-mt-32">
      <div class="container mx-auto px-4 my-12">
        <div
          class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16 text-gray-700 items-center"
        >
          <div style="max-width: 680px;" class="mx-auto overflow-hidden">
            <content-loader
              width="680"
              height="350"
              primary-color="#edf2f7"
              secondary-color="#edf2f7"
              :animate="false"
              class="object-cover"
              style="height: 350px;"
            >
              <rect x="0" y="0" rx="3" ry="3" width="680" height="15" />
              <rect x="0" y="30" rx="3" ry="3" width="680" height="15" />
              <rect x="0" y="60" rx="3" ry="3" width="400" height="15" />
              <rect x="0" y="120" rx="3" ry="3" width="680" height="15" />
              <rect x="0" y="150" rx="3" ry="3" width="680" height="15" />
              <rect x="0" y="180" rx="3" ry="3" width="400" height="15" />
              <rect x="0" y="240" rx="3" ry="3" width="680" height="15" />
              <rect x="0" y="270" rx="3" ry="3" width="680" height="15" />
              <rect x="0" y="300" rx="3" ry="3" width="400" height="15" />
            </content-loader>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ContentLoader } from 'vue-content-loader'

export default {
  name: 'FrontPageLoading',
  components: {
    ContentLoader,
  },
}
</script>
