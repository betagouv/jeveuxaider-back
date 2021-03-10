<template>
  <el-dropdown split-button type="primary" @command="handleCommand">
    <nuxt-link :to="`/dashboard/mission/${mission.id}/edit`">
      Modifier la mission
    </nuxt-link>
    <el-dropdown-menu slot="dropdown">
      <nuxt-link :to="`/mission/${mission.id}/${mission.slug}`" target="_blank">
        <el-dropdown-item command=""> Visualiser la mission</el-dropdown-item>
      </nuxt-link>
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
        this.handleDelete()
      } else if (command.action == 'clone') {
        this.hanldleClone()
      } else {
        this.$router.push(command)
      }
    },
    hanldleClone() {
      this.loading = true
      this.$api.cloneMission(this.mission.id).then((response) => {
        this.$router
          .push({
            path: `/dashboard/mission/${response.data.id}/edit`,
          })
          .then(() => {
            Message.success({
              message: 'La mission a été dupliquée !',
            })
          })
      })
    },
    handleDelete() {
      if (this.mission.participations_total > 0) {
        MessageBox.alert(
          'Il est impossible de supprimer une mission déjà assigner à un ou plusieurs bénévoles.',
          'Supprimer la mission',
          {
            confirmButtonText: 'Retour',
            type: 'warning',
            center: true,
          }
        )
      } else {
        MessageBox.confirm(
          `La mission ${this.mission.name} sera définitivement supprimée de la plateforme. Voulez-vous continuer ?`,
          'Supprimer la mission',
          {
            confirmButtonText: 'Supprimer',
            confirmButtonClass: 'el-button--danger',
            cancelButtonText: 'Annuler',
            center: true,
            type: 'error',
          }
        ).then(() => {
          this.$api.deleteMission(this.mission.id).then(() => {
            Message.success({
              message: `La mission ${this.mission.name} a été supprimée.`,
            })
            this.$router.push('/dashboard/missions')
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
