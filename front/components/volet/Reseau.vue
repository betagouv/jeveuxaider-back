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
        <VoletRowItem label="Nb. antennes"
          ><span class="font-bold"
            >{{ row.structures_count }} antenne(s)</span
          ></VoletRowItem
        >
        <VoletRowItem label="Nb. modèles"
          ><span class="font-bold"
            >{{ row.mission_templates_count }} modèle(s)</span
          ></VoletRowItem
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

      <!-- RESPONSABLES -->
      <template v-if="responsables.length > 0">
        <VoletCard
          v-for="responsable in responsables"
          :key="responsable.id"
          label="Responsable"
          :icon="require('@/assets/images/icones/heroicon/user.svg?include')"
          :link="
            $store.getters.contextRole == 'admin'
              ? `/dashboard/profile/${responsable.id}`
              : null
          "
        >
          <!-- <VoletRowItem label="ID">{{ responsable.id }}</VoletRowItem> -->
          <VoletRowItem label="Nom"
            ><span class="font-bold">{{
              responsable.full_name
            }}</span></VoletRowItem
          >
          <VoletRowItem label="Email">{{ responsable.email }}</VoletRowItem>
          <VoletRowItem label="Mobile">{{ responsable.mobile }}</VoletRowItem>
          <VoletRowItem v-if="responsable.phone" label="Tel">{{
            responsable.phone
          }}</VoletRowItem>
        </VoletCard>
      </template>
    </div>
  </Volet>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      reseau: null,
      responsables: [],
      antennes: [],
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
        this.reseau = { ...newValue }
        const responsables = await this.$api.getReseauResponsables(
          this.reseau.id
        )
        this.responsables = responsables.data
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
