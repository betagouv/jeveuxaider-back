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
          :class="[{ 'font-bold': isNew }, { 'font-light': !isNew }]"
        >
          <span class="truncate text-sm pr-2">{{ message }}</span>
          <span v-if="date" class="flex-none text-gray-500 text-xs">
            {{ date | formatCustom('D MMM') }}
          </span>
        </div>

        <div class="text-gray-500 text-sm font-light">{{ status }}</div>
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
      default: null,
    },
    thumbnail: {
      type: String,
      default: undefined,
    },
    date: {
      type: String,
      default: null,
    },
    status: {
      type: String,
      required: true,
    },
    isNew: {
      type: Boolean,
      required: true,
    },
  },
}
</script>

<style lang="sass" scoped></style>
