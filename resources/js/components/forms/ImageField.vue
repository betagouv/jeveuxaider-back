<template>
  <div class="mb-6">
    <div :class="labelClass">{{ label }}</div>
    <item-description container-class="mb-3">
      <span class="whitespace-pre">{{ renderDescription.join('\n') }}</span>
    </item-description>

    <div v-show="imgPreview">
      <div
        class="preview-area"
        :class="[previewAreaClass]"
        :style="{
          '--preview-area__width': previewWidth,
        }"
      >
        <img :src="imgPreview" alt="Cropped Image" />
      </div>

      <div class="actions mt-4">
        <el-button
          v-if="crop"
          type="secondary"
          @click.prevent="dialogCropVisible = true"
        >
          Recadrer
        </el-button>
        <el-button
          type="danger"
          icon="el-icon-delete"
          :loading="loadingDelete"
          @click.prevent="onDelete()"
        >
          Supprimer
        </el-button>
      </div>

      <el-dialog
        v-if="crop"
        title="Recadrer"
        :visible.sync="dialogCropVisible"
        width="680"
        :close-on-click-modal="false"
        @close="onModalClose"
      >
        <vue-cropper
          v-if="dialogCropVisible"
          ref="cropper"
          :src="imgSrc ? imgSrc : field ? field.original : null"
          :aspect-ratio="aspectRatio"
          :zoomable="false"
          :movable="false"
          :zoom-on-touch="false"
          :zoom-on-wheel="false"
          :auto-crop-area="1"
          :min-container-height="320"
          :min-container-width="640"
          :view-mode="2"
          preview=".preview"
          @ready="onCropperReady"
          @cropmove="ensureMinDimensions"
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
        <i class="el-icon-upload" />
        <div class="el-upload__text">
          Glissez votre image ou <br /><em>cliquez ici pour la sélectionner</em>
        </div>
      </el-upload>
    </div>
  </div>
</template>

<script>
import ItemDescription from '@/components/forms/ItemDescription'
import VueCropper from 'vue-cropperjs'
import 'cropperjs/dist/cropper.css'
import { deleteImage } from '@/api/app'

export default {
  name: 'ImageField',
  components: { ItemDescription, VueCropper },
  props: {
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
      type: Number,
      default: 300,
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
      let description = []
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
      if (file.raw.type.indexOf('image/') === -1) {
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
          let image = new Image()
          image.src = readerEvent.target.result
          image.onload = (imageEvent) => {
            var height = imageEvent.path[0].height
            var width = imageEvent.path[0].width
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
        cropSettings: cropSettings,
        fieldName: this.fieldName,
      })
    },
    onReset() {
      this.$refs.cropper.reset()
    },
    onDelete() {
      this.loadingDelete = true
      if (this.modelId) {
        deleteImage(this.modelId, this.model, this.fieldName).then(() => {
          this.deleteImage()
        })
      } else {
        this.deleteImage()
      }
    },
    ensureMinDimensions(event) {
      if (this.minWidth || this.minHeight) {
        let data = this.$refs.cropper.getData()
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
  },
}
</script>

<style lang="sass" scoped>
.preview-area
  width: var(--preview-area__width)
</style>
