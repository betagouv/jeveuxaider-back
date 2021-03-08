export default {
  name: 'TableWithVolet',
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
      // Hack pour éviter que le volet s'affiche quand on clique sur un boutton
      if (!['Actions', 'Statut'].includes(column.label)) {
        this.$store.commit('volet/show', { ...row })
      }
    },
  },
}
