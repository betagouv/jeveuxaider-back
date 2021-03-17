<template>
  <div id="messagerie" class="flex flex-col overflow-hidden bg-white">
    <client-only>
      <template v-if="conversations">
        <div class="h-full flex overflow-hidden">
          <div
            :class="[{ hide: !showPanelLeft }]"
            class="panel--left border-r border-cool-gray-200"
          >
            <div
              class="panel--header sticky top-0 bg-white px-6 border-b border-r border-cool-gray-200 flex items-center justify-between"
            >
              <h1 class="text-lg leading-8 font-bold text-gray-900">
                Messages
              </h1>
              <!--
            <el-dropdown trigger="click">
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
            </el-dropdown> -->
            </div>
            <div
              ref="conversationsContainer"
              class="panel--container"
              @scroll="onScrollConversations"
            >
              <div class="panel--content">
                <div v-if="$store.getters.contextRole == 'admin'" class="m-4">
                  <el-input
                    v-model="conversationFilters.search"
                    placeholder="Rechercher un utilisateur"
                    clearable
                  >
                    <svg
                      slot="prefix"
                      role="img"
                      xmlns="http://www.w3.org/2000/svg"
                      width="10"
                      height="10"
                      viewBox="0 0 40 40"
                      class="el-input__icon ml-2 mr-3"
                      style="width: 14px"
                    >
                      <path
                        d="M26.804 29.01c-2.832 2.34-6.465 3.746-10.426 3.746C7.333 32.756 0 25.424 0 16.378 0 7.333 7.333 0 16.378 0c9.046 0 16.378 7.333 16.378 16.378 0 3.96-1.406 7.594-3.746 10.426l10.534 10.534c.607.607.61 1.59-.004 2.202-.61.61-1.597.61-2.202.004L26.804 29.01zm-10.426.627c7.323 0 13.26-5.936 13.26-13.26 0-7.32-5.937-13.257-13.26-13.257C9.056 3.12 3.12 9.056 3.12 16.378c0 7.323 5.936 13.26 13.258 13.26z"
                        fillRule="evenodd"
                        fill="#6a6f85"
                      />
                    </svg>
                  </el-input>
                </div>
                <div v-if="conversations.length == 0" class="p-6 font-light">
                  Aucune conversation
                </div>
                <ConversationTeaser
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
                    conversation.latest_message
                      ? conversation.latest_message.content
                      : null
                  "
                  :date="
                    conversation.latest_message
                      ? conversation.latest_message.created_at
                      : null
                  "
                  :status="
                    conversation.conversable
                      ? conversation.conversable.state
                      : null
                  "
                  conversable-type="Participation"
                  :nametype="
                    $store.getters.contextRole == 'volontaire'
                      ? conversation.conversable.mission.structure.name
                      : null
                  "
                  class="cursor-pointer hover:bg-gray-100 transition"
                  :class="[
                    {
                      'bg-gray-200':
                        activeConversation &&
                        activeConversation.id == conversation.id,
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
                    {{ activeConversation.conversable.mission.city }}
                    <span
                      v-if="activeConversation.conversable.mission.start_date"
                      >·
                      {{
                        activeConversation.conversable.mission.start_date
                          | formatCustom('D MMM')
                      }}</span
                    >
                    <template
                      v-if="
                        activeConversation.conversable.mission.start_date &&
                        activeConversation.conversable.mission.end_date &&
                        activeConversation.conversable.mission.start_date.substring(
                          0,
                          10
                        ) !=
                          activeConversation.conversable.mission.end_date.substring(
                            0,
                            10
                          )
                      "
                    >
                      –
                      {{
                        activeConversation.conversable.mission.end_date
                          | formatCustom('D MMM YYYY')
                      }}
                    </template>
                    <template
                      v-else-if="
                        activeConversation.conversable.mission.start_date
                      "
                    >
                      {{
                        activeConversation.conversable.mission.start_date
                          | formatCustom('YYYY')
                      }}
                    </template>
                  </div>
                </div>
                <button
                  v-if="activeConversation"
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
                <transition-group name="fade-in" tag="div">
                  <ElContainer
                    v-if="conversationLoading"
                    key="conversationLoading"
                    v-loading="conversationLoading"
                  >
                    <div class="w-16 h-16"></div>
                  </ElContainer>

                  <div v-else key="conversationLoaded">
                    <template v-if="messages.length">
                      <template v-for="message in messages.slice().reverse()">
                        <template v-if="message.type == 'contextual'">
                          <ConversationContextualMessage
                            :key="message.id"
                            :message="message"
                          />
                        </template>
                        <template v-if="message.type == 'chat'">
                          <ConversationMessages
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
                          </ConversationMessages>
                        </template>
                      </template>
                    </template>
                    <div
                      v-else-if="activeConversation"
                      class="text-center text-gray-500 font-light"
                    >
                      Ceci est le tout début de votre conversation avec
                      {{
                        fromUser(activeConversation).profile.first_name
                      }}&nbsp;!<br />
                      N'hésitez pas à lui envoyer un message ;)
                    </div>
                  </div>
                </transition-group>
              </div>
            </div>

            <div v-if="activeConversation" class="sticky bottom-0 bg-white p-6">
              <div class="m-auto w-full" style="max-width: 550px">
                <div
                  class="px-4 py-2 pr-2 border focus-within:border-black transition flex items-end"
                  style="border-radius: 8px"
                >
                  <textarea-autosize
                    v-if="showPanelCenter"
                    v-model="newMessage"
                    placeholder="Saisissez un message"
                    :disabled="$store.getters.contextRole == 'admin'"
                    rows="1"
                    :max-height="120"
                    class="m-auto w-full outline-none leading-tight custom-scrollbar"
                    @keydown.enter.exact.prevent.native="onAddMessage"
                  />
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

          <div :class="[{ hide: !showPanelRight }]" class="panel--right">
            <div
              class="panel--header sticky top-0 bg-white px-6 border-b border-cool-gray-200 flex items-center"
            >
              <div class="flex flex-1 justify-between">
                <h1 class="text-lg leading-8 font-bold text-gray-900">
                  Détails
                </h1>
                <i
                  class="w-6 h-6 p-1 flex items-center justify-center rounded-full border cursor-pointer leading-none transition hover:border-black el-icon-close"
                  @click="onPanelRightToggle"
                ></i>
              </div>
            </div>
            <div class="panel--container">
              <div class="panel--content">
                <ConversationDetail
                  v-if="activeConversation"
                  :conversation-id="activeConversation.id"
                  :participation="activeConversation.conversable"
                  @updated="updateConversation"
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
    </client-only>
  </div>
</template>

<script>
import { debounce } from 'lodash'
import { mapGetters } from 'vuex'

export default {
  layout: 'messages',
  data() {
    return {
      showPanelLeft: true,
      showPanelCenter: false,
      showPanelRight: false,
      windowWidth: 1200,
      newMessage: '',
      filters: { status: 1, search: '' },
      conversationFilters: { status: 1 },
      messages: [],
      currentPage: 1,
      currentPageConversation: 1,
      lastPage: null,
      lastPageConversation: null,
      newMessageCount: 0,
      loading: true,
      conversationLoading: true,
    }
  },
  computed: {
    ...mapGetters({
      conversations: 'conversation/conversations',
      activeConversation: 'conversation/activeConversation',
    }),
    search() {
      return this.conversationFilters.search
    },
    isMobile() {
      return this.windowWidth < 768
    },
    isDesktop() {
      return this.windowWidth >= 1280
    },
  },
  watch: {
    search() {
      this.debouncedFetchConversations()
    },
    activeConversation(newConversation) {
      // @todo: bug -> Called twice when coming from handleSubmitFormParticipate in Mission.vue
      if (newConversation) {
        this.conversationLoading = true

        this.$api.fetchMessages(newConversation.id).then((response) => {
          this.messages = response.data.data
          if (this.$refs.messagesContainer) {
            this.$refs.messagesContainer.scrollTop = 0
          }
          this.currentPage = response.data.current_page
          this.lastPage = response.data.last_page

          if (!this.hasRead(newConversation)) {
            this.$store.commit(
              'conversation/addClickedUnreadConversationsIds',
              newConversation.id
            )
            this.$store.commit('auth/decrementNbUnreadConversations')
          }

          this.conversationLoading = false
        })
      }
    },
  },
  async created() {
    this.debouncedFetchConversations = debounce(this.fetchConversations, 500)
    await this.fetchConversations()
    this.$store.commit(
      'conversation/setActiveConversationId',
      this.$router.currentRoute.params.id
        ? this.$router.currentRoute.params.id
        : this.conversations[0]
        ? this.conversations[0].id
        : null
    )
  },
  mounted() {
    if (this.$router.currentRoute.name == 'messagesId' && this.isMobile) {
      this.showPanelLeft = false
      this.showPanelCenter = true
    }
    this.$nextTick(() => {
      window.addEventListener('resize', this.onResize)
      this.windowWidth = window.innerWidth
      this.onResize()
    })
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.onResize)
  },
  methods: {
    async fetchConversations() {
      const { data } = await this.$api.fetchConversations({
        'filter[search]': this.conversationFilters.search,
        page: 1,
      })
      this.lastPageConversation = data.last_page
      this.$store.commit('conversation/setConversations', data.data)
      this.loading = false
    },
    onResize() {
      this.windowWidth = window.innerWidth
      this.showPanelLeft = !!(!this.activeConversation || !this.isMobile)
      this.showPanelCenter = !!(this.activeConversation || !this.isMobile)
      this.showPanelRight = !!this.isDesktop
    },
    onScroll() {
      const scrollHeight =
        this.$refs.messagesContainer.scrollHeight -
        this.$refs.messagesContainer.offsetHeight

      if (
        this.currentPage < this.lastPage &&
        this.$refs.messagesContainer.scrollTop + scrollHeight == 0
      ) {
        this.fetchNextPage()
      }
    },
    onScrollConversations() {
      const isBottom =
        this.$refs.conversationsContainer.offsetHeight +
          this.$refs.conversationsContainer.scrollTop ==
        this.$refs.conversationsContainer.scrollHeight

      if (
        this.currentPageConversation < this.lastPageConversation &&
        isBottom
      ) {
        this.fetchNextPageConversations()
      }
    },
    fetchNextPage() {
      this.$api
        .fetchMessages(this.activeConversation.id, {
          page: this.currentPage + 1,
          itemsPerPage: 15 + this.newMessageCount,
        })
        .then((response) => {
          this.messages = [...this.messages, ...response.data.data]
          this.currentPage = response.data.current_page
        })
    },
    fetchNextPageConversations() {
      this.$api
        .fetchConversations({
          'filter[search]': this.conversationFilters.search,
          page: this.currentPageConversation + 1,
        })
        .then((response) => {
          this.$store.commit('conversation/setConversations', [
            ...this.conversations,
            ...response.data.data,
          ])
          this.currentPageConversation = response.data.current_page
        })
    },
    onPanelLeftToggle() {
      if (this.isMobile) {
        this.showPanelCenter = false
      }
      this.showPanelLeft = !this.showPanelLeft
      // do not change to $router.push, as it will redo the created function
      window.history.pushState({ id: null }, '', `/messages`)
    },
    onTeaserClick(conversation) {
      if (
        !this.activeConversation ||
        conversation.id != this.activeConversation.id
      ) {
        this.newMessage = ''
        this.$store.commit(
          'conversation/setActiveConversationId',
          conversation.id
        )
      }

      if (this.isMobile) {
        this.showPanelLeft = false
      }
      this.showPanelCenter = true

      // do not change to $router.push, as it will redo the created function
      window.history.pushState(
        { id: conversation.id },
        '',
        `/messages/${conversation.id}`
      )
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
        }
        if (!this.isDesktop) {
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
      if (this.$store.getters.contextRole == 'admin') {
        return true
      }
      if (
        this.$store.getters[
          'conversation/clickedUnreadConversationsIds'
        ].includes(conversation.id)
      ) {
        return true
      }

      if (conversation.latest_message) {
        if (!this.currentUser(conversation).pivot.read_at) {
          return false
        }

        return !(
          this.$dayjs(conversation.updated_at).unix() >
          this.$dayjs(this.currentUser(conversation).pivot.read_at).unix()
        )
      }
      return true
    },
    onAddMessage() {
      if (this.newMessage.trim().length) {
        this.$api
          .addMessageToConversation(this.activeConversation.id, {
            content: this.newMessage,
          })
          .then((response) => {
            this.newMessageCount++
            this.newMessage = ''
            this.messages = [response.data, ...this.messages]
            this.$refs.messagesContainer.scrollTop = 0
            this.$store.commit(
              'conversation/updateLastestMessage',
              response.data
            )
          })
      }
    },
    async updateConversation(conversationId) {
      const conversation = await this.$api.getConversation(conversationId)
      this.$store.commit('conversation/updateConversation', conversation)
    },
  },
}
</script>

<style lang="sass" scoped>
#messagerie
  height: calc(100% - 110px)
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
    width: 415px
    @apply flex-none
    > *
      width: 415px

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
