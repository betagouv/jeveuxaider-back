<template>
  <div class="flex switch-items">
    <div
      v-for="(item, index) in radios"
      :key="item.value"
      class="item w-full lg:w-auto"
      :class="[{ 'lg:flex': index == 0 }]"
    >
      <el-radio
        :value="radio"
        :label="item.value"
        class="flex items-center lg:h-full py-6 px-10 transition"
        :class="[
          { 'opacity-25': radio && radio != item.value },
          `el-radio-${index}`,
        ]"
        @click.native.prevent="onClick(item.value)"
      >
        <span>{{ item.label }}</span>
      </el-radio>

      <transition name="fade-in">
        <div
          v-if="index == 0 && radio == 'Mission en présentiel'"
          class="relative flex bg-white"
        >
          <AlgoliaPlacesInput
            ref="alogoliaInput"
            selector="dialog-mission--places-input"
            class="zipcode"
            :label="false"
            :description="false"
            type="city"
            :limit="4"
            :templates="templatesPlaces"
            placeholder="Ex: 75001"
            @selected="onPlaceSelect($event)"
            @clear="onPlaceClear"
          />
          <div class="radius pr-2">
            <AlgoliaRadiusFilter />
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import AlgoliaPlacesInput from '@/components/AlgoliaPlacesInput'
import AlgoliaRadiusFilter from '@/components/AlgoliaRadiusFilter'

export default {
  name: 'AlgoliaLieuSwitcher',
  components: {
    AlgoliaPlacesInput,
    AlgoliaRadiusFilter,
  },
  data() {
    return {
      radio: null,
      radios: [
        { value: 'Mission en présentiel', label: 'Près de chez moi' },
        { value: 'Mission à distance', label: 'À distance' },
      ],
      templatesPlaces: {
        value: function (suggestion) {
          return `${suggestion.postcode} ${suggestion.name}`
        },
        suggestion: function (suggestion) {
          let details = [suggestion.county, suggestion.administrative]
          let detailsOutput = ''
          details.forEach((element) => {
            if (element) {
              detailsOutput += ` - <span>${element}</span>`
            }
          })
          return (
            `<div class="text-black font-bold">${suggestion.highlight.name}</div>` +
            `<div class="text-gray-800 text-xs font-light">` +
            `<span>${suggestion.postcode}</span>${detailsOutput}` +
            `</div>`
          )
        },
      },
    }
  },
  methods: {
    onClick(val) {
      if (this.radio == val) {
        this.radio = null
        //this.$delete(this.routeState, 'refinementList')
      } else {
        this.radio = val
        //this.$set(this.routeState, 'refinementList', { type: [this.radio] })
      }
      this.onPlaceClear()
      this.$nextTick(() => {
        if (this.radio == 'Mission en présentiel') {
          document.querySelector(`#dialog-mission--places-input`).focus()
        }
      })
    },
    onPlaceClear() {
      // this.$delete(this.routeState, 'aroundLatLng')
      // this.$delete(this.routeState, 'place')
    },
    reset() {
      // this.routeState = {}
      // this.radio = null
    },
  },
}
</script>

<style lang="sass" scoped>
.switch-items
  .item
    border-color: #504DB2
    color: #504DB2
    @apply border cursor-pointer
    &:first-child
      @apply rounded-l-lg
    &:last-child
      @apply rounded-r-lg
      border-left: none
    ::v-deep
      .el-radio__label
        @apply text-base font-extrabold
        color: #504DB2
      .el-radio__inner
        width: 20px
        height: 20px
        border-color: #F3F3F3
        background: #F3F3F3
        transition: all .25s
        box-shadow: none !important
        &::after
          background: url(/images/check-gray.svg)
          width: 11px
          height: 100%
          background-repeat: no-repeat
          background-position: center
          transform: translate(-50%, -50%) scale(1)
      .el-radio__input.is-checked
        .el-radio__inner
          border-color: #E6EAF5
          background: #E6EAF5
          &::after
            background: url(/images/check-primary.svg)
            background-repeat: no-repeat
            background-position: center
    ::v-deep
      .el-radio
        @apply m-0
        &.is-checked
          @apply bg-white border-white
          &.el-radio-0
            padding-right: 12px!important
            .el-radio__label
              @apply hidden
        &.el-radio-0
          @apply rounded-l-lg
        &.el-radio-1
          @apply rounded-r-lg

.radius
  @apply border-l border-dashed border-gray-200
  position: relative
  &::after
    content: "Rayon"
    position: absolute
    pointer-events: none
    left: 40px
    top: 10px
    font-size: 12px
    color: #908E8E
    letter-spacing: -0.1px
    line-height: 18px
    @screen lg
      left: 15px
  ::v-deep
    .el-select
      @apply m-0 relative
      top: 10px
    input
      width: 100px
      border: none !important
      @apply pl-2 text-black font-bold

.zipcode
  position: relative
  @apply m-0 mb-4
  @screen lg
    @apply mb-0
  &::after
    content: "Votre code postal"
    position: absolute
    pointer-events: none
    left: 40px
    top: 10px
    font-size: 12px
    color: #908E8E
    letter-spacing: -0.1px
    line-height: 18px
    @screen lg
      left: 15px
  ::v-deep
    .algolia-places
      @apply bg-white rounded-full
      @screen lg
        @apply rounded-none h-full
    .ap-dropdown-menu
      border-radius: 8px
    .ap-suggestion
      padding: 5 15px
      line-height: normal
      height: inherit
    .ap-input
      width: 100%
      height: 68px
      border: 1px solid white
      color: black
      font-weight: bold
      background-color: transparent
      border: none
      top: 10px
      @apply truncate py-6 px-10
      @screen lg
        width: 250px
        height: calc(100% - 10px)
        padding: 0 15px
    .ap-icon-pin
      position: relative
      pointer-events: none
      svg
        display: none
      &::after
        content: ""
        position: absolute
        width: 22px
        height: 23px
        background: url('/images/picker.svg')
        top: 22px
        right: 0px
    .ap-icon-clear
      width: 20px
      height: 20px
      margin: auto
      display: flex
      align-items: center
      svg
        right: 4px

.chevron
  left: -12px
  @apply absolute top-0 bottom-0 m-auto
</style>
