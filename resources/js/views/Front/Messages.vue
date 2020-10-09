<template>
  <div class="h-full flex flex-col overflow-hidden">
    <AppHeader />

    <template v-if="!loading && conversations">
      <div class="h-full flex overflow-hidden">
        <div
          :class="[{ hide: !showPanelLeft }]"
          class="panel--left border-r border-cool-gray-200"
        >
          <div
            class="panel--header sticky top-0 bg-white px-6 border-b border-r border-cool-gray-200 flex items-center justify-between"
          >
            <h1 class="text-lg leading-8 font-bold text-gray-900">Messages</h1>

            <el-dropdown trigger="click" @command="handleFilters">
              <span class="el-dropdown-link">
                <button
                  class="ml-2 text-xs flex-none rounded-full px-3 py-1 my-4 sm:my-0 border hover:border-black transition"
                >
                  Filtres ( coming )
                </button>
              </span>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item
                  :command="{ status: 1 }"
                  :class="{ active: filters.status == 1 }"
                  class="font-light"
                >
                  Conversations actives
                </el-dropdown-item>
                <el-dropdown-item
                  :command="{ status: 0 }"
                  :class="{ active: filters.status == 0 }"
                  class="font-light"
                >
                  Conversations archivées
                </el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </div>
          <div class="panel--container">
            <div class="panel--content">
              <div
                v-if="filteredConversations.length == 0"
                class="p-6 font-light"
              >
                Aucune conversation
              </div>
              <ConversationTeaser
                v-for="conversation in filteredConversations"
                :key="conversation.id"
                :name="fromUser(conversation).profile.first_name"
                :short-name="fromUser(conversation).profile.short_name"
                :thumbnail="
                  fromUser(conversation).profile.image
                    ? fromUser(conversation).profile.image.thumb
                    : null
                "
                :message="
                  conversation.latest_message
                    ? conversation.latest_message.content
                    : null
                "
                :date="
                  conversation.latest_message
                    ? conversation.latest_message.created_at
                    : null
                "
                :status="conversation.participation.state"
                class="cursor-pointer hover:bg-gray-100 transition"
                :class="[
                  {
                    'bg-gray-200':
                      activeConversation &&
                      activeConversationId == conversation.id,
                  },
                ]"
                :has-read="hasRead(conversation)"
                @click.native="onTeaserClick(conversation)"
              />
            </div>
          </div>
        </div>

        <div
          :class="[{ hide: !showPanelCenter }]"
          class="panel--center min-w-0 border-r border-cool-gray-200"
        >
          <div
            class="panel--header sticky top-0 bg-white px-6 border-b border-cool-gray-200 flex items-center"
          >
            <div
              class="min-w-0 flex flex-wrap sm:flex-no-wrap flex-1 justify-between items-center"
            >
              <button
                class="order-1 md:hidden text-xs flex-none rounded-full px-3 py-1 mr-2 my-4 sm:my-0 border hover:border-black transition"
                @click="onPanelLeftToggle"
              >
                Retour
              </button>
              <div
                v-if="activeConversation"
                class="order-4 w-full sm:w-auto sm:order-2 mb-4 sm:mb-0 sm:truncate"
              >
                <h1
                  class="text-lg leading-8 font-bold text-gray-900 sm:truncate"
                >
                  {{ fromUser(activeConversation).profile.first_name }}
                  {{ fromUser(activeConversation).profile.last_name }}
                </h1>
                <div class="text-sm text-gray-500 font-light sm:truncate">
                  {{ activeConversation.participation.mission.city }}
                  <span
                    v-if="activeConversation.participation.mission.start_date"
                    >·
                    {{
                      activeConversation.participation.mission.start_date
                        | formatCustom('D MMM')
                    }}</span
                  >
                  <template
                    v-if="
                      activeConversation.participation.mission.start_date &&
                      activeConversation.participation.mission.start_date.substring(
                        0,
                        10
                      ) !=
                        activeConversation.participation.mission.end_date.substring(
                          0,
                          10
                        )
                    "
                  >
                    –
                    {{
                      activeConversation.participation.mission.end_date
                        | formatCustom('D MMM YYYY')
                    }}
                  </template>
                  <template v-else>
                    {{
                      activeConversation.participation.mission.start_date
                        | formatCustom('YYYY')
                    }}
                  </template>
                </div>
              </div>
              <button
                v-if="activeConversationId"
                class="order-3 ml-2 text-xs flex-none rounded-full px-3 py-1 my-4 sm:my-0 border hover:border-black transition"
                @click="onPanelRightToggle"
                v-html="
                  showPanelRight ? 'Masquer les détails' : 'Voir les détails'
                "
              ></button>
            </div>
          </div>

          <div
            ref="messagesContainer"
            class="panel--container"
            @scroll="onScroll"
          >
            <div class="panel--content">
              <template v-if="activeConversation">
                <template v-if="messages.length">
                  <MessageFull
                    v-for="message in messages.slice().reverse()"
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
                <template v-else>
                  <div class="text-center text-gray-500 font-light">
                    Ceci est le tout début de votre conversation avec
                    {{
                      fromUser(activeConversation).profile.first_name
                    }}&nbsp;!<br />
                    N'hésitez pas à lui envoyer un message ;)
                  </div>
                </template>
              </template>
            </div>
          </div>

          <div v-if="activeConversationId" class="sticky bottom-0 bg-white p-6">
            <div class="m-auto w-full" style="max-width: 550px">
              <div
                class="px-4 py-2 pr-2 border focus-within:border-black transition flex items-end"
                style="border-radius: 8px"
              >
                <textarea-autosize
                  v-if="showPanelCenter"
                  v-model="newMessage"
                  placeholder="Saisissez un message"
                  rows="1"
                  :max-height="120"
                  class="w-full outline-none leading-tight custom-scrollbar"
                  @keydown.enter.exact.prevent.native="onAddMessage"
                />
                <font-awesome-icon
                  icon="paper-plane"
                  class="w-5 h-5 ml-2 cursor-pointer transition text-gray-300 hover:text-primary"
                  @click="onAddMessage"
                />
              </div>
            </div>
          </div>
        </div>

        <div :class="[{ hide: !showPanelRight }]" class="panel--right">
          <div
            class="panel--header sticky top-0 bg-white px-6 border-b border-cool-gray-200 flex items-center"
          >
            <div class="flex flex-1 justify-between">
              <h1 class="text-lg leading-8 font-bold text-gray-900">Détails</h1>
              <font-awesome-icon
                icon="times"
                class="w-6 h-6 p-1 flex items-center justify-center rounded-full border cursor-pointer leading-none transition hover:border-black"
                @click="onPanelRightToggle"
              />
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
    </template>

    <template v-else>
      <div class="container mx-auto h-full">
        <div
          class="p-6 text-center flex flex-col justify-center items-center h-full"
        >
          <div class="text-gray-500 font-light">
            Vos prochaines conversations apparaitront ici.
          </div>
          <a
            href="/missions"
            class="mt-8 shadow-lg flex items-center justify-center px-10 py-3 text-base leading-6 font-medium rounded-full bg-white text-blue-800 hover:bg-gray-100 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out md:py-4 md:text-lg md:px-15"
          >
            Trouver une mission
          </a>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import MessageDetails from '@/components/messages/MessageDetails.vue'
