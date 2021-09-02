<template>
  <div class="component--image-field" :class="[componentClasses]">
    <slot name="label">
      <div :class="labelClass">{{ label }}</div>
    </slot>

    <slot name="description">
      <item-description container-class="mb-3">
        <span class="whitespace-pre">{{ renderDescription.join('\n') }}</span>
      </item-description>
    </slot>

    <div v-show="imgPreview">
      <div
        class="preview-area"
        :class="[previewAreaClass]"
        :style="{
          '--preview-area__width': previewWidth,
        }"
      >
        <img :src="imgPreview" :alt="alt" />
      </div>

      <div class="actions mt-4">
        <slot v-if="crop" name="button-crop" :events="{ setDialogCropVisible }">
          <el-button
            type="secondary"
            @click.prevent="setDialogCropVisible(true)"
          >
            Recadrer
          </el-button>
        </slot>

        <slot :events="{ onDelete }" name="button-delete">
          <el-button
            type="danger"
            icon="el-icon-delete"
            :loading="loadingDelete"
            @click.prevent="onDelete()"
          >
            Supprimer
          </el-button>
        </slot>
      </div>

      <el-dialog
        v-if="crop"
        title="Recadrer"
        :visible.sync="dialogCropVisible"
        :close-on-click-modal="false"
        top="3rem"
        @close="onModalClose"
      >
        <VueCropper
          v-if="dialogCropVisible"
          ref="cropper"
          :check-orientation="false"
          cross-origin="anonymous"
          crossorigin="anonymous"
          :src="imgSrc ? imgSrc : field ? field.original : null"
          :aspect-ratio="aspectRatio"
          :zoomable="false"
          :movable="false"
          :zoom-on-touch="false"
          :zoom-on-wheel="false"
          :auto-crop-area="1"
          :view-mode="2"
          preview=".preview"
          @ready="onCropperReady"
          @cropmove="ensureMinDimensions"
          @hook:mounted="onCropperMounted"
        />

        <span slot="footer" class="dialog-footer">
          <el-button @click="onReset()">Réinitialiser</el-button>
          <el-button @click="dialogCropVisible = false">Annuler</el-button>
          <el-button type="primary" :loading="loadingCrop" @click="onCrop()"
            >Valider</el-button
          >
        </span>
      </el-dialog>
    </div>
    <div v-show="!imgPreview">
      <el-upload
        class="upload-demo"
        drag
        action=""
        :show-file-list="false"
        :auto-upload="false"
        :on-change="onSelectFile"
        :accept="acceptedFiles"
      >
        <slot name="dragZone">
          <i class="el-icon-upload" />
          <div class="el-upload__text">
            Glissez votre image ou <br /><em
              >cliquez ici pour la sélectionner</em
            >
          </div>
        </slot>
      </el-upload>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    alt: {
      type: String,
      default: 'Image',
    },
    minWidth: {
      type: Number,
      default: null,
    },
    minHeight: {
      type: Number,
      default: null,
    },
    maxSize: {
      type: Number,
      default: 4000000, // 4 MB
    },
    crop: {
      type: Boolean,
      default: true,
    },
    aspectRatio: {
      type: Number,
      default: 1 / 1, // 0 to disable
    },
    field: {
      type: [Object, String],
      default: null,
    },
    fieldName: {
      type: String,
      default: null,
    },
    label: {
      type: String,
      required: true,
    },
    labelClass: {
      type: String,
      default: 'el-form-item__label',
    },
    previewWidth: {
      type: String,
      default: '300px',
    },
    model: {
      type: String,
      required: true,
    },
    modelId: {
      type: Number,
      default: null,
    },
    previewAreaClass: {
      type: String,
      default: null,
    },
    acceptedFiles: {
      type: String,
      default: null,
    },
    description: {
      type: String,
      default: null,
    },
    componentClasses: {
      type: String,
      default: 'mb-6',
    },
  },
  data() {
    return {
      img: null,
      imgSrc: '',
      cropImgSrc: '',
      dialogCropVisible: false,
      loadingDelete: false,
      loadingCrop: false,
      initialField: this.field ? this.field : null,
      cropData: null,
    }
  },
  computed: {
    imgPreview() {
      return this.cropImgSrc
        ? this.cropImgSrc
        : this.imgSrc
        ? this.imgSrc
        : this.initialField && this.initialField.thumb
        ? this.initialField.thumb
        : this.initialField && this.initialField.original
        ? this.initialField.original
        : this.initialField
    },
    renderDescription() {
      const description = []
      if (this.description) {
        description.push(this.description)
      }
      if (this.minWidth && this.minHeight) {
        description.push(
          `Résolution minimale: ${this.minWidth} par ${this.minHeight} pixels`
        )
      } else if (this.minWidth) {
        description.push(`Largeur minimale: ${this.minWidth} pixels`)
      } else if (this.minHeight) {
        description.push(`Hauteur minimale: ${this.minHeight} pixels`)
      }

      description.push(
        `Taille maximale: ${this.$options.filters.prettyBytes(this.maxSize)}`
      )

      return description
    },
  },
  methods: {
    onSelectFile(file) {
      if (!file.raw) {
        return false
      }
      if (this.maxSize && file.size > this.maxSize) {
        this.$message({
          message: `La taille ne doit pas dépasser ${this.$options.filters.prettyBytes(
            this.maxSize
          )}`,
          type: 'error',
        })
        return false
      }
      if (!file.raw.type.includes('image/')) {
        this.$message({
          message: 'Veuillez sélectionner une image',
          type: 'error',
        })
        return false
      }

      if (typeof FileReader === 'function') {
        const reader = new FileReader()
        reader.onload = (readerEvent) => {
          // Validate the File Height and Width.
          const image = new Image()
          image.src = readerEvent.target.result
          image.onload = (imageEvent) => {
            let height = 0
            let width = 0
            if (imageEvent.path) {
              height = imageEvent.path[0].height
              width = imageEvent.path[0].width
            } else {
              height = imageEvent.originalTarget.height
              width = imageEvent.originalTarget.width
            }
            if (
              this.minHeight &&
              this.minWidth &&
              (height < this.minHeight || width < this.minWidth)
            ) {
              this.$message({
                message: `Résolution minimale: ${this.minWidth} par ${this.minHeight} pixels`,
                type: 'error',
              })
            } else {
              this.img = file.raw
              this.imgSrc = readerEvent.target.result
              if (this.$refs.cropper) {
                this.$refs.cropper.replace(this.imgSrc)
              }
              this.$emit('add-or-crop', {
                blob: this.img,
                fieldName: this.fieldName,
              })
            }
          }
        }
        reader.readAsDataURL(file.raw)
      } else {
        console.log('FileReader API not supported')
      }
    },
    onCrop() {
      this.loadingCrop = true
      if (!this.img) {
        fetch(this.initialField.original)
          .then((res) => res.blob())
          .then((blob) => {
            this.img = blob
            this.handleCrop()
          })
      } else {
        this.handleCrop()
      }
    },
    handleCrop() {
      this.cropImgSrc = this.$refs.cropper.getCroppedCanvas().toDataURL()
      this.loadingCrop = false
      this.dialogCropVisible = false
      const cropSettings = this.$refs.cropper
        ? this.$refs.cropper.getData()
        : null
      this.$emit('add-or-crop', {
        blob: this.img,
        cropSettings,
        fieldName: this.fieldName,
      })
    },
    onReset() {
      this.$refs.cropper.reset()
    },
    onDelete() {
      this.loadingDelete = true
      if (this.modelId) {
        this.$api
          .deleteImage(this.modelId, this.model, this.fieldName)
          .then(() => {
            this.deleteImage()
          })
      } else {
        this.deleteImage()
      }
    },
    ensureMinDimensions(event) {
      if (this.minWidth || this.minHeight) {
        const data = this.$refs.cropper.getData()
        if (data.width < this.minWidth || data.height < this.minHeight) {
          event.preventDefault()
          if (data.width < this.minWidth) {
            data.width = this.minWidth
          }
          if (data.height < this.minHeight) {
            data.height = this.minHeight
          }
          this.$refs.cropper.setData(data)
        }
      }
    },
    deleteImage() {
      this.initialField = null
      this.img = null
      this.imgSrc = ''
      this.cropImgSrc = ''
      this.loadingDelete = false
      this.cropData = null
      this.$emit('delete', { fieldName: this.fieldName })
    },
    onModalClose() {
      this.cropData = this.$refs.cropper.getData()
    },
    onCropperReady() {
      if (this.cropData) {
        this.$refs.cropper.setData(this.cropData)
      }
    },
    onCropperMounted() {
      // HACK - data-not-lazy to prevent img not loaded
      this.$refs.cropper.$refs.img.setAttribute('data-not-lazy', '')
      const src = this.imgSrc
        ? this.imgSrc
        : this.field
        ? this.field.original
        : null
      this.$refs.cropper.replace(src)
    },
    setDialogCropVisible(value) {
      this.dialogCropVisible = value
    },
  },
}
</script>

<style lang="postcss" scoped>
.preview-area {
  width: var(--preview-area__width);
}

::v-deep .el-dialog {
  max-width: calc(100% - 2rem);
  width: 100%;
  @screen sm {
    width: 75%;
    max-width: 680px;
  }
  @screen lg {
    width: 50%;
  }
}
</style>
