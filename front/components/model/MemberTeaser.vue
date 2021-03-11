<template>
  <div class="member flex items-center overflow-hidden">
    <el-avatar class="bg-primary w-10 rounded-full flex-none">
      {{ member.short_name }}
    </el-avatar>
    <div class="flex flex-col ml-6" style="">
      <router-link :to="`/dashboard/profile/${member.id}`" target="_blank">
        <div class="text-sm text-gray-700">
          {{ member.full_name }}
        </div>
      </router-link>
      <div class="text-xs text-secondary">
        <div class="break-all">{{ member.email }}</div>
        <div class="">{{ member.mobile }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    member: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {}
  },
  methods: {},
}
</script>
