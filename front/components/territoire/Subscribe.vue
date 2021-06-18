<template>
  <div class="overflow-hidden">
    <hr class="opacity-25" />

    <section class="section-subscribe bg-purple-pale relative">
      <div class="relative container mx-auto px-4">
        <div class="py-16 lg:py-28">
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

          <p class="text-xl text-gray-700 max-w-lg mb-8">
            Trouvez des missions en quelques clics et devenez bénévole près de
            chez vous ou à distance
          </p>

          <div>@todo</div>
        </div>
      </div>

      <div
        class="
          max-w-xs
          ml-auto
          xl:max-w-full
          h-full
          relative
          lg:absolute
          right-0
          bottom-0
        "
      >
        <img
          src="/images/subscribe-pattern.svg"
          class="
            ml-auto
            lg:w-full
            lg:h-full
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
            lg:w-full
            lg:h-full
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
}
</script>

<style lang="sass" scoped></style>
