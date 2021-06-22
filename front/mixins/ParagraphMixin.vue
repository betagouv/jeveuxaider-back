<script>
import uniqid from 'uniqid'

export default {
  methods: {
    onParagraphAddItem(field, newRow) {
      if (!this.form[field]) {
        this.$set(this.form, field, [])
      }

      this.form[field].push({ ...newRow, uuid: uniqid() })
    },
    onParagraphUpdateItem(field, event) {
      this.form[field].splice(event.index, 1, event.payload)
    },
    onParagraphRemoveItem(field, index) {
      this.$delete(this.form[field], index)
    },
    // paragraphItemsWithIds(field) {
    //   return this.form[field]
    //     ? this.form[field].map((obj) => ({
    //         ...obj,
    //         // uuid: uniqid(),
    //       }))
    //     : []
    // },
  },
}
</script>
