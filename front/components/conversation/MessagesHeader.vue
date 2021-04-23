<template>
  <div
    class="min-w-0 flex flex-wrap sm:flex-no-wrap flex-1 justify-between items-center"
  >
    <button
      class="order-1 md:hidden text-xs flex-none rounded-full px-3 py-1 mr-2 my-4 sm:my-0 border hover:border-black transition focus:outline-none focus:border-black"
      @click="onPanelLeftToggle"
    >
      Retour
    </button>

    <div class="order-4 w-full sm:w-auto sm:order-2 mb-4 sm:mb-0 sm:truncate">
      <h1 class="text-lg leading-8 font-bold text-gray-900 sm:truncate">
        {{ recipient.profile.first_name }} {{ recipient.profile.last_name }}
      </h1>

      <div class="text-sm text-gray-500 font-light sm:truncate">
        {{ conversation.conversable.mission.city }}

        <span v-if="conversation.conversable.mission.start_date">
          · {{ formattedDate }}
        </span>
      </div>
    </div>

    <div class="order-3 ml-2 flex items-center my-4 sm:my-0">
      <button
        v-if="$store.getters.contextRole != 'admin'"
        v-tooltip="{
          content:
            currentUser.pivot.status == 0
              ? `Retirer la conversation des archives`
              : `Archiver la conversation`,
          hideOnTargetClick: true,
          placement: 'bottom',
        }"
        class="flex-none rounded-full transition whitespace-no-wrap mr-2 w-7 h-7 flex items-center justify-center focus:outline-none focus:bg-gray-200 hover:bg-gray-200"
        @click="onArchiveClick"
      >
        <img
          src="@/assets/images/archive.svg"
          :alt="
            currentUser.pivot.status == 0 ? `Retirer de l'archive` : `Archiver`
          "
          width="16"
        />
      </button>

      <button
        class="text-xs flex-none rounded-full px-3 py-1 border whitespace-no-wrap hover:border-black transition focus:outline-none focus:border-black"
        @click="$emit('toggle-panel-right')"
        v-html="
          $store.getters['messaging/showPanelRight']
            ? 'Masquer les détails'
            : 'Voir les détails'
        "
      />
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
  data() {
    return {}
  },
  computed: {
    formattedDate() {
      let start = this.conversation.conversable.mission.start_date
      let end = this.conversation.conversable.mission.end_date

      start = this.$options.filters.formatCustom(start, 'D MMM YYYY')
      end = this.$options.filters.formatCustom(end, 'D MMM YYYY')

      if (start == end) {
        return start
      }

      return `${start} - ${end}`
    },
    recipient() {
      return this.conversation.users.filter((user) => {
        return user.id != this.$store.getters.user.id
      })[0]
    },
    currentUser() {
      return this.conversation.users.find((user) => {
        return user.id == this.$store.getters.user.id
      })
    },
  },
  methods: {
    onPanelLeftToggle() {
      this.$store.commit('messaging/setShowPanelCenter', false)
      this.$store.commit('messaging/setShowPanelLeft', true)
    },
    onArchiveClick() {
      // Update status
      this.$api.setConversationStatus(this.conversation.id, {
        status: this.currentUser.pivot.status == 0,
      })

      // Change current conversation
      const key = this.$store.getters['messaging/conversations'].findIndex(
        (conversation) => {
          return conversation.id == this.conversation.id
        }
      )
      if (this.$store.getters['messaging/conversations'][key + 1]) {
        this.$router.push(
          `/messages/${
            this.$store.getters['messaging/conversations'][key + 1].id
          }`
        )
      } else if (this.$store.getters['messaging/conversations'][key - 1]) {
        this.$router.push(
          `/messages/${
            this.$store.getters['messaging/conversations'][key - 1].id
          }`
        )
      } else {
        this.$router.push('/messages')
      }

      // Remove conversation from conversations
      this.$store.commit(
        'messaging/removeConversationInConversations',
        this.conversation
      )
    },
  },
}
</script>
