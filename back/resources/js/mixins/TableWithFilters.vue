<script>
import _ from 'lodash'

export default {
  name: 'TableWithFilters',
  data() {
    return {
      query: {},
      totalRows: 0,
      fromRow: 0,
      toRow: 0,
      showFilters: false,
    }
  },
  computed: {
    activeFilters() {
      let count = 0
      Object.entries(this.query).forEach(([key, value]) => {
        if (key != 'page' && key != 'filter[search]') {
          if (
            (!_.isEmpty(value) && value) ||
            _.isBoolean(value) ||
            _.isNumber(value)
          ) {
            count++
          }
        }
      })
      return count
    },
  },
  created() {
    this.query = { ...this.$route.query }
    this.tableData = this.fetchDatas()
    this.showFilters = this.activeFilters > 0 ? true : false
  },
  methods: {
    onPageChange(page) {
      this.$router.push({
        path: this.$router.history.current.fullpath,
        query: { ...this.query, page: page },
      })
    },
    onFilterChange(filter) {
      this.$router.push({
        path: this.$router.history.current.path,
        query: {
          ...this.query,
          [`filter[${filter.name}]`]: filter.value,
          page: 1,
        },
      })
    },
    fetchDatas() {
      this.loading = true
      this.fetchRows(this.query)
        .then((response) => {
          // console.log('fetchDatas', response.data)
          this.loading = false
          this.totalRows = response.data.total
          this.fromRow = response.data.from
          this.toRow = response.data.to
          this.tableData = response.data.data
        })
        .catch((error) => {
          console.log(error)
          this.loading = false
        })
    },
  },
}
</script>
