<template>
  <div class="-mt-32 mb-12">
    <div class="container mx-auto px-4 mt-12">
      <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="lg:flex">
          <div class="flex-grow px-6 py-8 lg:flex-shrink-1 lg:p-12">
            <div class="w-full overflow-hidden" style="max-width: 765px;">
              <content-loader
                width="765"
                height="400"
                primary-color="#edf2f7"
                secondary-color="#edf2f7"
                :animate="false"
                class="object-cover"
                style="height: 400px;"
              >
                <rect x="0" y="0" rx="3" ry="3" width="765" height="25" />
                <rect x="0" y="50" rx="3" ry="3" width="450" height="25" />
                <circle cx="24" cy="150" r="24" />
                <rect x="60" y="130" rx="3" ry="3" width="130" height="10" />
                <rect x="60" y="160" rx="3" ry="3" width="130" height="10" />
                <rect x="0" y="230" rx="3" ry="3" width="130" height="15" />
                <rect x="150" y="230" rx="3" ry="3" width="130" height="15" />
                <rect x="0" y="300" rx="3" ry="3" width="765" height="3" />
                <rect x="0" y="350" rx="3" ry="3" width="600" height="10" />
                <rect x="0" y="370" rx="3" ry="3" width="450" height="10" />
              </content-loader>
            </div>
          </div>

          <div class="aside text-center bg-gray-100 lg:flex-shrink-0">
            <div class="py-8 px-6 lg:p-12">
              <div class="m-auto" style="width: 260px;">
                <content-loader
                  width="290"
                  height="350"
                  primary-color="#edf2f7"
                  secondary-color="#edf2f7"
                  :animate="false"
                >
                  <rect x="45" y="0" rx="3" ry="3" width="200" height="25" />
                  <rect x="20" y="50" rx="3" ry="3" width="250" height="25" />
                  <rect x="45" y="120" rx="3" ry="3" width="200" height="15" />
                  <rect x="50" y="150" rx="3" ry="3" width="190" height="15" />
                  <rect x="50" y="180" rx="3" ry="3" width="190" height="15" />
                  <rect x="0" y="240" rx="3" ry="3" width="290" height="15" />
                  <rect x="0" y="290" rx="3" ry="3" width="290" height="30" />
                </content-loader>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ContentLoader } from 'vue-content-loader'

export default {
  name: 'FrontMissionLoading',
  components: {
    ContentLoader,
  },
}
</script>
