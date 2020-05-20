<template>
  <div class="member flex">
    <el-avatar class="bg-primary w-10 rounded-full">
      {{ member.short_name }}
    </el-avatar>
    <div class="flex flex-col ml-6" style="min-width: 250px;">
      <router-link
        :to="{
          name: 'Profile',
          params: { id: member.id },
        }"
        target="_blank"
      >
        <div class="text-gray-800">
          {{ member.full_name }}
        </div>
      </router-link>
      <div class="uppercase text-xs text-secondary">
        {{ member.pivot.role }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MemberTeaser',
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
