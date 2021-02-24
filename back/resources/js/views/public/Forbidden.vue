<template>
  <div class="min-h-screen bg-white flex">
    <div
      class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24"
    >
      <div>
        <h2 class="mt-8 text-3xl leading-tight font-extrabold text-gray-900">
          Page
          <span class="text-blue-800">inaccessible</span>
        </h2>
      </div>
      <div class="mt-8 border-t border-gray-200 pt-8" />
      <div class>
        <p>La page que vous souhaitez afficher n'est pas accessible.</p>
      </div>
    </div>
    <div class="hidden lg:block relative w-0 flex-1">
      <img
        class="absolute inset-0 h-full w-full object-cover"
        src="/images/bg_header_home.jpg"
        alt
      />
    </div>
  </div>
</template>

<script>
export default {
  name: 'Forbidden',
}
</script>
