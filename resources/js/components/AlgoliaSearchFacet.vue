<template>
  <div class="z-100 text-gray-1000">
    <ais-refinement-list
      :ref="name"
      :key="name"
      class="facet-list"
      :attribute="name"
      show-more
      :limit="4"
      :show-more-limit="100"
    >
      <div
        slot-scope="{
          items,
          isShowingMore,
          isFromSearch,
          canToggleShowMore,
          refine,
          createURL,
          toggleShowMore,
          searchForItems,
        }"
      >
        <div class="h-7 flex justify-between mb-3">
          <span v-if="!isSearching" class="font-black text-gray-1000">
            {{ label }}
          </span>

          <el-input
            v-else
            ref="facet-search"
            v-model="search"
            class="facet-search"
            label="Recherche"
            clearable
            autocomplete="new-password"
            @input="searchForItems(search)"
          />

          <template v-if="isSearchable">
            <img
              src="/images/search-facet.svg"
              class="cursor-pointer ml-4"
              @click="onSearchClick"
            />
          </template>
        </div>

        <div v-if="!items.length" class="text-sm text-gray-450">Néant.</div>

        <el-checkbox
          v-for="item in items"
          :key="item.value"
          class="w-full text-sm cursor-pointer mb-3"
          :label="item.value"
          :value="item.isRefined"
          @change="onChange(refine, item)"
        >
          <div class="facet-value flex justify-between">
            <v-clamp
              :max-lines="1"
              tag="span"
              autoresize
              class="w-full mr-2 relative"
              :class="[
                { 'text-gray-1000 font-bold': item.isRefined },
                { 'text-gray-450': !item.isRefined },
              ]"
            >
              {{ item.value }}

              <template
                slot="after"
                slot-scope="{ expand, collapse, toggle, clamped, expanded }"
              >
                <!-- Tooltip if clamped -->
                <span
                  v-if="clamped"
                  v-tooltip="{
                    delay: { show: 700, hide: 100 },
                    content: item.value,
                    hideOnTargetClick: true,
                    placement: 'top',
                  }"
                  class="absolute w-full h-full top-0 left-0"
                />
              </template>
            </v-clamp>
            <span class="count text-gray-450">
              {{ item.count.toLocaleString() }}
            </span>
          </div>
        </el-checkbox>

        <button
          v-if="canToggleShowMore"
          class="uppercase text-xs font-bold text-gray-1000 mt-2"
          style="margin-left: 30px"
          @click="toggleShowMore"
        >
          {{ !isShowingMore ? 'Plus' : 'Moins' }}
        </button>
      </div>
    </ais-refinement-list>
  </div>
</template>

<script>
import { AisRefinementList } from 'vue-instantsearch'

export default {
  components: {
    AisRefinementList,
  },
  props: {
    name: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    isSearchable: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      isSearching: false,
      search: '',
    }
  },
  methods: {
    onSearchClick() {
      this.isSearching = !this.isSearching
      if (this.isSearching) {
        this.$nextTick(() => {
          this.$refs['facet-search'].focus()
        })
      }
    },
    onChange(refine, item) {
      this.search = ''
      this.isSearching = false

      refine(item.value)
      this.$emit('toggle-facet', {
        active: !item.isRefined,
        name: this.name,
        value: item.value,
      })
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep .facet-value
  > *
    transition: all .25s
  &:hover
    .label
      color: #27303f !important

::v-deep .el-checkbox
  @apply flex items-center whitespace-normal
  .el-checkbox__label
    @apply w-full
  .el-checkbox__input
    .el-checkbox__inner
      width: 20px
      height: 20px
      border-color: white
      border-radius: 4px
      &::after
        border: 2px solid #5B71B9
        border-left: 0
        border-top: 0
        height: 10px
        left: 6px
        top: 1px
        width: 4px
    &.is-checked
      .el-checkbox__inner
        background-color: #E6EAF5
        border-color: #E6EAF5

.facet-search
  ::v-deep .el-input__inner
    height: 28px
    border-color: #EDE8E9
    color: #171725
    padding: 0 30px 0 8px
  ::v-deep .el-input__icon
    line-height: 28px
</style>
