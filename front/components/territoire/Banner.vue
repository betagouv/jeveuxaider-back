<template>
  <div>
    <section class="section-banner relative">
      <img
        v-if="banner"
        :src="banner"
        :alt="`Bénévolat ${territoire.name}`"
        class="absolute object-cover object-center w-full h-full"
      />
      <div class="banner-gradient absolute inset-0" style=""></div>

      <div class="relative">
        <Breadcrumb theme="transparent" :items="breadcrumb" />

        <hr class="opacity-25" />

        <div class="container px-4 mx-auto">
          <div
            class="
              py-20
              flex flex-col
              lg:flex-row
              items-center
              gap-16
              lg:gap-32
            "
          >
            <div class="text-white">
              <h1
                class="
                  text-4xl
                  lg:text-5xl
                  leading-none
                  font-extrabold
                  text-white
                  tracking-px
                  lg:tracking-2px
                "
              >
                Devenez bénévole {{ territoire.suffix_title }}
              </h1>

              <hr class="border-t-4 w-10 my-8" />

              <h2 class="text-xl tracking-px">
                Trouvez une mission de
                <b class="font-extrabold">
                  bénévolat {{ territoire.suffix_title }}
                </b>
                parmi les missions actuellement disponibles et faites vivre
                l'engagement de chacun pour tous
              </h2>
            </div>

            <div class="flex-none rounded-xl overflow-hidden w-full sm:w-auto">
              <div class="bg-white px-8 sm:px-20 py-6">
                <p
                  class="
                    font-extrabold
                    text-2-5xl text-center
                    leading-tight
                    tracking-px
                  "
                >
                  Trouvez une nouvelle<br />cause à défendre
                </p>
              </div>

              <div class="bg-gray-100 px-10 py-6">
                <p
                  class="
                    text-center
                    uppercase
                    text-gray-700 text-xs
                    tracking-px
                    font-bold
                  "
                >
                  Choisissez un domaine d'action
                </p>

                <el-select
                  v-model="domaine"
                  placeholder="Choisissez un domaine d'action"
                  class="mb-4 rounded-xl"
                >
                  <el-option
                    v-for="item in domaines"
                    :key="item.id"
                    :label="item.name.fr"
                    :value="item.name.fr"
                  />
                </el-select>

                <button
                  class="
                    w-full
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
                    leading-none
                  "
                  style="background-color: #09c19d"
                  @click="onClick"
                >
                  Je veux aider
                </button>
              </div>
            </div>
          </div>
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
  data() {
    return {
      domaines: [],
      domaine: null,
    }
  },
  async fetch() {
    const domaines = await this.$api.fetchTags({ 'filter[type]': 'domaine' })
    this.domaines = domaines.data.data
    this.domaine = this.domaines[0].name.fr
  },
  computed: {
    banner() {
      return this.territoire.banner?.large
    },
    breadcrumb() {
      const breadcrumb = [
        { label: 'Missions de bénévolat', link: '/missions-benevolat' },
      ]
      if (this.territoire.type != 'department' && this.territoire.department) {
        const departmentName = this.$options.filters.departmentFromValue(
          this.territoire.department
        )
        if (departmentName != this.territoire.name) {
          breadcrumb.push({
            label: `Bénévolat ${departmentName}`,
            link: this.link(false, 'department'),
          })
        }
      }

      breadcrumb.push({
        label: `Bénévolat ${this.territoire.name}`,
        h1: true,
      })

      return breadcrumb
    },
  },
  methods: {
    link(withDomaine = true, type = this.territoire.type) {
      let link = null
      switch (type) {
        case 'department':
          link = `refinementList[department_name][0]=${this.$options.filters.fullDepartmentFromValue(
            this.territoire.department
          )}`
          break
        case 'city':
          link = `refinementList[type][0]=Mission en présentiel&aroundLatLng=${this.territoire.latitude},${this.territoire.longitude}&place=${this.territoire.zips[0]}&aroundRadius=35000`
          break
      }
      return withDomaine
        ? `/missions-benevolat?refinementList[domaines][0]=${this.domaine}&${link}`
        : `/missions-benevolat?${link}`
    },
    onClick() {
      this.$router.push(this.link())
    },
  },
}
</script>

<style lang="sass" scoped>
.banner-gradient
  background: linear-gradient(90deg, rgba(11, 8, 86, 0.7) 8.07%, rgba(17, 14, 82, 0.147) 100%)

.el-select
  ::v-deep
    .el-input__inner
      text-overflow: ellipsis
      height: 54px
      @apply rounded-xl outline-none text-black
    .el-input.is-focus .el-input__inner,
    .el-input__inner:focus
      border-width: 1px !important
</style>
