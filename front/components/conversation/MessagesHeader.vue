<template>
  <div
    class="min-w-0 flex flex-wrap sm:flex-no-wrap flex-1 justify-between items-center"
  >
    <button
      class="order-1 md:hidden text-xs flex-none rounded-full px-3 py-1 mr-2 my-4 sm:my-0 border hover:border-black transition"
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

    <button
      class="order-3 ml-2 text-xs flex-none rounded-full px-3 py-1 my-4 sm:my-0 border hover:border-black transition"
      @click="$emit('toggle-panel-right')"
      v-html="
        $store.getters['messaging/showPanelRight']
          ? 'Masquer les détails'
          : 'Voir les détails'
      "
    />
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
  },
  methods: {
    onPanelLeftToggle() {
      this.$store.commit('messaging/setShowPanelCenter', false)
      this.$store.commit('messaging/setShowPanelLeft', true)
    },
  },
}
</script>
