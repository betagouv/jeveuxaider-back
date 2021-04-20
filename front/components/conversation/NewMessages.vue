<template>
  <div class="h-full">
    <transition-group name="fade-in" tag="div" class="h-full">
      <ElContainer v-if="loading" key="loading" v-loading="loading">
        <div class="w-16 h-16"></div>
      </ElContainer>

      <div v-else key="loaded" class="h-full flex flex-col">
        <template
          v-for="message in $store.getters['messaging/messages']
            .slice()
            .reverse()"
        >
          <ConversationContextualMessage
            v-if="message.type == 'contextual'"
            :key="message.id"
            :message="message"
          />

          <ConversationMessage
            v-else
            :key="message.id"
            :name="message.from.profile.first_name"
            :short-name="message.from.profile.short_name"
            :thumbnail="
              message.from.profile.image
                ? message.from.profile.image.thumb
                : null
            "
            :date="message.created_at"
          >
            <nl2br tag="p" :text="message.content" class-name="break-word" />
          </ConversationMessage>
        </template>

        <div class="sticky bottom-0 bg-white pb-6 mt-auto">
          <div class="m-auto w-full" style="max-width: 550px">
            <div
              class="px-4 py-2 pr-2 border focus-within:border-black transition flex items-end"
              style="border-radius: 8px"
            >
              <client-only>
                <textarea-autosize
                  v-model="newMessage"
                  placeholder="Saisissez un message"
                  :disabled="$store.getters.contextRole == 'admin'"
                  rows="1"
                  :max-height="120"
                  class="m-auto w-full outline-none leading-tight custom-scrollbar"
                  @keydown.enter.exact.prevent.native="onAddMessage"
                />
              </client-only>

              <button
                class="px-3 py-1 ml-3 font-semibold text-sm rounded-full bg-blue-800 text-white hover:scale-105 transform transition duration-150 ease-in-out"
                @click="onAddMessage"
              >
                Envoyer
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition-group>
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
    return {
      newMessage: '',
      loading: true,
    }
  },
  async fetch() {
    this.loading = true
    const messages = await this.$api.fetchMessages(this.conversation.id)
    this.$store.commit('messaging/setMessages', messages.data.data)
    this.loading = false
  },
  methods: {
    async onAddMessage() {
      if (this.newMessage.trim().length) {
        const response = await this.$api.addMessageToConversation(
          this.conversation.id,
          {
            content: this.newMessage,
          }
        )

        this.$store.commit('messaging/setMessages', [
          response.data,
          ...this.$store.getters['messaging/messages'],
        ])

        this.$emit('new-message')
        this.$store.commit('messaging/incrementNewMessagesCount')

        this.newMessage = ''
        await this.$store.dispatch(
          'messaging/refreshConversation',
          this.conversation.id
        )
      }
    },
  },
}
</script>
