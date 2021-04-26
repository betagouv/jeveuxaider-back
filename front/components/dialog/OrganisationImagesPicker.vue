<template>
  <el-dialog
    :close-on-click-modal="false"
    title="Sélectionnez votre image"
    width="100%"
    :visible="isVisible"
    style="max-width: 600px; margin: auto; overflow: hidden"
    @open="onOpenDialog"
    @close="$emit('close')"
  >
    <div class="max-h-350 overflow-y-auto custom-scrollbar">
      <div class="flex flex-wrap">
        <img
          v-for="imageName in availableImages"
          :key="`${imageName}`"
          :src="`/images/domaines/${imageName}.jpg`"
          :srcset="`/images/domaines/${imageName}@2x.jpg 2x`"
          class="thumbnail w-32 sm:w-64"
          :class="[
            {
              selected: selectedImage == `${imageName}`,
            },
          ]"
          @click="selectedImage = imageName"
        />
      </div>
    </div>
    <span slot="footer" class="dialog-footer">
      <!-- <el-button @click="$emit('close')"> Annuler </el-button> -->
      <el-button type="primary" @click="onSubmit"
        >Choisir cette image</el-button
      >
    </span>
  </el-dialog>
</template>

<script>
export default {
  props: {
    domaines: {
      type: Array,
      required: true,
    },
    initialImage: {
      type: String,
      required: true,
    },
    isVisible: {
      type: Boolean,
      required: true,
    },
  },
  data() {
    return {
      selectedImage: this.initialImage,
    }
  },
  computed: {
    availableImages() {
      const ids = []
      this.domaines.forEach((domaine) => {
        for (let index = 1; index < 3; index++) {
          ids.push(`${domaine}_${index}`)
        }
      })
      return ids
    },
  },
  methods: {
    onOpenDialog() {
      this.selectedImage = this.initialImage
    },
    onSubmit() {
      this.$emit('picked', this.selectedImage)
      this.$emit('close')
    },
  },
}
</script>

<style lang="sass" scoped>
.thumbnail
  transition: all .25s
  @apply p-1 bg-white border border-transparent min-w-0 m-2 w-full h-auto cursor-pointer
  &.selected
    @apply border-primary
</style>
