<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          {{ $store.getters['user/contextRoleLabel'] }}
        </div>
        <div class="mb-12 font-bold text-2xl text-gray-800">
          Tableau de bord
        </div>
      </div>
      <div v-if="$store.getters.contextRole === 'admin'" class>
        <el-dropdown @command="handleCommand">
          <el-button :loading="loading" type="primary">
            Exporter les données
          </el-button>
          <el-dropdown-menu type="primary">
            <el-dropdown-item command="structures">
              Toutes les structures
            </el-dropdown-item>
            <el-dropdown-item command="missions">
              Toutes les missions
            </el-dropdown-item>
            <el-dropdown-item command="participations">
              Toutes les participations
            </el-dropdown-item>
            <el-dropdown-item command="profiles">
              Tous les utilisateurs
            </el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </div>
    </div>
    <div class="px-12 mb-12">
      <dashboard-menu index="main" />
    </div>
    <div class="px-12">
      <reminder-referent
        v-if="$store.getters.contextRole === 'referent'"
        class="mb-12"
      />
      <reminder-responsable
        v-if="$store.getters.contextRole === 'responsable'"
        class="mb-12"
      />
    </div>
    <div class="px-12">
      <div class="flex flex-wrap">
        <card-count-places-left
          v-if="$store.getters.contextRole != 'responsable'"
        />
        <card-count
          v-if="$store.getters.contextRole != 'responsable'"
          label="Structures"
          name="structures"
          link="/dashboard/stats/structures"
        />
        <card-count
          label="Missions"
          name="missions"
          link="/dashboard/stats/missions"
        />
        <card-count
          label="Participations"
          name="participations"
          link="/dashboard/stats/participations"
        />
        <card-count
          v-if="$store.getters.contextRole != 'responsable'"
          label="Utilisateurs"
          name="profiles"
          link="/dashboard/stats/profiles"
        />
      </div>
    </div>
  </div>
</template>

<script>
import DashboardMenu from '@/components/DashboardMenu'
import CardCount from '@/components/CardCount'
import CardCountPlacesLeft from '@/components/CardCountPlacesLeft'
import ReminderReferent from '@/components/ReminderReferent'
import ReminderResponsable from '@/components/ReminderResponsable'
import { exportTable } from '@/api/app'
import fileDownload from 'js-file-download'

export default {
  name: 'DashboardMain',
  components: {
    DashboardMenu,
    CardCount,
    CardCountPlacesLeft,
    ReminderReferent,
    ReminderResponsable,
  },
  data() {
    return {
      loading: false,
    }
  },
  computed: {},
  methods: {
    handleCommand(command) {
      this.loading = true
      this.export(command)
    },
    export(table) {
      exportTable(table)
        .then((response) => {
          this.loading = false
          fileDownload(response.data, `${table}.csv`)
        })
        .catch((error) => {
          console.log(error)
        })
    },
  },
}
</script>
