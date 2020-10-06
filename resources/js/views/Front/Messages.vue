<template>
  <div class="h-full flex flex-col overflow-hidden">
    <AppHeader />

    <div class="flex overflow-hidden">
      <div
        :class="[{ hide: !showPanelLeft }]"
        class="panel--left border-r border-cool-gray-200"
      >
        <div class="sticky top-0 bg-white p-6 border-b border-cool-gray-200">
          <h1 class="text-lg leading-8 font-bold text-gray-900">Messages</h1>
        </div>
        <div class="panel--container">
          <div class="panel--content">
            <!-- TODO Afficher statut de la mission -->
            <MessageTeaser
              v-for="conversation in conversations"
              :key="conversation.id"
              :name="fromUser(conversation).profile.first_name"
              :short-name="fromUser(conversation).profile.short_name"
              :thumbnail="
                fromUser(conversation).profile.image
                  ? fromUser(conversation).profile.image.thumb
                  : null
              "
              :message="
                lastMessage(conversation)
                  ? lastMessage(conversation).content
                  : null
              "
              :date="
                lastMessage(conversation)
                  ? lastMessage(conversation).created_at
                  : null
              "
              :status="conversation.status"
              class="cursor-pointer hover:bg-gray-100 transition"
              :class="[
                {
                  'bg-gray-200':
                    activeConversation &&
                    activeConversation.id == conversation.id,
                },
              ]"
              :is-new="isNew(conversation)"
              @click.native="onTeaserClick(conversation)"
            />
          </div>
        </div>
      </div>

      <div
        :class="[{ hide: !showPanelCenter }]"
        class="panel--center border-r border-cool-gray-200"
      >
        <div class="sticky top-0 bg-white p-6 border-b border-cool-gray-200">
          <div class="flex justify-between">
            <div class="md:hidden" @click="onPanelLeftToggle">BACK</div>
            <h1
              v-if="activeConversation"
              class="text-lg leading-8 font-bold text-gray-900"
            >
              {{ fromUser(activeConversation).profile.first_name }}
              {{ fromUser(activeConversation).profile.last_name }}
            </h1>
            <button
              @click="onPanelRightToggle"
              v-html="
                showPanelRight ? 'Masquer les détails' : 'Voir les détails'
              "
            ></button>
          </div>
        </div>

        <div class="panel--container">
          <div class="panel--content">
            <template v-if="activeConversation">
              <MessageFull
                v-for="message in activeConversation.messages"
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
                <nl2br tag="p" :text="message.content" />
              </MessageFull>
            </template>
          </div>
        </div>

        <div class="sticky bottom-0 bg-white p-6">
          <div class="m-auto w-full" style="max-width: 550px">
            <div
              class="px-4 py-2 pr-2 border flex items-end"
              style="border-radius: 8px"
            >
              <textarea-autosize
                v-model="newMessage"
                placeholder="Saisissez un message"
                rows="1"
                :max-height="120"
                class="w-full outline-none leading-tight custom-scrollbar"
                @keydown.enter.exact.prevent.native="addMessage"
              />
              <img
                class="w-5 h-5 ml-2 cursor-pointer"
                src="/images/send.svg"
                @click="addMessage"
              />
            </div>
          </div>
        </div>
      </div>

      <div :class="[{ hide: !showPanelRight }]" class="panel--right">
        <div class="sticky top-0 bg-white p-6 border-b border-cool-gray-200">
          <div class="flex justify-between">
            <h1 class="text-lg leading-8 font-bold text-gray-900">Détails</h1>
            <button @click="onPanelRightToggle">X</button>
          </div>
        </div>
        <div class="panel--container">
          <div class="panel--content">
            <MessageDetails
              v-if="activeConversation"
              :participation="activeConversation.participation"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import MessageDetails from '@/components/messages/MessageDetails.vue'
import MessageTeaser from '@/components/messages/MessageTeaser.vue'
import MessageFull from '@/components/messages/MessageFull.vue'
import { fetchConversations, addMessage } from '@/api/conversations'
import dayjs from 'dayjs'

