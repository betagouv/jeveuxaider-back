<script>
import qs from 'qs'
import { debounce } from 'lodash'
import 'instantsearch.css/themes/algolia-min.css'
import { createServerRootMixin } from 'vue-instantsearch'
import algoliasearch from 'algoliasearch/lite'

const searchClient = algoliasearch(
  process.env.algolia.appId,
  process.env.algolia.secret
)

export default {
  mixins: [
    createServerRootMixin({
      searchClient,
      indexName: process.env.algolia.index,
    }),
  ],
  serverPrefetch() {
    return this.instantsearch.findResultsState(this).then((algoliaState) => {
      this.$ssrContext.nuxt.algoliaState = algoliaState
    })
  },
  props: {
    initialGeoSearch: {
      type: [Object, Boolean],
      default: null,
    },
  },
  data() {
    return {
      searchClient,
      routeState: null,
      loading: true,
    }
  },
  computed: {
    indexName() {
      return this.$config.algolia.index
    },
    routeStateWithIndex() {
      return {
        [this.indexName]: this.routeState,
      }
    },
  },
  beforeMount() {
    const results =
      this.$nuxt.context.nuxtState.algoliaState || window.__NUXT__.algoliaState

    this.instantsearch.hydrate(results)

    // Remove the SSR state so it can't be applied again by mistake
    delete this.$nuxt.context.nuxtState.algoliaState
    delete window.__NUXT__.algoliaState
  },
  created() {
    this.readUrl()

    if (this.initialGeoSearch && !this.routeState.aroundLatLng) {
      this.$set(this, 'routeState', {
        ...this.routeState,
        ...this.initialGeoSearch,
      })
    }

    if (
      this.initialGeoSearch &&
      this.initialGeoSearch.refinementList &&
      this.initialGeoSearch.refinementList.type &&
      this.initialGeoSearch.refinementList.type[0] == 'Mission en présentiel'
    ) {
      this.$set(this.routeState, 'aroundRadius', this.defaultRadius)
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
      this.writeTimeout = debounce(() => {
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
      this.timeout = debounce(() => {
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
        const { type } = this.routeState.refinementList
        this.$set(this.routeState, 'refinementList', { type })
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
