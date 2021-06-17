<template>
  <div class="p-4">
    <div class="flex items-center">
      <Avatar
        class="mr-4"
        :source="recipient.profile.image ? recipient.profile.image.thumb : null"
        :fallback="recipient.profile.short_name"
      />

      <div class="flex-1 min-w-0">
        <div class="flex items-center space-x-2">
          <div :class="[{ 'font-bold unread': !hasRead }]">
            {{ recipient.profile.first_name }}
          </div>

          <div v-if="nametype" class="text-secondary text-sm truncate">
            • {{ nametype }}
          </div>
          <span
            v-if="!hasRead"
            class="w-2.5 h-2.5 mr-4 bg-red-500 rounded-full"
            aria-hidden="true"
          ></span>
        </div>

        <div
          v-if="conversation.latest_message"
          class="flex justify-between items-baseline text-gray-800"
          :class="[{ 'font-bold': !hasRead }]"
        >
          <span
            v-if="conversation.latest_message"
            class="truncate text-sm pr-2"
          >
            {{ conversation.latest_message.content }}
          </span>

          <span class="flex-none text-secondary text-sm">
            {{ conversation.latest_message.created_at | formatCustom('D MMM') }}
          </span>
        </div>

        <div
          v-if="conversation.conversable"
          class="text-sm font-light"
          :class="classParticipationStatus(conversation.conversable.state)"
        >
          <span class="text-secondary font-normal"> Participation : </span>
          <span>
            {{ conversation.conversable.state }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    conversation: {
      type: Object,
      required: true,
    },
  },
  computed: {
    recipient() {
      return this.conversation.users.filter((user) => {
        return user.id != this.$store.getters.user.id
      })[0]
    },
    currentUser() {
      return this.conversation.users.filter((user) => {
        return user.id == this.$store.getters.user.id
      })[0]
    },
    hasRead() {
      return !this.$store.getters.user.unreadConversations.includes(
        this.conversation.id
      )
    },
    nametype() {
      return this.$store.getters.contextRole == 'volontaire'
        ? this.conversation.conversable.mission.structure.name
        : null
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
