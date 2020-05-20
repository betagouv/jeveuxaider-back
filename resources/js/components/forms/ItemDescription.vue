<template>
  <div class="mt-2 mb-6 text-xs leading-snug text-gray-500 flex">
    <i class="el-icon-info text-primary mt-1 mr-2" />
    <div class="flex-1">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  name: 'ItemDescription',
}
</script>