export default {
  name: 'Messages',
  components: {
    MessageDetails,
    MessageTeaser,
    MessageFull,
  },
  data() {
    return {
      showPanelLeft: true,
      showPanelCenter: false,
      showPanelRight: false,
      windowWidth: window.innerWidth,
      activeConversation: null,
      newMessage: '',
      conversations: [],
    }
  },
  watch: {
    activeConversation(newConversation, oldConversation) {
      if (oldConversation) {
        console.log('TODO 1 - Update readAt for real...')
        this.currentUser(oldConversation).pivot.read_at = dayjs().unix()
      }
    },
  },
  created() {
    this.$store.commit('setLoading', true)
    fetchConversations(this.$store.getters.user.profile.id)
      .then((response) => {
        if (response.data && response.data.data) {
          this.conversations = response.data.data
          this.activeConversation =
            this.windowWidth >= 768 && this.conversations[0]
              ? this.conversations[0]
              : null
        }
        this.$store.commit('setLoading', false)
        this.loading = false
      })
      .catch(() => {
        this.loading = false
      })
  },
  mounted() {
    this.onResize()
    this.$nextTick(() => {
      window.addEventListener('resize', this.onResize)
    })
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.onResize)

    console.log('TODO 2 - Update readAt for real...')
    this.currentUser(this.activeConversation).pivot.read_at = dayjs().unix()
  },
  methods: {
    onResize() {
      this.windowWidth = window.innerWidth

      this.showPanelLeft =
        !this.activeConversation || this.windowWidth >= 768 ? true : false
      this.showPanelCenter =
        this.activeConversation || this.windowWidth >= 768 ? true : false
      this.showPanelRight = this.windowWidth >= 1280 ? true : false
    },
    onPanelLeftToggle() {
      console.log('TODO 3 - Update readAt for real...')
      this.currentUser(this.activeConversation).pivot.read_at = dayjs().unix()
      this.activeConversation = null

      if (this.windowWidth < 768) {
        this.showPanelCenter = false
      }

      this.showPanelLeft = !this.showPanelLeft
    },
    onTeaserClick(conversation) {
      if (this.windowWidth < 768) {
        this.showPanelLeft = false
      }

      this.activeConversation = conversation
      this.showPanelCenter = true
    },
    onPanelRightToggle() {
      if (this.showPanelRight) {
        // Show
        if (this.windowWidth < 768) {
          this.showPanelCenter = true
          this.showPanelLeft = false
        } else if (this.windowWidth < 1280) {
          this.showPanelLeft = !this.showPanelLeft
        }
      } else {
        // Hide
        if (this.windowWidth < 768) {
          this.showPanelCenter = false
          this.showPanelLeft = false
        } else if (this.windowWidth < 1280) {
          this.showPanelLeft = !this.showPanelLeft
        }
      }

      this.showPanelRight = !this.showPanelRight
    },
    fromUser(conversation) {
      return conversation.users.filter((user) => {
        return user.id != this.$store.getters.user.id
      })[0]
    },
    currentUser(conversation) {
      return conversation.users.filter((user) => {
        return user.id == this.$store.getters.user.id
      })[0]
    },
    lastMessage(conversation) {
      return conversation.messages[conversation.messages.length - 1]
    },
    isNew(conversation) {
      if (this.lastMessage(conversation) && this.currentUser(conversation)) {
        if (!this.currentUser(conversation).pivot.read_at) {
          return true
        }

        const messageTimestamp = dayjs(
          this.lastMessage(conversation).created_at
        ).unix()
        return messageTimestamp > this.currentUser(conversation).pivot.read_at
          ? true
          : false
      }
      return false
    },
    addMessage() {
      addMessage({
        content: this.newMessage,
        conversation_id: this.activeConversation.id,
      }).then((response) => {
        this.activeConversation = response.data
        this.newMessage = ''
        this.conversations.splice(
          this.conversations.findIndex(
            (el) => el.id === this.activeConversation.id
          ),
          1,
          response.data
        )
        console.log('TODO 4 - Update readAt for real...')
        this.currentUser(this.activeConversation).pivot.read_at = dayjs().unix()
      })
    },
  },
}
</script>

<style lang="sass" scoped>
.panel--left,
.panel--center,
.panel--right
  transition: opacity .25s
  opacity: 1
  pointer-events: auto
  @apply flex flex-col max-w-full
  @screen md
    transition: all .25s
  &.hide
    flex: 0 1 0%
    width: 0
    opacity: 0
    pointer-events: none
  .panel--container
    @apply flex flex-col overflow-y-auto

.panel--left
  flex: 1 1 0%
  @screen md
    width: 375px
    @apply flex-none

.panel--center
  width: 100%
  @apply flex-grow
  @screen md
    flex: 1 1 0%
  .panel--container
    @apply flex-col-reverse flex-1
    .panel--content
      max-width: 550px
      @apply mx-auto mb-auto w-full pt-4

.panel--right
  width: 100%
  @screen md
    width: 375px
    @apply flex-none
</style>
