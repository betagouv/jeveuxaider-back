<template>
  <div class="px-12">
    <el-card
      v-for="territoire in $store.getters.user.profile.territoires"
      :key="territoire.id"
      class="p-5"
      shadow="never"
    >
      <div class="label mb-3 text-lg font-bold text-secondary uppercase">
        {{ territoire.name }}
      </div>
      <!-- <DropdownTerritoireButton :territoire="territoire" /> -->

      <div class="text-xs text-gray-500">{{ territoire.type }}</div>
      <div class="text-xs text-gray-500">{{ territoire.state }}</div>
      <el-progress :percentage="territoire.completion_rate"></el-progress>
    </el-card>
  </div>
</template>

<script>
export default {}
</script>