import ConversationTeaser from '@/components/messages/ConversationTeaser.vue'
import MessageFull from '@/components/messages/MessageFull.vue'
import {
  fetchConversations,
  fetchMessages,
  addMessage,
} from '@/api/conversations'
import dayjs from 'dayjs'
import qs from 'qs'

export default {
  name: 'Messages',
  components: {
    MessageDetails,
    ConversationTeaser,
    MessageFull,
  },
  data() {
    return {
      showPanelLeft: true,
      showPanelCenter: false,
      showPanelRight: false,
      windowWidth: window.innerWidth,
      newMessage: '',
      filters: { status: 1 },
      activeConversationId: this.$router.currentRoute.params.id ?? null,
      conversations: [],
      messages: [],
      currentPage: 1,
      lastPage: null,
      newMessageCount: 0,
      loading: true,
    }
  },
  computed: {
    activeConversation() {
      return this.conversations.find(
        (conversation) => conversation.id == this.activeConversationId
      )
    },
    filteredConversations() {
      return this.conversations
      // TODO
      /*
      return this.conversations.filter((c) => {
        return c.status == this.filters.status
      })
      */
    },
    isMobile() {
      return this.windowWidth < 768
    },
    isDesktop() {
      return this.windowWidth >= 1280
    },
  },
  watch: {
    activeConversation(newConversation) {
      if (newConversation) {
        fetchMessages(newConversation.id).then((response) => {
          this.messages = response.data.data
          this.$refs['messagesContainer'].scrollTop = 0
          this.currentPage = response.data.current_page
          this.lastPage = response.data.last_page

          // Fake update of nbUnreadConversations
          if (!this.hasRead(newConversation)) {
            this.$store.getters.user.nbUnreadConversations--
          }

          this.currentUser(newConversation).pivot.read_at = dayjs().format(
            'YYYY-MM-DD HH:mm:ss'
          )
        })
      }
    },
  },
  created() {
    fetchConversations().then((response) => {
      this.conversations = response.data.data
      if (!this.activeConversationId && !this.isMobile) {
        this.activeConversationId = this.conversations[0].id
      }
      this.loading = false
    })
  },
  mounted() {
    if (this.$router.currentRoute.name == 'messagesId' && this.isMobile) {
      this.showPanelLeft = false
      this.showPanelCenter = true
    }
    this.$nextTick(() => {
      window.addEventListener('resize', this.onResize)
      window.addEventListener('popstate', this.handleHistoryChange)
      this.onResize()
    })
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.onResize)
    window.removeEventListener('popstate', this.handleHistoryChange)
  },
  methods: {
    onResize() {
      this.windowWidth = window.innerWidth
      this.showPanelLeft =
        !this.activeConversation || !this.isMobile ? true : false
      this.showPanelCenter =
        this.activeConversation || !this.isMobile ? true : false
      this.showPanelRight = this.isDesktop ? true : false

      if (
        !this.isMobile &&
        !this.activeConversationId &&
        this.conversations.length > 0
      ) {
        this.activeConversationId = this.conversations[0].id
      }
    },
    onScroll() {
      let scrollHeight =
        this.$refs['messagesContainer'].scrollHeight -
        this.$refs['messagesContainer'].offsetHeight

      if (
        this.currentPage < this.lastPage &&
        this.$refs['messagesContainer'].scrollTop + scrollHeight == 0
      ) {
        this.fetchNextPage()
      }
    },
    fetchNextPage() {
      fetchMessages(this.activeConversationId, {
        page: this.currentPage + 1,
        itemsPerPage: 15 + this.newMessageCount,
      }).then((response) => {
        this.messages = [...this.messages, ...response.data.data]
        this.currentPage = response.data.current_page
      })
    },
    onPanelLeftToggle() {
      this.activeConversationId = null

      if (this.isMobile) {
        this.showPanelCenter = false
      }

      this.showPanelLeft = !this.showPanelLeft

      window.history.pushState({ id: null }, '', `/messages`)
    },
    onTeaserClick(conversation) {
      if (conversation.id != this.activeConversationId) {
        this.newMessage = ''
        this.activeConversationId = conversation.id
        if (this.isMobile) {
          this.showPanelLeft = false
        }
        this.showPanelCenter = true

        window.history.pushState(
          { id: conversation.id },
          '',
          `/messages/${conversation.id}`
        )
      }
    },
    handleHistoryChange(event) {
      if (event.state && event.state.id) {
        this.activeConversationId = event.state.id
        if (this.isMobile) {
          this.showPanelLeft = false
          this.showPanelCenter = true
        }
      } else if (this.isMobile) {
        this.activeConversationId = null
        this.showPanelLeft = true
        this.showPanelCenter = false
      }
    },
    onPanelRightToggle() {
      if (this.showPanelRight) {
        // Show
        if (this.isMobile) {
          this.showPanelCenter = true
          this.showPanelLeft = false
        } else if (!this.isDesktop) {
          this.showPanelLeft = !this.showPanelLeft
        }
      } else {
        // Hide
        if (this.isMobile) {
          this.showPanelCenter = false
          this.showPanelLeft = false
        } else if (!this.isDesktop) {
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
    hasRead(conversation) {
      if (conversation.latest_message) {
        if (!this.currentUser(conversation).pivot.read_at) {
          return false
        }

        const messageTimestamp = dayjs(conversation.updated_at).unix()
        const userTimestamp = dayjs(
          this.currentUser(conversation).pivot.read_at
        ).unix()

        return messageTimestamp > userTimestamp ? false : true
      }
      return true
    },
    onAddMessage() {
      if (this.newMessage.trim().length) {
        addMessage({
          content: this.newMessage,
          conversation_id: this.activeConversation.id,
        }).then((response) => {
          this.newMessageCount++
          this.newMessage = ''
          this.messages = [response.data, ...this.messages]
          this.$refs['messagesContainer'].scrollTop = 0
          // Update last message in the corresponding conversation teaser
          this.activeConversation.latest_message = response.data
        })
      }
    },
    handleFilters(filters) {
      this.filters = filters
      this.activeConversationId = null
    },
    stringifyQuery(query) {
      const result = qs.stringify(query)
      return result ? '?' + result : ''
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
  .panel--header
    min-height: 77px
  .panel--container
    @apply flex flex-col overflow-y-auto

.panel--left
  flex: 1 1 0%
  @screen md
    width: 375px
    @apply flex-none
    > *
      width: 375px

.panel--center
  width: 100%
  @apply flex-grow
  @screen md
    flex: 1 1 0%
  .panel--container
    @apply flex-col-reverse flex-1 px-6
    .panel--content
      max-width: 550px
      @apply mx-auto mb-auto w-full pt-4

.panel--right
  width: 100%
  @screen md
    width: 415px
    @apply flex-none
    > *
      width: 415px

::v-deep .el-dropdown-menu__item:not(.is-disabled)
  @apply text-gray-500
  &:hover
    @apply bg-gray-200 text-gray-500
  &.active
    @apply bg-gray-200 text-black
</style>
