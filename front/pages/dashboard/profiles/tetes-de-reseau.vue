<template>
  <div class="profiles has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-8 font-bold text-[1.75rem] text-[#242526]">
          Utilisateurs - Têtes de réseaux
        </div>
      </div>
      <div v-if="$store.getters.contextRole === 'admin'" class="">
        <nuxt-link to="/dashboard/profiles/invitations/add">
          <el-button type="primary"> Inviter un utilisateur </el-button>
        </nuxt-link>
      </div>
    </div>
    <div class="px-12 mb-12">
      <TabsProfiles index="/dashboard/profiles/tetes-de-reseau" />
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <SearchFiltersQueryMain
          name="search"
          placeholder="Rechercher par nom, prénom, email..."
          :initial-value="query['filter[search]']"
          @changed="onFilterChange"
        />
        <el-badge :value="activeFilters || null" type="primary">
          <el-button
            icon="el-icon-s-operation"
            class="!ml-4"
            @click="showFilters = !showFilters"
          >
            Filtres avancés
          </el-button>
        </el-badge>
      </div>
      <div v-if="showFilters" class="flex flex-wrap gap-4 mb-4">
        <SearchFiltersQuery
          v-if="$store.getters.contextRole === 'admin'"
          name="referent_region"
          label="Département"
          multiple
          :value="query['filter[referent_region]']"
          :options="
            $store.getters.taxonomies.regions.terms.map((term) => {
              return {
                label: `${term.label}`,
                value: term.value,
              }
            })
          "
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
      <el-table-column label="Région" min-width="300">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.tete_de_reseau.name }}
          </div>
          <div class="font-light text-gray-600 text-xs">
            {{ scope.row.tete_de_reseau_waiting_actions.antennes }} antennes
          </div>
        </template>
      </el-table-column>
      <el-table-column label="# Missions" width="150" align="center">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{
              scope.row.tete_de_reseau_waiting_actions.missions | formatNumber
            }}
          </div>
          <div class="font-light text-gray-600 text-xs">en attente</div>
        </template>
      </el-table-column>
      <el-table-column label="# Participations" width="150" align="center">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{
              scope.row.tete_de_reseau_waiting_actions.participations
                | formatNumber
            }}
          </div>
          <div class="font-light text-gray-600 text-xs">en attente</div>
        </template>
      </el-table-column>
      <el-table-column label="# Actions" width="150" align="center">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.tete_de_reseau_waiting_actions.total | formatNumber }}
          </div>
          <div class="font-light text-gray-600 text-xs">en attente</div>
        </template>
      </el-table-column>
      <el-table-column
        v-if="$store.getters.contextRole === 'admin'"
        label="Actions"
        width="250"
        class-name="dropdown-wrapper"
      >
        <template slot-scope="scope">
          <el-dropdown
            split-button
            size="small"
            @command="handleCommand"
            @click.native.stop
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
import fileDownload from 'js-file-download'
import TableWithVolet from '@/mixins/table-with-volet'
import TableWithFilters from '@/mixins/table-with-filters'

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
        'filter[role]': 'tete_de_reseau',
      },
      [
        'last_online_at',
        'roles',
        'skills',
        'domaines',
        'tete_de_reseau_waiting_actions',
      ]
    )
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  methods: {
    handleCommand(command) {
      if (command.action == 'impersonate') {
        this.$store.dispatch('auth/impersonate', command.id)
      }
    },
    onExport() {
      this.loadingExport = true
      this.$api
        .exportProfilesTetesDeReseau({
          ...this.query,
          'filter[role]': 'tete_de_reseau',
        })
        .then((response) => {
          this.loadingExport = false
          fileDownload(response.data, 'tetes-de-reseau.csv')
        })
        .catch((error) => {
          this.loadingExport = false
          this.$message.error({
            message: error.response.data.message,
          })
        })
    },
  },
}
</script>
