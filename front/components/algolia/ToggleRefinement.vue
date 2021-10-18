<template>
  <div class="z-100 text-[#171725]">
    <AisToggleRefinement
      :ref="attribute"
      class="facet-list"
      :attribute="attribute"
      :label="label"
    >
      <div slot-scope="{ value, refine }">
        <!-- <div class="h-7 flex justify-between mb-3">
          <span class="font-black text-[#171725]">
            {{ label }}
          </span>
        </div> -->

        <el-checkbox
          class="!w-full !text-sm !cursor-pointer !mb-3"
          :label="label"
          :value="value.isRefined"
          @change="onChange(refine, value)"
        >
          <div class="facet-value flex justify-between">
            <client-only :placeholder="label">
              <v-clamp
                :max-lines="1"
                tag="span"
                autoresize
                class="w-full mr-2 relative"
                :class="[
                  { 'text-[#171725] font-bold': value.isRefined },
                  { 'text-gray-600': !value.isRefined },
                ]"
              >
                {{ label }}

                <template slot="after" slot-scope="{ clamped }">
                  <span
                    v-if="clamped"
                    v-tooltip="{
                      delay: { show: 700, hide: 100 },
                      content: label,
                    }"
                    class="absolute w-full h-full top-0 left-0"
                  />
                </template>
              </v-clamp>
            </client-only>

            <span v-if="showCount" class="count text-gray-600">
              {{ value.count }}
            </span>
          </div>
        </el-checkbox>
      </div>
    </AisToggleRefinement>
  </div>
</template>

<script>
import { AisToggleRefinement } from 'vue-instantsearch'

export default {
  components: {
    AisToggleRefinement,
  },
  props: {
    attribute: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    showCount: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {}
  },
  methods: {
    onChange(refine, value) {
      refine(value)
      this.$emit('toggle-facet', {
        active: !value.isRefined,
        name: this.attribute,
        value: !value.isRefined,
      })
    },
  },
}
</script>

<style lang="postcss" scoped>
.facet-list ::v-deep .facet-value {
  > * {
    transition: all 0.25s;
  }
  &:hover {
    .label {
      color: #27303f !important;
    }
  }
}

.facet-list .el-checkbox {
  @apply flex items-center whitespace-normal;

  ::v-deep .el-checkbox__label {
    @apply w-full;
  }

  ::v-deep .el-checkbox__input {
    &.is-focus {
      .el-checkbox__inner {
        border-color: #070191 !important;
      }
    }
    &.is-checked {
      .el-checkbox__inner {
        background-color: #e6eaf5 !important;
        border-color: #e6eaf5 !important;
      }
    }
  }

  ::v-deep .el-checkbox__inner {
    width: 20px !important;
    height: 20px !important;
    border-color: white !important;
    border-radius: 4px !important;
    &::after {
      border: 2px solid #5b71b9 !important;
      border-left: 0 !important;
      border-top: 0 !important;
      height: 10px !important;
      left: 6px !important;
      top: 1px !important;
      width: 4px !important;
    }
  }
}
</style>
