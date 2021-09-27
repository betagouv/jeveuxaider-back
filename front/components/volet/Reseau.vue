<template>
  <Volet :title="row.name" :link="`/dashboard/reseaux/${row.id}`">
    <div class="flex flex-col space-y-6">
      <!-- ACTIONS -->
      <div class="flex flex-wrap gap-2">
        <nuxt-link :to="`/dashboard/reseaux/${row.id}/edit`">
          <el-button
            v-tooltip="{
              content: 'Modifier le réseau',
              classes: 'bo-style',
            }"
            icon="el-icon-edit"
            size="small"
            >Modifier</el-button
          >
        </nuxt-link>

        <el-button
          v-if="$store.getters.contextRole == 'admin'"
          v-tooltip="{
            content: 'Supprimer le réseau',
            classes: 'bo-style',
          }"
          plain
          type="danger"
          size="small"
          @click="onClickDelete"
        >
          <i class="el-icon-delete" />
        </el-button>
      </div>

      <!-- RÉSEAU -->
      <VoletCard
        label="Réseau"
        :link="`/dashboard/reseaux/${row.id}`"
        :icon="require('@/assets/images/icones/heroicon/library.svg?include')"
      >
        <VoletRowItem label="ID">{{ row.id }}</VoletRowItem>
        <VoletRowItem label="Nom"
          ><span class="font-bold">{{ row.name }}</span></VoletRowItem
        >
        <VoletRowItem label="Crée le">{{
          row.created_at | formatMediumWithTime
        }}</VoletRowItem>
        <VoletRowItem label="Modifié le">{{
          row.updated_at | formatMediumWithTime
        }}</VoletRowItem>
        <VoletRowItem label="Statut">{{
          row.state | labelFromValue('structure_workflow_states')
        }}</VoletRowItem>
      </VoletCard>
    </div>
  </Volet>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      responsables: [],
    }
  },
  computed: {
    row() {
      return this.$store.getters['volet/row']
    },
  },
  watch: {
    row: {
      immediate: true,
      deep: false,
      async handler(newValue, oldValue) {
        // const responsables = await this.$api.getStructureMembers(
        //   this.structure.id
        // )
        // this.responsables = responsables.data
      },
    },
  },
  methods: {
    onClickDelete() {
      this.$confirm(
        `Le réseau ${this.row.name} sera définitivement supprimé. <br><br> Voulez-vous continuer ?<br>`,
        'Supprimer le réseau',
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          dangerouslyUseHTMLString: true,
          type: 'error',
        }
      ).then(() => {
        this.$api.deleteReseau(this.row.id).then(() => {
          this.$message.success({
            message: `Le réseau ${this.row.name} a été supprimé.`,
          })
          this.$emit('deleted', this.row)
          this.$store.commit('volet/hide')
        })
      })
    },
  },
}
</script>
