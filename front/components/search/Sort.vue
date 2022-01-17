<template>
  <el-dropdown @command="handleCommand">
    <el-button icon="el-icon-sort">{{ activeLabel }}</el-button>

    <el-dropdown-menu slot="dropdown">
      <el-dropdown-item
        v-for="sort in sorts"
        :key="sort.value"
        :disabled="active == sort.value"
        :command="sort.value"
      >
        {{ sort.label }}
      </el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
export default {
  props: {
    // QueryBuilder's defaultSort
    default: {
      type: String,
      required: true,
    },
    // QueryBuilder's allowedSorts
    sorts: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      active: this.$route.query?.sort ?? this.default,
    }
  },
  computed: {
    activeLabel() {
      return this.sorts?.find((sort) => sort.value == this.active)?.label
    },
  },
  methods: {
    handleCommand(newSort) {
      this.active = newSort
      this.$router.push({
        path: this.$router.history.current.path,
        query: {
          ...this.$route.query,
          sort: newSort,
        },
      })
    },
  },
}
</script>
