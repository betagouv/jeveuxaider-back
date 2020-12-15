<template>
  <div class="query-filter-skills mr-4 mb-4">
    <div class="text-secondary text-xs uppercase font-semibold mb-2">
      {{ label }}
    </div>
    <el-select
      :value="formattedValue"
      clearable
      filterable
      reserve-keyword
      remote
      :remote-method="fetchSkills"
      placeholder="Rechercher..."
      :loading="loading"
      @change="onChangeFilter"
    >
      <el-option-group
        v-for="(skillset, index) in skillGroups"
        :key="index"
        :label="index"
      >
        <el-option
          v-for="item in skillset"
          :key="item.id"
          :label="item.name.fr"
          :value="item.name.fr"
        >
        </el-option>
      </el-option-group>
    </el-select>
  </div>
</template>

<script>
import { fetchTags } from '@/api/app'
import _ from 'lodash'

export default {
  name: 'QueryFilterSkills',
  props: {
    value: {
      type: [String, Number, Boolean, Array],
      required: false,
      default: null,
    },
    name: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      skills: null,
      optionsSkills: [],
    }
  },
  computed: {
    skillGroups() {
      return _.groupBy(
        _.sortBy(this.optionsSkills, ['group']),
        (skill) => skill.group
      )
    },
    formattedValue() {
      console.log(this.value)
      // if (this.value == 'true') {
      //   return 'Oui'
      // } else if (this.value == 'false') {
      //   return 'Non'
      // }
      // if (
      //   this.multiple &&
      //   !(this.value instanceof Array) &&
      //   this.value !== null
      // ) {
      //   return [this.value]
      // }
      // if (
      //   !this.multiple &&
      //   this.value &&
      //   this.options[0] &&
      //   this.options[0].value &&
      //   this.options[0].label
      // ) {
      //   return this.options.filter((option) => this.value == option.value)[0]
      //     .label
      // }
      return this.value
    },
  },
  methods: {
    onChangeFilter(value) {
      this.$emit('changed', { name: this.name, value: value })
    },
    fetchSkills(query) {
      if (query !== '') {
        this.loading = true
        fetchTags({ 'filter[type]': 'competence', 'filter[name]': query }).then(
          (response) => {
            this.loading = false
            this.optionsSkills = response.data.data
          }
        )
      } else {
        this.optionsSkills = []
      }
    },
  },
}
</script>

<style lang="sass">
.query-filter
  .el-tag.el-tag--info
    .el-select__tags-text
      display: inline-flex
      max-width: 120px
      overflow: hidden
</style>
