export default {
  methods: {
    onUpdatedRow(row) {
      const foundIndex = this.tableData.findIndex((el) => el.id === row.id)
      this.tableData.splice(foundIndex, 1, row)
    },
    onDeletedRow(row) {
      const foundIndex = this.tableData.findIndex((el) => el.id === row.id)
      this.tableData.splice(foundIndex, 1)
    },
    onClickedRow(row, column) {
      this.$store.commit('volet/show', { ...row })
    },
  },
}
