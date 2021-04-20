<template>
  <div class="bg-gray-100 h-full flex flex-col">
    <AppHeader />

    <client-only>
      <div
        id="messagerie"
        class="flex-1 flex flex-col overflow-hidden bg-white"
      >
        <div class="h-full flex overflow-hidden">
          <!-- LEFT -->
          <div
            class="panel--left border-r border-cool-gray-200"
            :class="[{ hide: !$store.getters['messaging/showPanelLeft'] }]"
          >
            <div
              class="panel--header sticky top-0 bg-white px-6 border-b border-r border-cool-gray-200 flex items-center justify-between"
            >
              <div class="text-lg leading-8 font-bold text-gray-900">
                Messages
              </div>

              <ConversationToggleStatus @update="onStatusChange($event)" />
            </div>

            <div
              ref="conversationsContainer"
              class="panel--container"
              @scroll="onScrollConversations"
            >
              <div class="panel--content">
                <!-- TODO: Rechercher un utilisateur -->

                <ConversationTeaser2
                  v-for="conversationTeaser in $store.getters[
                    'messaging/conversations'
                  ]"
                  :key="conversationTeaser.id"
                  :conversation="conversationTeaser"
                  class="cursor-pointer hover:bg-gray-100 transition"
                  :class="[
                    {
                      'bg-gray-200':
                        $store.getters['messaging/conversation'] &&
                        conversationTeaser.id ==
                          $store.getters['messaging/conversation'].id,
                    },
                  ]"
                  @click.native="onConversationClick(conversationTeaser)"
                />

                <ElContainer
                  v-if="currentPageConversation < lastPageConversation"
                  key="loading"
                  v-loading="true"
                >
                  <div class="w-16 h-16"></div>
                </ElContainer>
              </div>
            </div>
          </div>

          <Nuxt />
        </div>
      </div>
    </client-only>

    <transition name="fade">
      <LazySearchOverlay
        v-if="$store.getters.searchOverlay"
        @submitted="$store.commit('toggleSearchOverlay')"
        @closed="$store.commit('toggleSearchOverlay')"
      />
    </transition>
  </div>
</template>

<script>
export default {
  name: 'MessagesLayout',
  middleware: 'logged',
  data() {
    return {
      currentPageConversation: 1,
      lastPageConversation: null,
      conversationFilters: {
        'filter[search]': null,
        'filter[exclude]': null,
        'filter[status]': 1,
      },
      windowWidth: 0,
    }
  },
  async fetch() {
    let conversations = await this.$api.fetchConversations2({
      page: 1,
      ...this.conversationFilters,
    })
    this.lastPageConversation = conversations.data.last_page
    conversations = conversations.data.data

    // Like Facebook.
    // If the conversation is not present in the first
    // page of results, we get it here.
    if (this.$router.currentRoute.params.id) {
      const isInConversations = conversations.find((conversation) => {
        return conversation.id == this.$router.currentRoute.params.id
      })
      if (!isInConversations) {
        const conversation = await this.$api.getConversation2(
          this.$router.currentRoute.params.id
        )
        conversations = [...conversations, conversation]
        this.conversationFilters['filter[exclude]'] = conversation.id
      }
    }

    this.$store.commit('messaging/setConversations', conversations)
  },
  head: {
    bodyAttrs: {
      class: 'full-height-layout',
    },
  },
  mounted() {
    if (
      this.$router.currentRoute.params.id &&
      this.$store.getters['messaging/isMobile']
    ) {
      this.$store.commit('messaging/setShowPanelLeft', false)
      this.$store.commit('messaging/setShowPanelCenter', true)
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
    onConversationClick(conversation) {
      this.$router.push(`/messagess/${conversation.id}`)
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
    async fetchNextPageConversations() {
      const conversations = await this.$api.fetchConversations2({
        page: this.currentPageConversation + 1,
        ...this.conversationFilters,
      })
      this.$store.commit('messaging/setConversations', [
        ...this.$store.getters['messaging/conversations'],
        ...conversations.data.data,
      ])

      this.currentPageConversation = conversations.data.current_page
    },
    onResize() {
      this.windowWidth = window.innerWidth
      this.$store.commit('messaging/setIsMobile', this.windowWidth < 768)
      this.$store.commit('messaging/setIsDesktop', this.windowWidth >= 1280)

      console.log('onResize', this.$route)

      // this.showPanelLeft = !!(!this.activeConversation || !this.isMobile)
      this.$store.commit(
        'messaging/setShowPanelLeft',
        !!(
          this.$route.name == 'messagess' ||
          !this.$store.getters['messaging/isMobile']
        )
      )

      // this.showPanelCenter = !!(this.activeConversation || !this.isMobile)
      this.$store.commit(
        'messaging/setShowPanelCenter',
        !!(
          this.$route.name == 'messagess-id' ||
          !this.$store.getters['messaging/isMobile']
        )
      )

      this.$store.commit(
        'messaging/setShowPanelRight',
        this.$store.getters['messaging/isDesktop']
      )
    },
  },
}
</script>

<style lang="sass" scoped>
#messagerie
  height: calc(100% - 110px)
.panel--left
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
</style>
