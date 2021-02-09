<template>
  <div class="flex flex-col lg:flex-row switch-items">
    <div
      v-for="(item, index) in radios"
      :key="item.value"
      class="flex item w-full lg:w-auto"
    >
      <el-radio
        :value="radio"
        :label="item.value"
        class="flex items-center w-full lg:h-full py-3 px-5 lg:py-6 lg:px-10"
        :class="[
          { 'opacity-75': radio && radio != item.value },
          `el-radio-${index}`,
          radio && radio == item.value && color
            ? `text-${color}`
            : 'text-white',
        ]"
        @click.native.prevent="onClick(item.value)"
      >
        <span>{{ item.label }}</span>
      </el-radio>

      <div
        v-if="index == 0 && radio == 'Mission en présentiel'"
        class="w-full relative flex items-stretch bg-white rounded-tr-lg lg:rounded-tr-none"
      >
        <AlgoliaPlacesInput
          ref="alogoliaInput"
          :value="initialPlace"
          selector="algolia-lieu-switcher--places-input"
          class="zipcode"
          :label="false"
          :description="false"
          type="city"
          :limit="4"
          :templates="templatesPlaces"
          placeholder="Ex: 75001"
          @selected="$emit('selected', $event)"
          @clear="$emit('clear')"
        />
        <div class="radius pr-2">
          <AlgoliaRadiusFilter
            :initial-value="aroundRadius"
            @selected="$emit('change-radius', $event)"
          />
        </div>
      </div>
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
  props: {
    initialType: {
      type: [String, Boolean],
      default: null,
    },
    initialPlace: {
      type: [String, Boolean],
      default: undefined,
    },
    aroundRadius: {
      type: [Number, String],
      default: 'all',
    },
    color: {
      type: [String, Boolean],
      default: null,
    },
  },
  data() {
    return {
      radio: this.initialType,
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
      this.$emit('click', val)

      if (this.radio == val) {
        this.radio = null
        this.$emit('typeRemoved')
      } else {
        this.radio = val
        this.$emit('typeChanged', val)
      }

      this.$nextTick(() => {
        if (this.radio == 'Mission en présentiel') {
          document.querySelector(`#algolia-lieu-switcher--places-input`).focus()
        }
      })
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep
  .el-radio
    @apply m-0 border
    border-color: #504DB2
    color: #504DB2
    @screen lg
      max-height: 70px
    &.is-checked
      @apply bg-white border-white
      .el-radio__label
        color: #070192
        @apply font-bold
      .el-radio__inner
        border-color: #E6EAF5
        background: #E6EAF5
      &.el-radio-0
        @apply rounded-tr-none
        width: 30px!important
        @screen lg
         width: 100%
        .el-radio__label
          @apply hidden
    &.el-radio-0
      @apply rounded-t-lg
      @screen lg
        @apply rounded-t-none rounded-l-lg
    &.el-radio-1
      @apply rounded-b-lg border-t-0
      @screen lg
        @apply rounded-b-none rounded-r-lg border-t border-l-0
    .el-radio__label
      @apply text-base
      color: #817EE2
      padding-left: 15px
    .el-radio__inner
      width: 20px
      height: 20px
      border-color: #504DB2
      background: #504DB2
      box-shadow: none !important
      &::after
        background: url(/images/check-primary.svg)
        width: 11px
        height: 100%
        background-repeat: no-repeat
        background-position: center
        transform: translate(-50%, -50%) scale(1)

.radius
  @apply border-l border-dashed border-gray-200
  position: relative
  &::after
    content: "Rayon"
    position: absolute
    pointer-events: none
    left: 15px
    top: 5px
    font-size: 12px
    color: #908E8E
    letter-spacing: -0.1px
    line-height: 18px
    @screen lg
      top: 15px
  ::v-deep
    .el-select
      @apply m-0 relative
      top: 6px
      @screen lg
        top: 15px
    input
      width: 80px
      border: none !important
      background: none !important
      @apply pl-2 text-black font-bold
      @screen lg
        width: 100px
    .el-input__suffix
      right: 10px
      top: -5px
      @screen lg
        top: -8px

.zipcode
  position: relative
  height: 56px
  @apply m-0 flex-1
  @screen lg
    height: 70px
    @apply mb-0
  &::after
    content: "Votre code postal"
    position: absolute
    pointer-events: none
    left: 15px
    top: 5px
    font-size: 12px
    color: #908E8E
    letter-spacing: -0.1px
    line-height: 18px
    @screen lg
      top: 15px
  ::v-deep
    .ap-dropdown-menu
      border-radius: 8px
    .ap-suggestion
      padding: 5 15px
      line-height: normal
      height: inherit
    .ap-input
      width: 100%
      border: 1px solid white
      color: black
      font-weight: bold
      background-color: transparent
      border: none
      top: 14px
      @apply truncate
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
        top: 15px
        right: 0px
        @screen lg
          top: 22px
    .ap-icon-clear
      width: 20px
      height: 20px
      margin: auto
      display: flex
      align-items: center
      svg
        right: 4px
    .algolia-places
      height: 100%

.chevron
  left: -12px
  @apply absolute top-0 bottom-0 m-auto
</style>
