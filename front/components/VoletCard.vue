<template>
  <div>
    <div class="flex justify-between items-end mb-1">
      <div class="font-semibold">{{ label }}</div>
      <router-link v-if="link" :to="link" class="text-xs hover:text-primary">
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
      required: true,
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
  },
}
</script>

<style></style>
