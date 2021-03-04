import { isEmpty, isBoolean, isNumber } from 'lodash'

export default {
  watch: {
    '$route.query': '$fetch',
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
  methods: {
    onPageChange(page) {
      this.$router.push({
        path: this.$router.history.current.fullpath,
        query: { ...this.query, page },
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
  },
}
