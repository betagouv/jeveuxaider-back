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
      placeholder="Rechercher par mots clés..."
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
import { groupBy, sortBy } from 'lodash'

export default {
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
      return groupBy(
        sortBy(this.optionsSkills, ['group']),
        (skill) => skill.group
      )
    },
    formattedValue() {
      return this.value
    },
  },
  methods: {
    onChangeFilter(value) {
      this.$emit('changed', { name: this.name, value })
    },
    fetchSkills(query) {
      if (query !== '') {
        this.loading = true
        this.$api
          .fetchTags({ 'filter[type]': 'competence', 'filter[name]': query })
          .then((response) => {
            this.loading = false
            this.optionsSkills = response.data.data
          })
      } else {
        this.optionsSkills = []
      }
    },
  },
}
</script>

<style lang="sass" scoped>
.query-filter-skills
  ::v-deep
    .el-input__inner
      width: 400px
</style>
