<template>
  <div class="structure-view">
    <HeaderOrganisation :structure="structure">
      <template #action>
        <DropdownStructureButton :structure="structure" />
      </template>
    </HeaderOrganisation>
    <NavTabStructure
      v-if="$store.getters.contextRole != 'responsable'"
      :structure="structure"
    />
    <div class="px-12">
      <div class="flex space-x-4">
        <div class="w-1/2">
          <el-card shadow="never" class="p-4">
            <div class="flex justify-between">
              <div class="mb-6 text-xl font-semibold">Organisation</div>
            </div>
            <ModelStructureInfos :structure="structure" />
          </el-card>
        </div>
        <div class="w-1/2 flex flex-col space-y-4">
          <el-card shadow="never" class="p-4">
            <div class="flex justify-between items-start">
              <div v-if="structure.members" class="mb-6 text-xl font-semibold">
                Membres
              </div>
              <nuxt-link :to="`/dashboard/structure/${structure.id}/members`">
                <el-button size="small" type="secondary">
                  Gérer les membres
                </el-button>
              </nuxt-link>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <ModelMemberTeaser
                v-for="member in structure.members"
                :key="member.id"
                class="member py-2"
                :member="member"
              />
            </div>
          </el-card>
          <template v-if="structure.reseaux.length">
            <el-card
              v-for="reseau in structure.reseaux"
              :key="reseau.id"
              shadow="never"
              class="p-4"
            >
              <div class="flex justify-between items-start">
                <div class="mb-6 text-xl font-semibold">
                  Réseau: {{ reseau.name }}
                </div>
                <el-button
                  v-if="canDetachReseau(reseau)"
                  size="small"
                  type="danger"
                  @click="onClickRemove(reseau)"
                >
                  Retirer
                </el-button>
              </div>
              <div class="grid grid-cols-2 gap-3">
                <ModelMemberTeaser
                  v-for="responsable in reseau.responsables"
                  :key="responsable.id"
                  class="member py-2"
                  :member="responsable"
                />
              </div>
            </el-card>
          </template>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params }) {
    const structure = await $api.getStructure(params.id)
    return {
      structure,
    }
  },
  methods: {
    canDetachReseau(reseau) {
      return (
        this.$store.getters.contextRole == 'admin' ||
        this.$store.getters.contextRole == 'referent' ||
        this.$store.getters.profile.tete_de_reseau_id == reseau.id
      )
    },
    onClickRemove(reseau) {
      this.$confirm(
        `L'organisation ${this.structure.name} sera retirée du réseau.<br><br> Voulez-vous continuer ?<br>`,
        "Retirer l'organisation du réseau",
        {
          confirmButtonText: 'Retirer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          dangerouslyUseHTMLString: true,
          type: 'error',
        }
      ).then(() => {
        this.$api
          .detachStructureFromReseau(this.structure.id, reseau.id)
          .then(async () => {
            this.$message.success({
              message: `L'organisation ${this.structure.name} a été retirée du reseau.`,
            })
            const structure = await this.$api.getStructure(this.structure.id)
            this.structure = structure
          })
      })
    },
  },
}
</script>

<style scoped lang="postcss">
.el-menu--horizontal {
  @apply px-12;
  > .el-menu-item {
    @apply mr-8 p-0 font-medium;
    border-bottom: solid 3px #070191;
  }
}
</style>
