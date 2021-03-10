<template>
  <el-dropdown split-button type="primary" @command="handleCommand">
    <router-link
      :to="{
        name: 'DashboardMissionFormEdit',
        params: { id: mission.id },
      }"
    >
      Modifier la mission
    </router-link>
    <el-dropdown-menu slot="dropdown">
      <router-link
        :to="{
          name: 'Mission',
          params: { id: mission.id, slug: mission.slug },
        }"
        target="_blank"
      >
        <el-dropdown-item command=""> Visualiser la mission</el-dropdown-item>
      </router-link>
      <el-dropdown-item :command="{ action: 'clone' }"
        >Dupliquer la mission</el-dropdown-item
      >
      <el-dropdown-item
        v-if="$store.getters.contextRole != 'responsable'"
        divided
        :command="{ action: 'delete' }"
      >
        Supprimer la mission
      </el-dropdown-item>
    </el-dropdown-menu>
  </el-dropdown>
</template>

<script>
import { MessageBox, Message } from 'element-ui'
export default {
  props: {
    mission: {
      type: Object,
      required: true,
    },
  },
  methods: {
    handleCommand(command) {
      if (command.action == 'delete') {
        this.handleDeleteStructure()
      }
    },
    handleDeleteStructure() {
      if (this.structure.missions_count > 0) {
        MessageBox.alert(
          'Il est impossible de supprimer une organisation qui contient des missions.',
          "Supprimer l'organisation",
          {
            confirmButtonText: 'Retour',
            type: 'warning',
            center: true,
          }
        )
      } else {
        MessageBox.confirm(
          `L'organisation ${this.structure.name} sera définitivement supprimée de la plateforme.<br><br> Voulez-vous continuer ?<br>`,
          "Supprimer l'organisation",
          {
            confirmButtonText: 'Supprimer',
            confirmButtonClass: 'el-button--danger',
            cancelButtonText: 'Annuler',
            center: true,
            dangerouslyUseHTMLString: true,
            type: 'error',
          }
        ).then(() => {
          this.$api.deleteStructure(this.structure.id).then(() => {
            Message.success({
              message: `L'organisation ${this.structure.name} a été supprimée.`,
            })
            this.$router.push('/dashboard/structures')
          })
        })
      }
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
