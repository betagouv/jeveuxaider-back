<template>
  <el-dropdown split-button type="primary" @command="handleCommand">
    <nuxt-link :to="`/dashboard/territoire/${territoire.id}/edit`">
      Modifier le territoire
    </nuxt-link>
    <el-dropdown-menu slot="dropdown">
      <nuxt-link
        v-if="
          territoire.state == 'validated' && territoire.is_published == true
        "
        target="_blank"
        :to="territoire.full_url"
        class="cursor-not-allowed"
      >
        <el-dropdown-item :command="{}">
          Afficher le territoire
        </el-dropdown-item>
      </nuxt-link>
      <div v-else>
        <el-dropdown-item :command="{}">
          <div class="cursor-not-allowed">Afficher le territoire</div>
        </el-dropdown-item>
      </div>
      <nuxt-link :to="`/dashboard/territoire/${territoire.id}/responsables`">
        <el-dropdown-item :command="{}">
          Gérer les responsables
        </el-dropdown-item>
      </nuxt-link>
      <nuxt-link
        :to="`/dashboard/territoire/${territoire.id}/responsables/add`"
      >
        <el-dropdown-item :command="{}">
          Ajouter un responsable
        </el-dropdown-item>
      </nuxt-link>
      <el-dropdown-item
        v-if="$store.getters.contextRole === 'admin'"
        :command="{ action: 'delete' }"
        divided
      >
        Supprimer le territoire
      </el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
export default {
  props: {
    territoire: {
      type: Object,
      required: true,
    },
  },
  computed: {},
  methods: {
    url(row) {
      switch (row.type) {
        case 'department':
          return `/territoires/departements/${row.slug}`
        case 'collectivity':
          return `/territoires/collectivites/${row.slug}`
        case 'city':
          return `/territoires/villes/${row.slug}`
      }
    },
    handleCommand(command) {
      if (command.action == 'delete') {
        this.handleDeleteTerritoire()
      }
    },
    handleDeleteTerritoire() {
      this.$confirm(
        `Le territoire ${this.territoire.name} sera définitivement supprimée de la plateforme.<br><br> Voulez-vous continuer ?<br>`,
        'Supprimer le territoire',
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          dangerouslyUseHTMLString: true,
          type: 'error',
        }
      ).then(() => {
        this.$api.deleteTerritoire(this.territoire.id).then(() => {
          this.$message.success({
            message: `Le territoire ${this.territoire.name} a été supprimée.`,
          })
          this.$router.push('/dashboard/territoires')
        })
      })
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
