<script>
import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';
import {
  deleteImage
} from "@/api/app";

export default {
  name: "Crop",
  components: { VueCropper },
  data() {
    return {
      img: null,
      imgSrc: '',
      cropImgSrc: '',
      dialogCropVisible: false,
      loadingDelete: false,
      loadingCrop: false
    };
  },
  computed: {
    imgPreview() {
      return this.cropImgSrc ? this.cropImgSrc :
      this.imgSrc ? this.imgSrc :
      (this.form.image && this.form.image.thumb) ? this.form.image.thumb : this.form.image
    }
  },
  methods: {
    onSelectFile(file) {
      if (!file.raw) {
        return false;
      }
      if (this.imgMaxSize && file.size > this.imgMaxSize) {
        this.$message({
          message: `La taille ne doit pas dépasser ${this.$options.filters.prettyBytes(this.imgMaxSize)}`,
          type: "error"
        });
        return false;
      }
      if (file.raw.type.indexOf('image/') === -1) {
        this.$message({
          message: "Veuillez sélectionner une image",
          type: "error"
        });
        return false;
      }

      if (typeof FileReader === 'function') {
        const reader = new FileReader();
        reader.onload = (readerEvent) => {
          // Validate the File Height and Width.
          let image = new Image();
          image.src = readerEvent.target.result;
          image.onload = (imageEvent) => {
            var height = imageEvent.path[0].height;
            var width = imageEvent.path[0].width;
            if (this.imgMinHeight && this.imgMinWidth && (height < this.imgMinHeight || width < this.imgMinWidth)) {
              this.$message({
                message: `Résolution minimale: ${this.imgMinWidth} par ${this.imgMinHeight} pixels`,
                type: "error"
              });
            }
            else {
              this.img = file.raw;
              this.imgSrc = readerEvent.target.result;
              if (this.$refs.cropper) {
                this.$refs.cropper.replace(this.imgSrc);
              }
            }
          };
        };
        reader.readAsDataURL(file.raw);
      }
      else {
        console.log("FileReader API not supported")
      }
    },
    onCrop() {
      this.loadingCrop = true
      if (!this.img) {
        fetch(this.form.image.original)
          .then(res => res.blob())
          .then(blob => {
            this.img = blob
            this.crop()
          })
      }
      else {
        this.crop()
      }
    },
    crop() {
      this.cropImgSrc = this.$refs.cropper.getCroppedCanvas().toDataURL();
      this.loadingCrop = false
      this.dialogCropVisible = false
    },
    onReset() {
      this.$refs.cropper.reset();
    },
    onDelete() {
      this.loadingDelete = true
      deleteImage(this.form.id, this.model)
        .then(() => {
          this.form.image = null
          this.img = null
          this.imgSrc = ''
          this.cropImgSrc = ''
          this.loadingDelete = false
        })
    },
    ensureMinWidth(event) {
      let data = this.$refs.cropper.getData();
      if (data.width < this.imgMinWidth || data.height < this.imgMinHeight) {
        event.preventDefault();
        if (data.width < this.imgMinWidth) {
          data.width = this.imgMinWidth;
        }
        if (data.height < this.imgMinHeight) {
          data.height = this.imgMinHeight;
        }
        this.$refs.cropper.setData(data);
      }
    }
  }
};
</script>


<style lang="sass" scoped>
.preview-area
  width: 300px

.preview
  width: 100%
  height: calc(372px * (85 / 128))
  overflow: hidden

.cropped-image img
  max-width: 100%
</style>
