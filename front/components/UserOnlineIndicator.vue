<template>
  <div>
    <el-tooltip
      v-if="lastOnlineAt"
      :content="`En ligne ${$options.filters.fromNow(lastOnlineAt)}`"
      placement="top"
    >
      <div
        class="w-2 h-2 rounded-full"
        :class="isOnline ? 'bg-green-400' : 'bg-gray-400'"
      ></div>
    </el-tooltip>
    <div
      v-else
      class="w-2 h-2 rounded-full"
      :class="isOnline ? 'bg-green-400' : 'bg-gray-400'"
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
