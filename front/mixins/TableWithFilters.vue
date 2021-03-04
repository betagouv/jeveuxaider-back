<script>
import { isEmpty, isBoolean, isNumber } from 'lodash'

export default {
  beforeRouteUpdate(to, from, next) {
    this.query = { ...to.query }
    this.tableData = this.fetchDatas()
    this.showFilters = this.activeFilters > 0
    next()
  },
  data() {
    return {
      query: {},
      totalRows: 0,
      fromRow: 0,
      toRow: 0,
      showFilters: false,
      tableData: [],
    }
  },
  computed: {
    activeFilters() {
      let count = 0
      Object.entries(this.query).forEach(([key, value]) => {
        if (key != 'page' && key != 'filter[search]') {
          if (
            (!isEmpty(value) && value) ||
            isBoolean(value) ||
            isNumber(value)
          ) {
            count++
          }
        }
      })
      return count
    },
  },
  created() {
    // this.query = { ...this.$route.query }
    // this.tableData = this.fetchDatas()
    // this.showFilters = this.activeFilters > 0
  },
  mounted() {
    this.query = { ...this.$route.query }
    this.tableData = this.fetchDatas()
    this.showFilters = this.activeFilters > 0
  },
  methods: {
    onPageChange(page) {
      this.$router.push({
        path: this.$router.history.current.fullpath,
        query: { ...this.query, page },
      })
    },
    onFilterChange(filter) {
      console.log('toto')
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
