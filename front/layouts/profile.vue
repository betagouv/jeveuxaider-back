<template>
  <div class="bg-gray-100">
    <AppHeader />

    <div class="bg-primary pb-32">
      <div class="container mx-auto px-4">
        <div class="pt-10">
          <h1 class="text-3xl font-bold text-white">Mon compte</h1>
        </div>
      </div>
    </div>
    <div class="-mt-32">
      <div class="container mx-auto px-4 my-12">
        <div
          class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
        >
          <h2 class="text-3xl leading-tight font-extrabold text-gray-900">
            {{ $store.getters.user.profile.first_name }}
            {{ $store.getters.user.profile.last_name }}
          </h2>

          <div
            class="flex flex-col lg:flex-row mt-8 pt-8 border-t border-gray-200"
          >
            <user-menu class="bg-gray-100 lg:bg-transparent mb-4"></user-menu>
            <div
              class="flex-1 px-1 lg:px-12 pt-2 lg:ml-12 lg:border-l lg:border-gray-200 max-w-2xl"
            >
              <Nuxt />
            </div>
          </div>
        </div>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script>
import UserMenu from '@/components/UserMenu'

export default {
  name: 'ProfileLayout',
  components: { UserMenu },
  middleware: 'logged',
}
</script>
