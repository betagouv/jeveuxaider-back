<template>
  <div class="profiles has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-8 font-bold text-2xl text-gray-800">Utilisateurs</div>
      </div>
      <div v-if="$store.getters.contextRole === 'admin'" class="">
        <el-dropdown>
          <el-button type="primary"> Inviter un utilisateur </el-button>
          <el-dropdown-menu type="primary">
            <router-link
              :to="{
                name: 'DashboardProfileFormAdd',
                params: { role: 'superviseur' },
              }"
            >
              <el-dropdown-item>Superviseur national</el-dropdown-item>
            </router-link>
            <router-link
              :to="{
                name: 'DashboardProfileFormAdd',
                params: { role: 'referent' },
              }"
            >
              <el-dropdown-item>Référent départemental</el-dropdown-item>
            </router-link>
            <router-link
              :to="{
                name: 'DashboardProfileFormAdd',
                params: { role: 'referent_regional' },
              }"
            >
              <el-dropdown-item>Référent régional</el-dropdown-item>
            </router-link>
            <router-link
              :to="{
                name: 'DashboardProfileFormAdd',
                params: { role: 'analyste' },
              }"
            >
              <el-dropdown-item>Datas analyste</el-dropdown-item>
            </router-link>
          </el-dropdown-menu>
        </el-dropdown>
      </div>
    </div>
    <div v-if="$store.getters.contextRole === 'admin'" class="px-12 mb-12">
      <profiles-menu index="/dashboard/profiles"></profiles-menu>
    </div>
    <div class="px-12 mb-3 flex flex-wrap">
      <div class="flex w-full mb-4">
        <query-main-search-filter
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
      <div v-if="showFilters" class="flex flex-wrap">
        <query-filter
          name="role"
          label="Rôle"
          :value="query['filter[role]']"
          :options="rolesList"
          @changed="onFilterChange"
        />
        <query-filter
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
        <query-filter
          type="select"
          name="collectivity"
          :value="query['filter[collectivity]']"
          label="Collectivité"
          :options="
            collectivities.map((collectivity) => {
              return {
                label: collectivity.name,
                value: collectivity.id,
              }
            })
          "
          @changed="onFilterChange"
        />
        <query-filter
          name="zips"
          label="Codes postaux"
          multiple
          allow-create
          default-first-option
          :value="query['filter[zips]']"
          :options="[]"
          @changed="onFilterChange"
        />
        <query-filter
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
        <query-filter
          name="is_visible"
          label="Visible"
          :value="query['filter[is_visible]']"
          :options="[
            { label: 'Oui', value: true },
            { label: 'Non', value: false },
          ]"
          @changed="onFilterChange"
        />
        <query-filter
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
      v-loading="loading"
      :data="tableData"
      :highlight-current-row="true"
      @row-click="onClickedRow"
    >
      <el-table-column width="70" align="center">
        <template slot-scope="scope">
          <el-avatar
            :src="scope.row.image ? scope.row.image.thumb : null"
            class="bg-primary text-white"
          >
            {{ scope.row.short_name }}
          </el-avatar>
        </template>
      </el-table-column>
      <el-table-column label="Email" min-width="300">
        <template slot-scope="scope">
          <div class="text-gray-900">
            {{ scope.row.full_name }}
          </div>
          <div class="font-light text-gray-600 text-xs">
            {{ scope.row.email }}
          </div>
        </template>
      </el-table-column>
      <el-table-column label="Contextes" min-width="350">
        <template slot-scope="scope">
          <profile-roles-tags :profile="scope.row" />
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
      >
        <template slot-scope="scope">
          <el-dropdown
            v-if="$store.getters.contextRole === 'admin' && scope.row.user_id"
            split-button
            size="small"
            @command="handleCommand"
          >
            Choisissez une action
            <el-dropdown-menu slot="dropdown">
              <router-link
                :to="{
                  name: 'DashboardProfile',
                  params: { id: scope.row.id },
                }"
                ><el-dropdown-item command=""
                  >Visualiser le profil</el-dropdown-item
                >
              </router-link>
              <router-link
                :to="{
                  name: 'DashboardProfileFormEdit',
                  params: { id: scope.row.id },
                }"
              >
                <el-dropdown-item command=""
                  >Modifier le profil</el-dropdown-item
                >
              </router-link>
              <el-dropdown-item
                :command="{ action: 'impersonate', id: scope.row.user_id }"
              >
                Prendre sa place
              </el-dropdown-item>
            </el-dropdown-menu>
          </el-dropdown>
          <router-link
            v-else
            :to="{
              name: 'DashboardProfileFormEdit',
              params: { id: scope.row.id },
            }"
          >
            <el-button icon="el-icon-edit" size="mini" class="m-1">
              Modifier
            </el-button>
          </router-link>
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
      <profile-volet @updated="onUpdatedRow" />
    </portal>
  </div>
</template>

<script>
import { fetchProfiles, exportProfiles } from '@/api/user'
import TableWithVolet from '@/mixins/TableWithVolet'
import TableWithFilters from '@/mixins/TableWithFilters'
import QueryFilter from '@/components/QueryFilter.vue'
import QueryMainSearchFilter from '@/components/QueryMainSearchFilter.vue'
import ProfileVolet from '@/layouts/components/Volet/ProfileVolet.vue'
import ProfileRolesTags from '@/components/ProfileRolesTags.vue'
import fileDownload from 'js-file-download'
import { Message } from 'element-ui'
import { fetchTags, fetchCollectivities } from '@/api/app'
import ProfilesMenu from '@/components/ProfilesMenu.vue'

export default {
  name: 'DashboardProfiles',
  components: {
    ProfileRolesTags,
    ProfileVolet,
    QueryFilter,
    QueryMainSearchFilter,
    ProfilesMenu,
  },
  mixins: [TableWithVolet, TableWithFilters],
  data() {
    return {
      loading: true,
      tableData: [],
      totalRows: 0,
      domaines: [],
      collectivities: [],
    }
  },
  computed: {
    rolesList() {
      if (
        this.$store.getters.contextRole == 'admin' ||
        this.$store.getters.contextRole == 'analyste'
      ) {
        return [
          { label: 'Modérateur', value: 'admin' },
          { label: 'Superviseur', value: 'superviseur' },
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
  created() {
    fetchTags({ 'filter[type]': 'domaine' }).then((res) => {
      this.domaines = res.data.data
    })
    fetchCollectivities({
      'filter[state]': 'validated',
      pagination: 1000,
    }).then((res) => {
      this.collectivities = res.data.data
    })
  },
  methods: {
    fetchRows() {
      return fetchProfiles(
        {
          ...this.query,
        },
        ['roles', 'has_user', 'skills', 'domaines']
      )
    },
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
    //       Message({
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
