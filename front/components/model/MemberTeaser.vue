<template>
  <div class="member flex items-center overflow-hidden">
    <Avatar
      :source="member.image ? member.image.thumb : null"
      :fallback="member.short_name"
      width="w-12 h-12"
    />

    <div class="flex flex-col ml-6" style="">
      <nuxt-link
        v-if="$store.getters.contextRole === 'admin'"
        :to="`/dashboard/profile/${member.id}`"
        target="_blank"
      >
        <div class="text-sm text-gray-700">
          {{ member.full_name }}
        </div>
      </nuxt-link>
      <div v-else class="text-sm text-gray-700">
        {{ member.full_name }}
      </div>

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
