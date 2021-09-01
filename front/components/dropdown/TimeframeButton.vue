<template>
  <el-dropdown split-button type="primary" @command="handleCommand">
    <span> {{ selectedOption.label }} </span>
    <el-dropdown-menu slot="dropdown">
      <el-dropdown-item
        v-for="option in options"
        :key="option.key"
        :command="option.key"
      >
        {{ option.label }}
      </el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
export default {
  data() {
    return {
      selectedValue: 'last-30-days',
      options: [
        {
          key: 'last-30-days',
          label: 'Les 30 derniers jours',
        },
        {
          key: 'last-7-days',
          label: 'Les 7 derniers jours',
        },
        {
          key: 'current-month',
          label: 'Ce mois-ci',
        },
        {
          key: 'last-month',
          label: 'Le mois dernier',
        },
        {
          key: 'current-year',
          label: 'Cette année',
        },
        {
          key: 'last-year',
          label: "L'année dernière",
        },
        {
          key: 'all',
          label: 'Depuis le début',
        },
      ],
    }
  },
  computed: {
    selectedOption() {
      return this.options.find((option) => option.key == this.selectedValue)
    },
  },
  methods: {
    handleCommand(command) {
      this.selectedValue = command
      this.$emit('change', command)
    },
  },
}
</script>

<style scoped lang="sass">
.el-menu--horizontal
  @apply px-12
  > .el-menu-item
    @apply mr-8 p-0 font-medium
      border-bottom: solid 3px #070191
</style>
