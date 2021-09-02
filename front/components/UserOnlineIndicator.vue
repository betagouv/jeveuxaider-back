<template>
  <div>
    <div
      v-tooltip="{
        content: lastOnlineAt
          ? `En ligne ${$options.filters.fromNow(lastOnlineAt)}`
          : null,
        classes: 'bo-style',
      }"
      class="w-2 h-2 rounded-full"
      :class="isOnline ? 'bg-[#16a972]' : 'bg-gray-400'"
    ></div>
  </div>
</template>

<script>
import dayjs from 'dayjs'

export default {
  name: 'UserOnlineIndicator',
  props: {
    lastOnlineAt: {
      type: String,
      default: null,
    },
  },
  data() {
    return {}
  },
  computed: {
    isOnline() {
      const now = Date.now() / 1000
      const date = dayjs(this.lastOnlineAt).unix()
      return !!(this.lastOnlineAt && now - date < 5 * 60)
    },
  },
}
</script>
