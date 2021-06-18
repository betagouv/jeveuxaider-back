<template>
  <div class="overflow-hidden">
    <hr class="opacity-25" />

    <section class="section-subscribe bg-purple-pale relative">
      <div class="relative z-10 container mx-auto px-4">
        <div class="pt-16 pb-8 lg:py-28">
          <h2 class="max-w-2xl mb-12">
            <p class="uppercase text-red-500 font-extrabold text-sm mb-4">
              Chacun pour tous
            </p>

            <p
              class="
                text-4xl
                xl:text-5xl
                leading-none
                font-extrabold
                tracking-px
                lg:tracking-2px
                text-primary
              "
            >
              Suivez toute l'actualité du bénévolat
              {{ territoire.suffix_title }}
            </p>
          </h2>

          <p class="md:text-xl text-gray-700 sm:max-w-md md:max-w-lg mb-8">
            Trouvez des missions en quelques clics et devenez bénévole près de
            chez vous ou à distance
          </p>

          <div class="rounded-xl px-8 py-4 bg-white shadow-lg max-w-3xl">
            <div class="flex flex-wrap sm:flex-no-wrap gap-1 sm:gap-8">
              <div class="input-wrapper relative">
                <el-input
                  v-model="email"
                  type="email"
                  placeholder="Renseignez votre e-mail"
                  class="w-full"
                />
              </div>

              <button
                class="
                  w-full
                  sm:w-auto
                  sm:flex-none
                  flex
                  items-center
                  justify-center
                  border border-transparent
                  rounded-xl
                  text-white
                  focus:outline-none
                  focus:shadow-outline
                  transition
                  duration-150
                  hover:scale-105
                  transform
                  will-change-transform
                  ease-in-out
                  font-bold
                  text-xl
                  px-5
                  py-4
                  sm:px-8
                  sm:py-4
                  sm:pb-5
                  leading-none
                "
                style="background-color: #09c19d"
                @click="onSubmit"
              >
                Je m'engage
              </button>
            </div>
          </div>
        </div>
      </div>

      <div
        class="
          max-w-xs
          ml-auto
          xl:max-w-full
          h-full
          relative
          sm:absolute
          right-0
          bottom-0
          sm:pt-36
          lg:pt-8
          z-0
        "
      >
        <img
          src="/images/subscribe-pattern.svg"
          class="
            ml-auto
            w-full
            h-full
            object-contain object-right-bottom
            absolute
          "
        />
        <img
          src="/images/subscribe-illustration.png"
          srcset="/images/subscribe-illustration@2x.png 2x"
          :alt="`Suivez toute l'actualité du bénévolat
              ${territoire.suffix_title}`"
          class="
            ml-auto
            w-full
            h-full
            object-contain object-right-bottom
            relative
          "
        />
      </div>
    </section>
  </div>
</template>

<script>
export default {
  props: {
    territoire: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      email: '',
    }
  },
  methods: {
    onSubmit() {
      console.log('@todo')
    },
  },
}
</script>

<style lang="sass" scoped>
.input-wrapper
  @apply w-full
  &::after
      content: "E-MAIL"
      position: absolute
      pointer-events: none
      left: 0
      top: 12px
      font-size: 12px
      color: black
      letter-spacing: -0.1px
      line-height: 18px
      font-weight: bold
  .el-input
    position: relative
    top: 22px
    margin-bottom: 25px
    left: -15px
    ::v-deep
      input
        border: none !important
</style>
