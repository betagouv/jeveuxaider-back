<template>
  <div class="structure-view">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">Collectivité</div>
        <div class="flex flex-wrap mb-8">
          <div class="font-bold text-2xl text-gray-800 mr-2">
            {{ collectivity.name }}
          </div>
          <TagModelState
            v-if="collectivity.state"
            :state="collectivity.state"
          />
          <el-tag v-if="collectivity.published" class="m-1 ml-0" size="medium">
            En ligne
          </el-tag>
          <el-tag v-if="!collectivity.published" class="m-1 ml-0" size="medium">
            Hors ligne
          </el-tag>
        </div>
      </div>
      <div>
        <el-dropdown split-button type="primary" @command="handleCommand">
          <nuxt-link :to="`/dashboard/collectivity/${collectivity.id}/edit`">
            Modifier la collectivité
          </nuxt-link>
          <el-dropdown-menu slot="dropdown">
            <nuxt-link
              :to="`/territoires/collectivites/${collectivity.slug}`"
              target="_blank"
            >
              <el-dropdown-item> Visualiser la collectivité</el-dropdown-item>
            </nuxt-link>

            <el-dropdown-item divided :command="{ action: 'delete' }">
              Supprimer la collectivité
            </el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </div>
    </div>
    <el-menu
      :default-active="$router.history.current.path"
      mode="horizontal"
      class="mb-8"
      @select="$router.push($event)"
    >
      <el-menu-item :index="`/dashboard/collectivity/${collectivity.id}`">
        Informations
      </el-menu-item>
      <el-menu-item
        :index="`/dashboard/collectivity/${collectivity.id}/history`"
      >
        Historique
      </el-menu-item>
    </el-menu>

    <div class="px-12">
      <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        <el-card shadow="never" class="p-4">
          <div class="flex justify-between">
            <div class="mb-6 text-xl">Informations</div>
          </div>
          <ModelCollectivityInfos :collectivity="collectivity" />
        </el-card>
        <el-card v-if="collectivity.structure" shadow="never" class="p-4">
          <div class="flex justify-between">
            <nuxt-link
              :to="`/dashboard/structure/${collectivity.structure.id}`"
              class="mb-6 text-xl hover:text-blue-800"
              >{{ collectivity.structure.name }}</nuxt-link
            >
          </div>
          <ModelStructureInfos :structure="collectivity.structure" />
        </el-card>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
    const collectivity = await $api.getCollectivity(params.id)
    return {
      collectivity,
    }
  },
  methods: {
    handleCommand(command) {
      if (command.action == 'delete') {
        this.handleClickDelete(command.id)
      } else {
        this.$router.push(command)
      }
    },
    handleClickDelete() {
      this.$confirm(
        `Êtes vous sur de vouloir supprimer cette collectivité ?`,
        'Supprimer cette collectivité',
        {
          confirmButtonText: 'Supprimer',
          confirmButtonClass: 'el-button--danger',
          cancelButtonText: 'Annuler',
          center: true,
          type: 'error',
        }
      ).then(() => {
        this.$api.deleteCollectivity(this.collectivity.id).then(() => {
          this.$message.success({
            message: `La collectivité a été supprimée.`,
          })
          this.$router.push('/dashboard/collectivities')
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
