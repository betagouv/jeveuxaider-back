<template>
  <div class="p-4">
    <div class="flex items-center">
      <Avatar class="mr-4" :source="thumbnail" :fallback="shortName" />

      <div class="flex-1 min-w-0">
        <div class="flex items-center">
          <div :class="[{ 'font-bold': !hasRead }]">{{ name }}</div>
          <div v-if="nametype" class="text-secondary ml-2 text-sm truncate">
            • {{ nametype }}
          </div>
        </div>
        <div
          class="flex justify-between items-baseline text-gray-800"
          :class="[{ 'font-bold': !hasRead }]"
        >
          <span class="truncate text-sm pr-2">{{ message }}</span>
          <span v-if="date" class="flex-none text-secondary text-sm">
            {{ date | formatCustom('D MMM') }}
          </span>
        </div>

        <div
          class="text-sm font-light"
          :class="classParticipationStatus(status)"
        >
          <span v-if="conversableType" class="text-secondary font-normal"
            >{{ conversableType }} :</span
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
    conversableType: {
      type: String,
      default: null,
    },
    nametype: {
      type: String,
      default: null,
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
