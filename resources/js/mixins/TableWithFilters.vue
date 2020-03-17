<script>
export default {
  name: "TableWithFilters",
  data() {
    return {
      query: {},
      totalRows: 0,
      fromRow: 0,
      toRow: 0
    };
  },
  created() {
    this.query = { ...this.$router.history.current.query };
    this.tableData = this.fetchDatas();
  },
  methods: {
    onPageChange(page) {
      this.$router.push({
        path: this.$router.history.current.fullpath,
        query: { ...this.query, page: page }
      });
    },
    onFilterChange(filter) {
      this.$router.push({
        path: this.$router.history.current.path,
        query: {
          ...this.query,
          [`filter[${filter.name}]`]: filter.value,
          page: 1
        }
      });
    },
    fetchDatas() {
      this.loading = true;
      this.fetchRows(this.query)
        .then(response => {
          this.loading = false;
          this.totalRows = response.data.total;
          this.fromRow = response.data.from;
          this.toRow = response.data.to;
          this.tableData = response.data.data;
        })
        .catch(error => {
          console.log(error);
          this.loading = false;
        });
    }
  }
};
</script>
