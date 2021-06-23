<template>
  <div>
    <section class="section-associations bg-white">
      <div class="container mx-auto px-4 py-12">
        <div
          v-if="territoire.seo_recruit_title"
          class="grid gap-16 lg:grid-cols-2"
        >
          <div>
            <h2>
              <p class="uppercase text-red-500 font-extrabold text-sm mb-4">
                Associations {{ territoire.suffix_title }}
              </p>

              <p
                class="
                  text-3xl
                  lg:text-4xl
                  leading-none
                  font-extrabold
                  tracking-px
                  lg:tracking-2px
                "
              >
                {{ territoire.seo_recruit_title }}
              </p>
            </h2>

            <div class="mt-12 grid sm:grid-cols-2 gap-8">
              <img
                v-for="structure in structuresLogos"
                :key="structure.id"
                class="my-auto w-auto h-20"
                :alt="structure.name"
                :src="structure.logo"
              />
            </div>
          </div>

          <div v-html="territoire.seo_recruit_description" />
        </div>
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
  computed: {
    structuresLogos() {
      return this.territoire.structures
        .filter(({ logo }) => logo)
        .map(({ logo, id, name }) => {
          return { id, name, logo: logo.thumb || logo.original }
        })
    },
  },
}
</script>

<style lang="sass" scoped></style>
