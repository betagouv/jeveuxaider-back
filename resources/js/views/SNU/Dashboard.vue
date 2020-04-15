<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">{{ $store.getters["user/contextRoleLabel"] }}</div>
        <div class="mb-12 font-bold text-2xl text-gray-800">Dashboard</div>
      </div>
      <div v-if="$store.getters.contextRole === 'admin'" class>
        <el-dropdown @command="handleCommand">
          <el-button :loading="loading" type="primary">Exporter les données</el-button>
          <el-dropdown-menu type="primary">
            <el-dropdown-item command="structures">Toutes les structures</el-dropdown-item>
            <el-dropdown-item command="missions">Toutes les missions</el-dropdown-item>
            <el-dropdown-item command="participations">Toutes les participations</el-dropdown-item>
            <el-dropdown-item command="profiles">Tous les utilisateurs</el-dropdown-item>
          </el-dropdown-menu>
        </el-dropdown>
      </div>
    </div>
    <div class="px-12">
      <card-mission-count label="Missions" name="missions" link="/dashboard/missions" />
      <card-participation-count
        label="Participations"
        name="participations"
        link="/dashboard/participations"
      />
    </div>

    <div class="px-12" v-if="$store.getters.contextRole != 'responsable'">
      <card-structure-count label="Structures" name="structures" link="/dashboard/structures" />
      <card-profile-count label="Utilisateurs" name="profiles" link="/dashboard/profiles" />
      <card-analytics label="Départements" name="analytics"></card-analytics>
    </div>
  </div>
</template>

<script>
import CardMissionCount from "@/components/CardMissionCount";
import CardParticipationCount from "@/components/CardParticipationCount";
import CardStructureCount from "@/components/CardStructureCount";
import CardProfileCount from "@/components/CardProfileCount";
import CardAnalytics from "@/components/CardAnalytics";
import { exportTable } from "@/api/app";
import fileDownload from "js-file-download";

export default {
  name: "Dashboard",
  components: {
    CardMissionCount,
    CardParticipationCount,
    CardStructureCount,
    CardProfileCount,
    CardAnalytics
  },
  data() {
    return {
      loading: false
    };
  },
  computed: {},
  methods: {
    handleCommand(command) {
      this.loading = true;
      this.export(command);
    },
    export(table){
      exportTable(table)
        .then(response => {
          this.loading = false;
          fileDownload(response.data, `${table}.csv`);
        })
        .catch(error => {
          console.log(error);
        });
    }
    
  }
};
</script>
