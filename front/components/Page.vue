<template>
  <div>
    <div class="bg-primary pb-32">
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
            style="max-width: 680px"
            class="mx-auto wysiwyg-field"
            v-html="page.description"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    page: {
      type: Object,
      required: true,
    },
  },
}
</script>
