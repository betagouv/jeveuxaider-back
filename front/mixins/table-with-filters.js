import { isEmpty, isBoolean, isNumber } from 'lodash'

export default {
  watch: {
    '$route.query': 'urlQuerytHasChanged',
  },
  data() {
    return {
      query: this.$route.query,
      totalRows: 0,
      fromRow: 0,
      toRow: 0,
      showFilters: false,
      tableData: [],
    }
  },
  created() {
    if (this.activeFilters > 0) {
      this.showFilters = true
    }
  },
  computed: {
    activeFilters() {
      let count = 0
      if (this.query) {
        Object.entries(this.query).forEach(([key, value]) => {
          if (!['page', 'filter[search]', 'filter[of_reseau]'].includes(key)) {
            if (
              (!isEmpty(value) && value) ||
              isBoolean(value) ||
              isNumber(value)
            ) {
              count++
            }
          }
        })
      }

      return count
    },
  },
  methods: {
    urlQuerytHasChanged() {
      this.query = this.$route.query
      this.$fetch()
      document.getElementById('main').scrollIntoView()
    },
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
    isFilterActive(name, value) {
      return this.query[name] && this.query[name] == value
    },
  },
}
