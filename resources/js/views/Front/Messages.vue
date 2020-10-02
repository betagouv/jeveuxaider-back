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
              :message="lastMessage(conversation).content"
              :date="lastMessage(conversation).timestamp"
              :status="conversation.status"
              class="cursor-pointer hover:bg-gray-100 transition"
              :class="[
                {
                  'bg-gray-200':
                    activeConversation &&
                    activeConversation.id == conversation.id,
                },
              ]"
              :is-new="
                lastMessage(conversation).timestamp >
                currentUser(conversation).readAt
                  ? true
                  : false
              "
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
                :message="message.content"
                :date="message.timestamp"
              />
            </template>
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
      conversations: [
        {
          id: 1,
          status: true, // archiché ou actif
          participation: {},
          users: [
            {
              id: 2,
              profile: {
                email: 'tintin@milou.fr',
                first_name: 'Tintin',
                last_name: 'Milou',
                short_name: 'TM',
                image: null,
              },
              readAt: 1601460537,
            },
            {
              id: 1,
              profile: {
                email: 'kevin@codeconut.fr',
                first_name: 'Tintin',
                last_name: 'Milou',
                short_name: 'KV',
                image: null,
              },
              readAt: 1601460653,
            },
          ],
          messages: [
            {
              from: {
                id: 2,
                profile: {
                  email: 'tintin@milou.fr',
                  first_name: 'Tintin',
                  last_name: 'Milou',
                  short_name: 'TM',
                  image: null,
                },
              },
              content:
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris cursus vestibulum mollis. Sed rhoncus vehicula dolor, at suscipit magna luctus eget. Mauris eu dignissim odio. Vivamus vestibulum mi vel dapibus facilisis.',
              timestamp: 1601460537,
            },
            {
              from: {
                id: 1,
                profile: {
                  email: 'kevin@codeconut.fr',
                  first_name: 'Kevin',
                  last_name: 'Vaissaud',
                  short_name: 'KV',
                  image: null,
                },
              },
              content: 'Bonjour monsieur ! bla bla bla et re bla bla bla',
              timestamp: 1601460653,
            },
            {
              from: {
                id: 2,
                profile: {
                  email: 'tintin@milou.fr',
                  first_name: 'Tintin',
                  last_name: 'Milou',
                  short_name: 'TM',
                  image: null,
                },
              },
              content:
                'Praesent euismod et sapien et maximus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Morbi dignissim luctus dapibus. Cras eget dui bibendum, ultrices dui et, accumsan elit.',
              timestamp: 1601480021,
            },
          ],
        },
        {
          id: 2,
          status: false, // archiché ou actif
          participation: {},
          users: [
            {
              id: 3,
              profile: {
                email: 'michel@sardou.fr',
                first_name: 'Michel',
                last_name: 'Sardou',
                short_name: 'MS',
                image: null,
              },
              readAt: 1601460537,
            },
            {
              id: 1,
              profile: {
                email: 'kevin@codeconut.fr',
                first_name: 'Tintin',
                last_name: 'Milou',
                short_name: 'KV',
                image: null,
              },
              readAt: 1601460500,
            },
          ],
          messages: [
            {
              from: {
                id: 3,
                profile: {
                  email: 'michel@sardou.fr',
                  first_name: 'Michel',
                  last_name: 'Sardou',
                  short_name: 'MS',
                  image: null,
                },
              },
              content:
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris cursus vestibulum mollis. Sed rhoncus vehicula dolor, at suscipit magna luctus eget. Mauris eu dignissim odio. Vivamus vestibulum mi vel dapibus facilisis.',
              timestamp: 1601460537,
            },
          ],
        },
      ],
    }
  },
  watch: {
    activeConversation(newConversation, oldConversation) {
      if (oldConversation) {
        console.log('TODO 1 - Update readAt for real...')
        this.currentUser(oldConversation).readAt = Math.floor(
          new Date().getTime() / 1000
        )
      }
    },
  },
  created() {
    this.activeConversation =
      this.windowWidth >= 768 ? this.conversations[0] : null
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
    this.currentUser(this.activeConversation).readAt = Math.floor(
      new Date().getTime() / 1000
    )
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
      this.currentUser(this.activeConversation).readAt = Math.floor(
        new Date().getTime() / 1000
      )
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
    @apply flex-col-reverse
    .panel--content
      max-width: 600px
      @apply m-auto pt-4
      // min-height: 3000px
      // background: linear-gradient(#e66465, #9198e5)

.panel--right
  width: 100%
  @screen md
    width: 375px
    @apply flex-none
</style>
