<script>
import VueCropper from 'vue-cropperjs';
import 'cropperjs/dist/cropper.css';

export default {
  name: "Crop",
  components: { VueCropper },
  data() {
    return {
      img: null,
      imgSrc: '',
      imgMinWidth: 1024,
      imgMinHeight: 680,
      cropImg: null,
      cropImgSrc: '',
    };
  },
  methods: {
    selectFile(event) {
      if (!event.target.files[0]) {
        return;
      }
      if (event.target.files[0].type.indexOf('image/') === -1) {
        this.$message({
          message: "Veuillez sélectionner une image",
          type: "error"
        });
        return;
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
            if (height < this.imgMinHeight || width < this.imgMinWidth) {
              this.$message({
                message: `Résolution minimale: ${this.imgMinWidth} par ${this.imgMinHeight} pixels`,
                type: "error"
              });
            }
            else {
              this.img = event.target.files[0];
              this.imgSrc = readerEvent.target.result;
              this.$refs.cropper.replace(this.imgSrc);
            }
          };
        };
        reader.readAsDataURL(event.target.files[0]);
      }
      else {
        console.log("FileReader API not supported")
      }
    },
    cropImage() {
      this.cropImgSrc = this.$refs.cropper.getCroppedCanvas().toDataURL();
      fetch(this.cropImgSrc)
        .then(res => res.blob())
        .then(blob => this.cropImg = blob)
    },
    reset() {
      this.$refs.cropper.reset();
      this.cropImage()
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
    },
  }
};
</script>


<style lang="sass" scoped>
.preview-area
  width: 307px

.preview
  width: 100%
  height: calc(372px * (85 / 128))
  overflow: hidden

.crop-placeholder
  width: 100%
  height: 200px
  background: #ccc

.cropped-image img
  max-width: 100%
</style>
