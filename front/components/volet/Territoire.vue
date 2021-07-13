<template>
  <Volet v-if="$store.getters['volet/active']">
    <div class="text-xs text-gray-600 uppercase text-center mt-8 mb-12"></div>
    <el-card shadow="never" class="overflow-visible relative">
      <div slot="header" class="clearfix flex flex-col items-center">
        <div class="-mt-10">
          <Avatar :fallback="row.name[0]" />
        </div>
        <nuxt-link
          class="font-semibold text-sm my-3 text-primary text-center"
          :to="`/dashboard/territoire/${row.id}`"
        >
          {{ row.name }}
        </nuxt-link>
        <div class="flex items-center">
          <nuxt-link :to="`/dashboard/territoire/${row.id}`">
            <el-button class="mr-1" icon="el-icon-view" type="mini">
              Voir
            </el-button>
          </nuxt-link>
          <nuxt-link
            v-if="$store.getters.contextRole == 'admin'"
            :to="`/dashboard/territoire/${row.id}/edit`"
          >
            <el-button icon="el-icon-edit" type="mini"> Modifier </el-button>
          </nuxt-link>
          <el-button
            v-if="$store.getters.contextRole == 'admin'"
            type="button"
            class="ml-1 el-button is-plain el-button--danger el-button--mini"
            @click="onClickDelete"
          >
            <i class="el-icon-delete" />
          </el-button>
        </div>
      </div>
      <div class="flex flex-wrap items-center justify-center mb-4">
        <el-tag v-if="row.published" size="small" class="m-1 ml-0">
          En ligne
        </el-tag>
        <el-tag v-if="!row.published" size="small" class="m-1 ml-0">
          Hors ligne
        </el-tag>
      </div>
      <ModelTerritoireInfos :territoire="row" />
    </el-card>
  </Volet>
</template>

<script>
export default {
  computed: {
    row() {
      return this.$store.getters['volet/row']
    },
  },
  methods: {
    onClickDelete() {
      this.$confirm(
        `Êtes vous sur de vouloir supprimer ce territoire ?`,
        'Supprimer ce territoire',
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          type: 'error',
        }
      ).then(() => {
        this.$api.deleteTerritoire(this.row.id).then(() => {
          this.$message.success({
            message: `Le territoire a été supprimée.`,
          })
          this.$emit('deleted', this.row)
          this.$store.commit('volet/hide')
        })
      })
    },
  },
}
</script>
