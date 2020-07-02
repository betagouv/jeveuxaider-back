<script>
export default {
  name: 'ActivityUtils',
  methods: {
    type(subject_type) {
      switch (subject_type) {
        case 'App\\Models\\Mission':
          return 'Mission'
        case 'App\\Models\\Structure':
          return 'Structure'
        case 'App\\Models\\Participation':
          return 'Participation'
        case 'App\\Models\\Profile':
          return 'Utilisateur'
        default:
          return 'Autre'
      }
    },
    linkSubject(row) {
      switch (row.subject_type) {
        case 'App\\Models\\Mission':
          return `/dashboard/mission/${row.subject_id}/edit`
        case 'App\\Models\\Structure':
          return `/dashboard/structure/${row.subject_id}`
        case 'App\\Models\\Participation':
          return `/dashboard/participations`
        case 'App\\Models\\Profile':
          return `/dashboard/profile/${row.subject_id}`
        default:
          return '#'
      }
    },
    linkCauser(row) {
      return row.causer_type == 'App\\Models\\User'
        ? `/dashboard/profile/${row.data.causer_id}`
        : '#'
    },
    rowClicked(row) {
      this.$refs.tableData.toggleRowExpansion(row)
    },
  },
}
</script>
