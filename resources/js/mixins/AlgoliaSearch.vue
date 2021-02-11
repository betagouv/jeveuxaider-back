<script>
import qs from 'qs'
import _ from 'lodash'
import algoliasearch from 'algoliasearch/lite'
import 'instantsearch.css/themes/algolia-min.css'

export default {
  name: 'AlgoliaSearchMixin',
  props: {
    initialGeoSearch: {
      type: [Object, Boolean],
      default: null,
    },
  },
  data() {
    return {
      searchClient: algoliasearch(
        process.env.MIX_ALGOLIA_APP_ID,
        process.env.MIX_ALGOLIA_SEARCH_KEY
      ),
      routeState: null,
      loading: true,
    }
  },
  computed: {
    indexName() {
      return process.env.MIX_ALGOLIA_INDEX
    },
    routeStateWithIndex() {
      return {
        [this.indexName]: this.routeState,
      }
    },
  },
  created() {
    this.readUrl()

    if (this.initialGeoSearch && !this.routeState.aroundLatLng) {
      this.$set(this, 'routeState', {
        ...this.routeState,
        ...this.initialGeoSearch,
      })
    }

    // See https://www.algolia.com/doc/guides/building-search-ui/troubleshooting/faq/js/#why-is-my-uistate-ignored
    setTimeout(() => {
      this.loading = false
    }, 250)
  },
  methods: {
    readUrl() {
      const routeState = qs.parse(window.location.search.substring(1))
      this.$set(this, 'routeState', routeState)
    },
    writeUrl() {
      if (this.writeTimeout) {
        this.writeTimeout.cancel()
      }
      this.writeTimeout = _.debounce(() => {
        window.history.pushState(
          this.routeState,
          '',
          `${window.location.pathname}${this.stringifyQuery(this.routeState)}`
        )
      }, 400)
      this.writeTimeout()
    },
    onQueryInput(refine, $event) {
      if (this.timeout) {
        this.timeout.cancel()
      }
      this.timeout = _.debounce(() => {
        refine($event)
        this.writeUrl()
      }, 400)
      this.timeout()
    },
    onQueryClear() {
      this.$delete(this.routeState, 'query')
    },
    onToggleFacet($event) {
      if ($event.active) {
        this.addFacet($event)
      } else {
        this.deleteFacet($event)
      }
      this.writeUrl()
    },
    onPlaceSelect($event) {
      this.$set(
        this.routeState,
        'aroundLatLng',
        `${$event.latlng.lat},${$event.latlng.lng}`
      )
      this.$set(this.routeState, 'place', $event.value)
      this.writeUrl()
    },
    onRadiusSelect(value) {
      this.$set(this.routeState, 'aroundRadius', `${value}`)
      this.writeUrl()
    },
    onPlaceClear() {
      this.$delete(this.routeState, 'aroundRadius')
      this.$delete(this.routeState, 'aroundLatLng')
      this.$delete(this.routeState, 'place')
      this.writeUrl()
    },
    addFacet($event) {
      if (!this.routeState.refinementList) {
        this.$set(this.routeState, 'refinementList', {})
      }
      const values = this.routeState.refinementList[$event.name]
        ? [...this.routeState.refinementList[$event.name], $event.value]
        : [$event.value]
      this.$set(this.routeState.refinementList, $event.name, values)
    },
    deleteFacet($event) {
      this.routeState.refinementList[$event.name].splice(
        this.routeState.refinementList[$event.name].findIndex((i) => {
          return i == $event.value
        }),
        1
      )
      if (this.routeState.refinementList[$event.name].length == 0) {
        this.$delete(this.routeState.refinementList, $event.name)
      }
    },
    stringifyQuery(query) {
      const string = qs.stringify(query)
      return string ? '?' + string : ''
    },
    onResetFilters(refine) {
      if (this.type) {
        let { type } = this.routeState.refinementList
        this.$set(this.routeState, 'refinementList', { type: type })
      } else {
        this.$delete(this.routeState, 'refinementList')
      }

      this.$delete(this.routeState, 'query')
      refine()
      this.writeUrl()
    },
  },
}
</script>
