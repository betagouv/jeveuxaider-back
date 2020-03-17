<script>
export default {
  name: "TableWithVolet",
  methods: {
    onUpdatedRow(row) {
      let foundIndex = this.tableData.findIndex(el => el.id === row.id);
      this.tableData.splice(foundIndex, 1, row);
    },
    onDeletedRow(row) {
      let foundIndex = this.tableData.findIndex(el => el.id === row.id);
      this.tableData.splice(foundIndex, 1);
    },
    onClickedRow(row, column) {
      // Hack pour éviter que le volet s'affiche quand on clique sur un boutton
      if (column.label != "Actions") {
        this.$store.commit("volet/show", { ...row });
      }
    }
  }
};
</script>
