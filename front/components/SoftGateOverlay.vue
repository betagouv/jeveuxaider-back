<template>
  <div class="fixed inset-0 w-full h-full z-50">
    <div
      id="soft-gate"
      class="w-full h-full flex flex-col items-center justify-center"
    >
      <div class="flex flex-col w-full h-full px-4">
        <button
          class="p-4 -mr-4 lg:m-0 lg:p-8 cursor-pointer ml-auto lg:absolute lg:right-0"
          @click="onClose"
        >
          <img src="/images/close-white.svg" alt="Fermer" width="24px" />
        </button>

        <div
          v-scroll-lock="true"
          class="overflow-y-auto flex-1 flex flex-col lg:justify-center"
        >
          <div class="pb-32 lg:pb-0">
            <div class="text-center text-white text-lg">#ChacunPourTous</div>
            <div class="title text-center text-white font-extrabold mb-4">
              <template v-if="step != 'share'"
                >Participez à cette mission</template
              >
              <template v-else>Merci pour votre engagement</template>
            </div>
            <div
              class="bg-gray-100 rounded-lg max-w-full lg:max-w-xl mx-auto p-6 lg:p-10"
            >
              <SoftGateEmail
                v-if="step == 'email'"
                @login="goToLogin"
                @register="goToRegister"
              />
              <SoftGateLogin
                v-if="step == 'login'"
                :datas="datas"
                @next="step = 'participate'"
              />
              <SoftGateRegister
                v-if="step == 'register'"
                :datas="datas"
                @next="step = 'participate'"
              />
              <SoftGateParticipate
                v-if="step == 'participate'"
                @next="step = 'share'"
              />
              <SoftGateShare v-if="step == 'share'" @next="onClose" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SoftGateOverlay',
  data() {
    return {
      step: 'email',
      datas: null,
    }
  },
  created() {
    if (this.$store.getters.isLogged) {
      this.step = 'participate'
    }
  },
  methods: {
    goToLogin(datas) {
      this.step = 'login'
      this.datas = datas
    },
    goToRegister(datas) {
      this.step = 'register'
      this.datas = datas
    },
    onClose() {
      this.$store.commit('setMissionSelected', null)
      this.$emit('closed')
      if (this.step == 'share') {
        this.$router.push('/user/missions')
      }
    },
  },
}
</script>

<style lang="sass" scoped>
#soft-gate
  background-color: rgba(25, 22, 130, .95)
  .title
    font-size: 24px
    @screen lg
      font-size: 50px
      letter-spacing: -1px
</style>
