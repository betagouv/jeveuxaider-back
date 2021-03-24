export default {
  name: 'MenuWithActive',
  methods: {
    isActive(item) {
      switch (item) {
        case 'dashboard':
          return [
            'dashboard',
            'dashboard-stats-structures',
            'dashboard-stats-missions',
            'dashboard-stats-participations',
            'dashboard-stats-profiles',
            'dashboard-stats-departments',
            'dashboard-stats-collectivities',
            'dashboard-stats-domaines',
          ].includes(this.$route.name)
        case 'collectivities':
          return [
            'dashboard-collectivities',
            'dashboard-collectivity-id',
            'dashboard-collectivity-id-edit',
            'dashboard-collectivity-id-history',
            'dashboard-collectivity-id-stats',
            'dashboard-collectivity-id-missions',
            'dashboard-collectivity-id-participations',
          ].includes(this.$route.name)
        case 'structures':
          return [
            'dashboard-structures',
            'dashboard-structure-add',
            'dashboard-structure-id',
            'dashboard-structure-id-edit',
            'dashboard-structure-id-missions',
            'dashboard-structure-id-missions-add',
            'dashboard-structure-id-history',
            'dashboard-structure-id-members',
            'dashboard-structure-id-members-add',
          ].includes(this.$route.name)
        case 'missions':
          return [
            'dashboard-missions',
            'dashboard-mission-add',
            'dashboard-mission-id',
            'dashboard-mission-id-edit',
            'dashboard-mission-id-participations',
            'dashboard-mission-id-history',
            'dashboard-mission-id-trouver-des-benevoles',
          ].includes(this.$route.name)
        case 'participations':
          return [
            'dashboard-participations',
            'dashboard-participation-id',
            'dashboard-participation-id-history',
          ].includes(this.$route.name)
        case 'profiles':
          return [
            'dashboard-profiles',
            'dashboard-profile-id',
            'dashboard-profile-id-edit',
            'dashboard-profile-id-activity',
            'dashboard-profile-id-history',
            'dashboard-profile-id-referents-departements',
            'dashboard-profile-id-referents-regions',
            'dashboard-profile-id-responsables',
            'dashboard-profile-id-invitations',
            'dashboard-profile-id-invitations-add',
          ].includes(this.$route.name)
        case 'contents':
          return this.$route.name.includes('-contents-')
        case 'activities':
          return ['dashboard-activities'].includes(this.$route.name)
        case 'ressources':
          return ['dashboard-ressources'].includes(this.$route.name)
        case 'collectivity-stats':
          return [
            'dashboard-collectivity-id-stats',
            'dashboard-collectivity-id-missions',
            'dashboard-collectivity-id-participations',
          ].includes(this.$route.name)
        case 'collectivity-edit':
          return ['dashboard-collectivity-id-edit'].includes(this.$route.name)
        default:
          return false
      }
    },
  },
}
