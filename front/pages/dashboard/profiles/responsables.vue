<template>
  <div class="profiles has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-8 font-bold text-2-5xl text-[#242526]">
          Utilisateurs - Responsables
        </div>
      </div>
      <div v-if="$store.getters.contextRole === 'admin'" class="">
        <nuxt-link to="/dashboard/profiles/invitations/add">
          <el-button type="primary"> Inviter un utilisateur </el-button>
        </nuxt-link>
      </div>
    </div>
    <div class="px-12 mb-12">
      <TabsProfiles index="/dashboard/profiles/responsables" />
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <SearchFiltersQueryMain
          name="search"
          placeholder="Rechercher par nom, prénom, email..."
          :initial-value="query['filter[search]']"
          @changed="onFilterChange"
        />
      </div>
    </div>
    <el-table
      v-loading="$fetchState.pending"
      :data="tableData"
      :highlight-current-row="true"
      @row-click="onClickedRow"
    >
      <el-table-column width="70" align="center">
        <template slot-scope="scope">
          <Avatar
            :source="scope.row.image ? scope.row.image.thumb : null"
            :fallback="scope.row.short_name"
          />
        </template>
      </el-table-column>
      <el-table-column label="Email" min-width="300">
        <template slot-scope="scope">
          <div class="text-gray-900 flex items-center">
            {{ scope.row.full_name }}
            <UserOnlineIndicator
              class="ml-3"
              :last-online-at="scope.row.last_online_at"
            />
          </div>
          <div class="font-light text-gray-600 text-xs">
            {{ scope.row.email }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Organisation" min-width="200">
        <template slot-scope="scope">
          <div v-if="scope.row.structures[0]" class="text-gray-600">
            {{ scope.row.structures[0].name }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="# Participations" width="150" align="center">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.responsable_waiting_actions.total | formatNumber }}
          </div>
          <div class="font-light text-gray-600 text-xs">en attente</div>
        </template>
      </el-table-column>
      <el-table-column prop="created_at" label="Crée le" min-width="120">
        <template slot-scope="scope">
          <div class="text-sm text-gray-600">
            {{ scope.row.created_at | fromNow }}
          </div>
        </template>
      </el-table-column>
      <el-table-column
        v-if="$store.getters.contextRole === 'admin'"
        label="Actions"
        min-width="250"
        class-name="dropdown-wrapper"
      >
        <template slot-scope="scope">
          <el-dropdown
            v-if="$store.getters.contextRole === 'admin'"
            split-button
            size="small"
            @command="handleCommand"
          >
            Choisissez une action
            <el-dropdown-menu slot="dropdown">
              <nuxt-link :to="`/dashboard/profile/${scope.row.id}`"
                ><el-dropdown-item command=""
                  >Visualiser le profil</el-dropdown-item
                >
              </nuxt-link>
              <nuxt-link :to="`/dashboard/profile/${scope.row.id}/edit`">
                <el-dropdown-item command=""
                  >Modifier le profil</el-dropdown-item
                >
              </nuxt-link>
              <el-dropdown-item
                :command="{ action: 'impersonate', id: scope.row.user_id }"
              >
                Prendre sa place
              </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
          <nuxt-link v-else :to="`/dashboard/profile/${scope.row.id}/edit`">
            <el-button icon="el-icon-edit" size="mini" class="m-1">
              Modifier
            </el-button>
          </nuxt-link>
        </template>
      </el-table-column>
    </el-table>
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
      <div class="ml-auto">
        <el-button
          :loading="loadingExport"
          icon="el-icon-download"
          size="small"
          @click="onExport"
        >
          Export
        </el-button>
      </div>
    </div>
    <portal to="volet">
      <VoletProfile @updated="onUpdatedRow" />
    </portal>
  </div>
</template>

<script>
import TableWithVolet from '@/mixins/table-with-volet'
import TableWithFilters from '@/mixins/table-with-filters'
import fileDownload from 'js-file-download'

export default {
  mixins: [TableWithFilters, TableWithVolet],
  layout: 'dashboard',
  asyncData({ $api, store, error, params }) {
    if (!['admin'].includes(store.getters.contextRole)) {
      return error({ statusCode: 403 })
    }
  },
  data() {
    return {
      loadingExport: false,
    }
  },
  async fetch() {
    const { data } = await this.$api.fetchProfiles(
      {
        ...this.query,
        'filter[role]': 'responsable',
      },
      ['last_online_at', 'responsable_waiting_actions']
    )
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  computed: {},
  watch: {
    '$route.query': '$fetch',
  },
  created() {},
  methods: {
    handleCommand(command) {
      if (command.action == 'impersonate') {
        this.$store.dispatch('auth/impersonate', command.id)
      }
    },
    onExport() {
      this.loadingExport = true
      this.$api
        .exportProfilesResponsables({
          ...this.query,
          'filter[role]': 'responsable',
        })
        .then((response) => {
          this.loadingExport = false
          fileDownload(response.data, 'responsables.csv')
        })
        .catch((error) => {
          this.loadingExport = false
          console.log('exportProfilesResponsables', error)
        })
    },
  },
}
</script>
