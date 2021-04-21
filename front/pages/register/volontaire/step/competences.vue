<template>
  <div class="relative">
    <portal to="sidebar"
      ><div class="text-xl lg:text-2xl font-bold mb-6 lg:mb-12">
        Ça ne devrait pas prendre plus de 3 minutes 😉
      </div>
      <Steps :steps="steps"
    /></portal>
    <div class="mb-6 lg:mb-12 text-center text-white">
      <h1 class="text-4xl lg:text-5xl font-medium leading-12 mb-4">
        Dites-nous en plus<br />
        sur vous
        <span class="font-bold">{{ $store.getters.profile.first_name }}</span>
      </h1>
    </div>
    <div class="max-w-lg mx-auto">
      <div
        class="px-8 py-6 bg-white rounded-t-lg text-black text-3xl font-extrabold leading-9 text-center"
      >
        Enrichissez vos informations
      </div>
      <div class="p-8 bg-gray-50 border-t border-gray-200 rounded-b-lg">
        <div class="mb-8 text-md text-gray-500">
          Enrichissez votre profil avec les compétences que vous souhaitez
          mettre au service des organisations publiques ou associatives.
        </div>

        <div class="mb-8">
          <div class="form-register-steps el-form--label-top">
            <label for="compentences" class="el-form-item__label"
              >Renseignez vos compétences</label
            >
            <ais-instant-search
              :search-client="searchClient"
              :index-name="indexName"
            >
              <ais-configure :hits-per-page.camel="5" />
              <ais-autocomplete>
                <template slot-scope="{ indices, refine }">
                  <div class="">
                    <vue-autosuggest
                      :suggestions="indicesToSuggestions(indices)"
                      :get-suggestion-value="getSuggestionValue"
                      :input-props="{
                        placeholder:
                          'Communication, action sociale, accompagnement...',
                      }"
                      class="relative"
                      @input="onInput(refine, $event)"
                      @selected="onSelect"
                      @keyup.enter="onEnter"
                    >
                      <template slot="after-input"
                        ><div
                          class="absolute z-10 w-5 h-5 text-gray-300"
                          style="right: 15px; top: 12px"
                          v-html="
                            require('@/assets/images/icones/heroicon/search.svg?include')
                          "
                        ></div
                      ></template>
                      <template slot-scope="{ suggestion }">
                        <div>
                          <div
                            class="ml-auto leading-6 text-sm font-medium text-gray-500 flex-none"
                          >
                            <div class="flex items-center space-x-2">
                              <div class="">{{ suggestion.item.name.fr }}</div>
                              <div
                                v-if="isAlreadySelected(suggestion.item.id)"
                                class="px-2 rounded-full text-xxs bg-green-400 text-white leading-5"
                              >
                                Ajoutée
                              </div>
                            </div>
                            <div class="text-xs italic">
                              {{ suggestion.item.group }}
                            </div>
                          </div>
                        </div>
                      </template>
                    </vue-autosuggest>
                  </div>
                </template>
              </ais-autocomplete>
            </ais-instant-search>
          </div>
        </div>

        <div v-if="form.skills.length" class="mb-10">
          <div class="flex flex-wrap -m-1">
            <div
              v-for="item in form.skills"
              :key="item.id"
              class="flex items-center space-x-4 px-4 py-3 rounded-lg border border-green-400 bg-white m-1"
            >
              <div class="flex-none text-sm text-green-400 font-bold">
                {{ item.name.fr }}
              </div>
              <div
                class="flex-none cursor-pointer w-4 h-4 text-green-400 hover:text-green-800"
                @click="handleRemoveSkill(item.id)"
                v-html="
                  require('@/assets/images/icones/heroicon/close.svg?include')
                "
              />
            </div>
          </div>
        </div>

        <div class="sm:col-span-">
          <span class="block w-full rounded-md shadow-sm">
            <el-button
              type="primary"
              :loading="loading"
              class="shadow-lg block w-full text-center rounded-lg z-10 border border-transparent bg-green-400 px-4 sm:px-6 py-4 text-lg sm:text-xl leading-6 font-bold text-white hover:bg-green-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150"
              @click="onSubmit"
              >Terminer</el-button
            >
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import algoliasearch from 'algoliasearch/lite'
import { VueAutosuggest } from 'vue-autosuggest'
import {
  AisInstantSearch,
  AisConfigure,
  AisAutocomplete,
} from 'vue-instantsearch'

const searchClient = algoliasearch(
  process.env.algolia.appId,
  process.env.algolia.searchKey
)

export default {
  components: {
    VueAutosuggest,
    AisConfigure,
    AisInstantSearch,
    AisAutocomplete,
  },
  layout: 'register-steps',
  asyncData({ $api, store }) {
    return {
      form: { ...store.getters.profile },
    }
  },
  data() {
    return {
      loading: false,
      searchClient,
      indexName: process.env.algolia.skills,
      selectedItem: null,
      steps: [
        {
          name: 'Rejoignez le mouvement',
          status: 'complete',
          href: '/register/volontaire/step/profile',
        },
        {
          name: 'Votre profil',
          status: 'complete',
          href: '/register/volontaire/step/profile',
        },
        {
          name: 'Vos préférences',
          status: 'complete',
          href: '/register/volontaire/step/preferences',
        },
        {
          name: 'Vos compétences',
          status: 'current',
        },
      ],
    }
  },
  methods: {
    handleRemoveSkill(id) {
      this.form.skills = this.form.skills.filter((item) => item.id !== id)
    },
    isAlreadySelected(id) {
      return this.form.skills.filter((item) => item.id == id).length > 0
    },
    onSelect(selected) {
      if (selected && !this.isAlreadySelected(selected.item.id)) {
        this.selectedItem = selected.item
        this.$set(this.form, 'skills', [...this.form.skills, this.selectedItem])
      }
    },
    onInput(refine, $event) {
      this.query = $event
      refine($event)
    },
    onEnter($event) {
      if (!this.selectedItem) {
        // Nothing
      }
    },
    indicesToSuggestions(indices) {
      return this.query
        ? indices.map(({ hits }) => ({
            data: hits,
          }))
        : []
    },
    getSuggestionValue(suggestion) {
      return null
    },
    async onSubmit() {
      this.loading = true
      await this.$store.dispatch('user/updateProfile', {
        id: this.$store.getters.profile.id,
        ...this.form,
      })
      this.loading = false
      this.$router.push('/missions-benevolat')
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep #autosuggest
  input
    @apply w-full pl-4 pr-12 py-3 rounded-lg border border-gray-200 text-sm
::v-deep .ais-Highlight-highlighted
  background: transparent
  @apply text-blue-800 font-semibold
::v-deep .autosuggest__results-container
  .autosuggest__results
    max-width: 448px
    @apply w-full rounded-lg absolute z-50 bg-white mt-1 overflow-hidden border border-gray-200
  .autosuggest__results-item
    @apply px-4 py-2
    &:not(:last-child)
      @apply border-b border-gray-100
    &.autosuggest__results-item--highlighted
      @apply cursor-pointer bg-gray-50 text-gray-700
</style>
