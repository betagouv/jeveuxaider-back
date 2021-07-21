<template>
  <div>
    <div class="flex justify-between items-end mb-1">
      <div class="flex space-x-2 justify-center">
        <!-- <div
          v-if="icon"
          class="flex-shrink-0 h-5 w-5 text-gray-900"
          v-html="icon"
        /> -->
        <div class="text-lg font-medium">{{ label }}</div>
      </div>
      <router-link
        v-if="link"
        :to="link"
        class="text-xs text-primary hover:text-primary"
      >
        {{ linkLabel }}
      </router-link>
    </div>
    <el-card shadow="never" class="overflow-visible">
      <div class="flex flex-col space-y-2 text-xs text-gray-500">
        <slot></slot>
      </div>
    </el-card>
  </div>
</template>

<script>
export default {
  props: {
    label: {
      type: String,
      default: null,
      required: false,
    },
    link: {
      type: String,
      default: null,
      required: false,
    },
    linkLabel: {
      type: String,
      default: 'Accéder à la fiche',
      required: false,
    },
    icon: {
      type: String,
      default: null,
      required: false,
    },
  },
}
</script>

<style></style>
