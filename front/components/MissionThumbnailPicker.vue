<template>
  <div>
    <label for="thumbnail" class="el-form-item__label"> Illustration </label>
    <div class="flex -m-2">
      <img
        v-for="index in 3"
        :key="`${domainId}_${index}`"
        :src="`/images/domaines/${domainId}_${index}.jpg`"
        :srcset="`/images/domaines/${domainId}_${index}@2x.jpg 2x`"
        class="thumbnail"
        :class="[
          {
            selected: value == `${domainId}_${index}`,
          },
        ]"
        @click="onThumbnailClick(index)"
      />
    </div>
  </div>
</template>

<script>
export default {
  name: 'MissionThumbnailPicker',
  props: {
    domainId: {
      type: Number,
      required: true,
    },
    value: {
      type: [String, null],
      default: null,
    },
  },
  data() {
    return {}
  },
  methods: {
    onThumbnailClick(index) {
      this.$emit('click', `${this.domainId}_${index}`)
    },
  },
}
</script>

<style lang="sass" scoped>
.thumbnail
  transition: all .25s
  @apply opacity-25 p-1 bg-white border border-transparent min-w-0 m-2 w-full h-auto cursor-pointer
  &.selected
    @apply opacity-100 border-primary
</style>
