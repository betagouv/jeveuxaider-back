<template>
  <div class="h-full flex flex-col overflow-hidden">
    <AppHeader />

    <div class="flex overflow-hidden">
      <div :class="[{ hide: !showPanelLeft }]" class="panel--left">
        <div
          class="sticky top-0 bg-white p-6 border-b border-r border-cool-gray-200"
        >
          <h1 class="text-lg leading-8 font-bold text-gray-900">Messages</h1>
        </div>
        <div class="panel--container">
          <div class="panel--content">
            <button @click="onPanelCenterToggle">Voir message</button>
          </div>
        </div>
      </div>

      <div :class="[{ hide: !showPanelCenter }]" class="panel--center">
        <div class="sticky top-0 bg-white p-6 border-b border-cool-gray-200">
          <div class="flex justify-between">
            <div class="md:hidden" @click="onPanelLeftToggle">BACK</div>
            <h1 class="text-lg leading-8 font-bold text-gray-900">
              Tintin Milou
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
          <div class="panel--content"></div>
        </div>
      </div>

      <div :class="[{ hide: !showPanelRight }]" class="panel--right">
        <div
          class="sticky top-0 bg-white p-6 border-l border-b border-cool-gray-200"
        >
          <div class="flex justify-between">
            <h1 class="text-lg leading-8 font-bold text-gray-900">Détails</h1>
            <button @click="onPanelRightToggle">X</button>
          </div>
        </div>
        <div class="panel--container">
          <div class="panel--content">
            <MessageDetails />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import MessageDetails from '@/components/messages/MessageDetails.vue'

export default {
  name: 'Messages',
  components: {
    MessageDetails,
  },
  data() {
    return {
      showPanelLeft: true,
      showPanelCenter: false,
      showPanelRight: false,
      windowWidth: window.innerWidth,
      activeThread: null,
    }
  },
  mounted() {
    this.onResize()
    this.$nextTick(() => {
      window.addEventListener('resize', this.onResize)
    })
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.onResize)
  },
  methods: {
    onResize() {
      this.windowWidth = window.innerWidth

      this.showPanelLeft =
        !this.activeThread || this.windowWidth >= 768 ? true : false
      this.showPanelCenter =
        this.activeThread || this.windowWidth >= 768 ? true : false
      this.showPanelRight = this.windowWidth >= 1280 ? true : false
    },
    onPanelLeftToggle() {
      this.activeThread = null
      if (this.windowWidth < 768) {
        this.showPanelCenter = false
      }
      this.showPanelLeft = !this.showPanelLeft
    },
    onPanelCenterToggle() {
      if (this.windowWidth < 768) {
        this.showPanelLeft = false
      }

      // Todo set activeThread
      this.activeThread = { message: 'HELLO' }

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
  },
}
</script>

<style lang="sass" scoped>
.panel--left,
.panel--center,
.panel--right
  transition: all .25s
  opacity: 1
  pointer-events: auto
  @apply flex flex-col
  &.hide
    flex: 0 1 0%
    width: 0
    opacity: 0
    pointer-events: none
  .panel--container
    @apply flex flex-col overflow-y-auto

.panel--left
  flex: 1 1 0%
  @screen lg
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
      min-height: 3000px
      background: linear-gradient(#e66465, #9198e5)

.panel--right
  width: 100%
  @screen md
    width: 375px
    @apply flex-none
</style>
