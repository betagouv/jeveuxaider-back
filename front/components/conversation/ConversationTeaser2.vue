<template>
  <div class="p-4">
    <div class="flex items-center">
      <Avatar
        class="mr-4"
        :source="recipient.profile.image ? recipient.profile.image.thumb : null"
        :fallback="recipient.profile.short_name"
      />

      <div class="flex-1 min-w-0">
        <div class="flex items-center">
          <div :class="[{ 'font-bold': !hasRead }]">
            {{ recipient.profile.first_name }}
          </div>

          <div v-if="nametype" class="text-secondary ml-2 text-sm truncate">
            • {{ nametype }}
          </div>
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
    hasRead() {
      // if (this.$store.getters.contextRole == 'admin') {
      //   return true
      // }
      // if (
      //   this.$store.getters[
      //     'conversation/clickedUnreadConversationsIds'
      //   ].includes(this.conversation.id)
      // ) {
      //   return true
      // }

      // if (this.conversation.latest_message) {
      //   if (!this.currentUser.pivot.read_at) {
      //     return false
      //   }

      //   return !(
      //     this.$dayjs(this.conversation.updated_at).unix() >
      //     this.$dayjs(this.currentUser.pivot.read_at).unix()
      //   )
      // }
      return false
    },
    currentUser() {
      return this.conversation.users.filter((user) => {
        return user.id == this.$store.getters.user.id
      })[0]
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
