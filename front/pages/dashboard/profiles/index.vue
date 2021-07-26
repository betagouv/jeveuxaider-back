<template>
  <div class="has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters.contextRoleLabel }}
        </div>
        <div class="mb-8 font-bold text-2-5xl text-gray-800">Utilisateurs</div>
      </div>
      <div v-if="$store.getters.contextRole === 'admin'" class="">
        <nuxt-link to="/dashboard/profiles/invitations/add">
          <el-button type="primary"> Inviter un utilisateur </el-button>
        </nuxt-link>
      </div>
    </div>
    <div v-if="$store.getters.contextRole === 'admin'" class="px-12 mb-12">
      <TabsProfiles index="/dashboard/profiles" />
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <SearchFiltersQueryMain
          name="search"
          placeholder="Rechercher par nom, prénom, email..."
          :initial-value="query['filter[search]']"
          @changed="onFilterChange"
        />
        <el-badge v-if="activeFilters" :value="activeFilters" type="primary">
          <el-button
            icon="el-icon-s-operation"
            class="ml-4"
            @click="showFilters = !showFilters"
          >
            Filtres avancés
          </el-button>
        </el-badge>
        <el-button
          v-else
          icon="el-icon-s-operation"
          class="ml-4"
          @click="showFilters = !showFilters"
        >
          Filtres avancés
        </el-button>
      </div>
      <div v-if="showFilters" class="flex flex-wrap gap-4 mb-4">
        <SearchFiltersQuery
          name="role"
          label="Rôle"
          :value="query['filter[role]']"
          :options="rolesList"
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          v-if="$store.getters.contextRole === 'admin'"
          name="department"
          label="Département"
          :value="query['filter[department]']"
          :options="
            $store.getters.taxonomies.departments.terms.map((term) => {
              return {
                label: `${term.value} - ${term.label}`,
                value: term.value,
              }
            })
          "
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          name="zips"
          label="Codes postaux"
          multiple
          allow-create
          default-first-option
          :value="query['filter[zips]']"
          :options="[]"
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          type="select"
          name="domaines"
          :value="query['filter[domaines]']"
          label="Domaine d'action"
          :options="
            domaines.map((domaine) => {
              return {
                label: domaine.name.fr,
                value: domaine.id,
              }
            })
          "
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          name="is_visible"
          label="Visible"
          :value="query['filter[is_visible]']"
          :options="[
            { label: 'Oui', value: true },
            { label: 'Non', value: false },
          ]"
          @changed="onFilterChange"
        />
        <SearchFiltersQuery
          name="min_participations"
          label="Participations"
          :value="query['filter[min_participations]']"
          :options="[
            { label: 'Au moins 1', value: 1 },
            { label: 'Au moins 3', value: 3 },
            { label: 'Au moins 5', value: 5 },
            { label: 'Au moins 10', value: 10 },
          ]"
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
      <el-table-column label="Contextes" min-width="350">
        <template slot-scope="scope">
          <TagProfileRoles :profile="scope.row" />
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
            class="text-0 leading-none"
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
      <!-- <div class="ml-auto">
        <el-button icon="el-icon-download" size="small" @click="onExport">
          Export
        </el-button>
      </div> -->
    </div>
    <portal to="volet">
      <VoletProfile @updated="onUpdatedRow" />
    </portal>
  </div>
</template>

<script>
import TableWithVolet from '@/mixins/table-with-volet'
import TableWithFilters from '@/mixins/table-with-filters'
// import fileDownload from 'js-file-download'

export default {
  mixins: [TableWithFilters, TableWithVolet],
  layout: 'dashboard',
  async asyncData({ $api, store, error, params }) {
    if (
      !['admin', 'referent', 'referent_regional'].includes(
        store.getters.contextRole
      )
    ) {
      return error({ statusCode: 403 })
    }
    const domaines = await $api.fetchTags({ 'filter[type]': 'domaine' })
    const templates = await $api.fetchMissionTemplates({ pagination: 1000 })

    return {
      domaines: domaines.data.data,
      templates: templates.data.data,
    }
  },
  data() {
    return {
      loading: true,
    }
  },
  async fetch() {
    const { data } = await this.$api.fetchProfiles(this.query, [
      'last_online_at',
      'roles',
      'skills',
      'domaines',
    ])
    this.tableData = data.data
    this.totalRows = data.total
    this.fromRow = data.from
    this.toRow = data.to
  },
  computed: {
    rolesList() {
      if (
        this.$store.getters.contextRole == 'admin' ||
        this.$store.getters.contextRole == 'analyste'
      ) {
        return [
          { label: 'Modérateur', value: 'admin' },
          { label: 'Tête de réseau', value: 'superviseur' },
          { label: 'Analyste', value: 'analyste' },
          { label: 'Référent départemental', value: 'referent' },
          { label: 'Référent régional', value: 'referent_regional' },
          { label: 'Responsable', value: 'responsable' },
          { label: 'Bénévole', value: 'volontaire' },
        ]
      } else {
        return [
          { label: 'Responsable', value: 'responsable' },
          { label: 'Bénévole', value: 'volontaire' },
        ]
      }
    },
  },
  watch: {
    '$route.query': '$fetch',
  },
  methods: {
    handleCommand(command) {
      if (command.action == 'impersonate') {
        this.$store.dispatch('auth/impersonate', command.id)
      }
    },
    // onExport() {
    //   this.loading = true
    //   exportProfiles(this.query)
    //     .then(() => {
    //       this.loading = false
    //       // fileDownload(response.data, 'utilisateurs.csv')
    //       this.$message.success({
    //         message:
    //           "Votre export est en cours de génération... Vous recevrez un e-mail lorsqu'il sera prêt !",
    //         type: 'success',
    //       })
    //     })
    //     .catch((error) => {
    //       console.log('exportProfiles', error)
    //     })
    // },
  },
}
</script>
