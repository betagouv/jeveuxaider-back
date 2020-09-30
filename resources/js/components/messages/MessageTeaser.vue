<template>
  <div class="p-4">
    <div class="flex items-center">
      <el-avatar :src="thumbnail" class="flex-none bg-primary text-white mr-4">
        {{ shortName }}
      </el-avatar>
      <div class="flex-1 min-w-0">
        <div :class="[{ 'font-bold': isNew }]">{{ name }}</div>

        <div
          class="flex justify-between items-baseline"
          :class="[{ 'font-bold': isNew }]"
        >
          <span class="truncate text-sm pr-2">{{ message }}</span>
          <span class="flex-none text-gray-500 text-xs">
            {{ (date * 1000) | formatCustom('D MMM') }}
          </span>
        </div>

        <div class="text-gray-500 text-sm">{{ formatStatus }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MessageTeaser',
  props: {
    name: {
      type: String,
      required: true,
    },
    shortName: {
      type: String,
      required: true,
    },
    message: {
      type: String,
      required: true,
    },
    thumbnail: {
      type: String,
      default: undefined,
    },
    date: {
      type: Number,
      required: true,
    },
    status: {
      type: Boolean,
      required: true,
    },
    isNew: {
      type: Boolean,
      required: true,
    },
  },
  computed: {
    formatStatus() {
      return this.status ? 'En cours' : 'Archivé'
    },
  },
}
</script>

<style lang="sass" scoped></style>
