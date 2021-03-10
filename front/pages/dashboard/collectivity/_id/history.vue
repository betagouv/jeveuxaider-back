<template>
  <div class="has-full-table">
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
              :to="`/territoires/${collectivity.slug}`"
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
    <TableActivities :table-data="tableData" />
    <div class="m-3 flex items-center">
      <el-pagination
        background
        layout="prev, pager, next"
        :total="totalRows"
        :page-size="15"
        :current-page="Number(query.page)"
        @current-change="onPageChange"
      />
      <div class="text-secondary text-xs ml-3">
        Affiche {{ fromRow }} à {{ toRow }} sur {{ totalRows }} résultats
      </div>
    </div>
  </div>
</template>

<script>
import { Message, MessageBox } from 'element-ui'
import TableWithFilters from '@/mixins/table-with-filters'

export default {
  mixins: [TableWithFilters],
  layout: 'dashboard',
  async asyncData({ $api, params }) {
    const collectivity = await $api.getCollectivity(params.id)
    return {
      collectivity,
    }
  },
  async fetch() {
    this.query = this.$route.query
    const { data } = await this.$api.fetchActivities({
      'filter[subject_id]': this.$route.params.id,
      'filter[subject_type]': 'Collectivity',
      page: this.$route.query.page || 1,
    })
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  watch: {
    '$route.query': '$fetch',
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
      MessageBox.confirm(
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
          Message.success({
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
