<script>
import qs from 'qs'
import _ from 'lodash'
import algoliasearch from 'algoliasearch/lite'
import 'instantsearch.css/themes/algolia-min.css'

export default {
  name: 'AlgoliaSearchMixin',
  props: {},
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
    routeStateWithIndex() {
      return {
        [process.env.MIX_ALGOLIA_INDEX]: this.routeState,
      }
    },
    indexName() {
      return process.env.MIX_ALGOLIA_INDEX
    },
  },
  created() {
    this.readUrl()
    this.loading = false
  },
  methods: {
    stringifyQuery(query) {
      const string = qs.stringify(query)
      return string ? '?' + string : ''
    },
    readUrl() {
      this.routeState = qs.parse(window.location.search.substring(1))
    },
    writeUrl() {
      console.log('WRITE')
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
      refine($event)
      this.writeUrl()
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
    onPlaceClear() {
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
  },
}
</script>
