<template>
  <div>
    <div class="text-center">
      <div
        class="text-2xl sm:text-[38px] sm:leading-tight font-bold text-primary tracking-[-1px]"
      >
        Votre témoignage a déjà été enregistré.
      </div>

      <div class="font-medium text-md sm:text-xl text-[#808080] mt-4">
        Vous ne pouvez plus l’éditer.
      </div>

      <div class="py-8">
        <div class="font-medium text-md sm:text-xl text-[#808080] mb-4">
          Trouvez dès maintenant votre prochaine mission
        </div>

        <button
          id="search"
          class="bg-[#30C48D] rounded-full text-white transition will-change hover:scale-105 outline-none focus:scale-105 focus-visible:ring-indigo px-6 py-4 uppercase font-extrabold tracking-wide text-sm max-w-[295px] w-full"
          @click="$store.commit('toggleSearchOverlay')"
        >
          Trouver une mission
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    steps() {
      return this.$store.getters['avis/steps']
    },
    lastStep() {
      return this.steps[this.steps.length - 1]
    },
  },
  created() {
    this.$store.commit('avis/setStep', this.lastStep)
  },
}
</script>
