<template>
  <transition name="fade">
    <div v-if="showBanner" id="jdma" class="bg-[#070191]">
      <div class="container mx-auto py-3 px-3 sm:px-6 lg:px-8">
        <div class="pr-16 sm:text-center sm:px-16">
          <p class="font-medium text-white">
            <span class="md:hidden">
              Que pensez-vous de <b>JeVeuxAider.gouv.fr</b> ?
            </span>
            <span class="hidden md:inline">
              Que pensez-vous de <b>JeVeuxAider.gouv.fr</b> ?
            </span>
            <span class="block sm:ml-2 sm:inline-block">
              <span
                href="https://voxusagers.numerique.gouv.fr/Demarches/2603?&amp;view-mode=formulaire-avis&amp;nd_mode=en-ligne-enti%C3%A8rement&amp;nd_source=button&amp;key=e67467d2b689f586a1f53d9e03d92434"
                target="_blank"
                class="text-white font-bold underline cursor-pointer hover:text-[#c3ddfd]"
                @click="handleClick()"
              >
                Je donne mon avis →
              </span>
            </span>
          </p>
        </div>
        <div
          class="absolute inset-y-0 right-0 pt-1 pr-1 flex items-start sm:pt-1 sm:pr-2 sm:items-start"
        >
          <button
            type="button"
            class="flex p-2 rounded-md focus:text-indigo-500 transition"
            @click="handleClose()"
          >
            <svg
              class="h-6 w-6 text-white hover:text-[#c3ddfd]"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              ></path>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'JdmaBenevole',
  data() {
    return {
      showBanner: true,
    }
  },
  created() {},
  methods: {
    handleClick() {
      window.open(
        'https://voxusagers.numerique.gouv.fr/Demarches/2603?&amp;view-mode=formulaire-avis&amp;nd_mode=en-ligne-enti%C3%A8rement&amp;nd_source=button&amp;key=e67467d2b689f586a1f53d9e03d92434',
        '_blank'
      )
      this.showBanner = false
      this.$emit('clicked')
    },
    handleClose() {
      this.showBanner = false
      this.$emit('clicked')
    },
  },
}
</script>

<style lang="sass" scoped></style>
