<template>
  <div class="p-4">
    <div class="flex items-center">
      <el-avatar :src="thumbnail" class="flex-none bg-primary text-white mr-4">
        {{ shortName }}
      </el-avatar>
      <div class="flex-1 min-w-0">
        <div :class="[{ 'font-bold': !hasRead }]">{{ name }}</div>

        <div
          class="flex justify-between items-baseline"
          :class="[{ 'font-bold': !hasRead }, { 'font-light': hasRead }]"
        >
          <span class="truncate text-sm pr-2">{{ message }}</span>
          <span v-if="date" class="flex-none text-gray-500 text-xs">
            {{ date | formatCustom('D MMM') }}
          </span>
        </div>

        <div
          class="text-sm font-light"
          :class="classParticipationStatus(status)"
        >
          {{ status }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ConversationTeaser',
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
    hasRead: {
      type: Boolean,
      required: true,
    },
  },
  methods: {
    classParticipationStatus(status) {
      switch (status) {
        case 'En attente de validation':
          return 'text-orange-400 font-semibold'
        case 'Validée':
          return 'text-green-800 font-semibold'
        case 'Effectuée':
          return 'text-green-600 font-semibold'
        default:
          return 'text-gray-500'
      }
    },
  },
}
</script>

<style lang="sass" scoped></style>
