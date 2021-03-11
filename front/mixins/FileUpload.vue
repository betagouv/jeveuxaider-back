<script>
export default {
  name: 'FileUpload',
  data() {
    return {
      file: null,
      fileMaxSize: 10000000, // 10 MB
      loadingDelete: false,
    }
  },
  computed: {},
  methods: {
    onDownloadFile(file) {
      window.open(file.url, '_blank')
    },
    onSelectFile(file) {
      if (!file.raw) {
        return false
      }

      if (file.size > this.fileMaxSize) {
        this.$message({
          message: `La taille ne doit pas dépasser ${this.$options.filters.prettyBytes(
            this.fileMaxSize
          )}`,
          type: 'error',
        })
        return false
      }

      this.file = file.raw
    },
    onDeleteFile(model) {
      this.loadingDelete = true
      this.$api.deleteFile(this.form.id, model).then(() => {
        this.form.file = null
        this.file = null
        this.loadingDelete = false
      })
    },
  },
}
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
