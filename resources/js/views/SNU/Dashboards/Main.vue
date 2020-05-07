<template>
  <div class="dashboard mb-6">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">{{ $store.getters["user/contextRoleLabel"] }}</div>
        <div class="mb-12 font-bold text-2xl text-gray-800">Tableau de bord</div>
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
    <div class="px-12 mb-12">
      <el-menu :default-active="activeIndex" mode="horizontal" @select="handleSelect">
        <el-menu-item index="main" active>Général</el-menu-item>
        <el-menu-item
          v-if="$store.getters.contextRole != 'responsable'"
          index="structures"
        >Structures</el-menu-item>
        <el-menu-item index="missions">Missions</el-menu-item>
        <el-menu-item index="participations">Participations</el-menu-item>
        <el-menu-item v-if="$store.getters.contextRole != 'responsable'" index="profiles">Utilisateurs</el-menu-item>
        <el-menu-item
          v-if="$store.getters.contextRole != 'responsable'"
          index="departments"
        >Départements</el-menu-item>
      </el-menu>
    </div>
    <div class="px-12">
      <div class="flex flex-wrap">
        <card-count
          v-if="$store.getters.contextRole != 'responsable'"
          label="Structures"
          name="structures"
          link="/dashboard/structures"
        ></card-count>
        <card-count label="Missions" name="missions" link="/dashboard/missions"></card-count>
        <card-count label="Participations" name="participations" link="/dashboard/participations"></card-count>
        <card-count
          v-if="$store.getters.contextRole != 'responsable'"
          label="Utilisateurs"
          name="profiles"
          link="/dashboard/profiles"
        ></card-count>
      </div>
    </div>
  </div>
</template>

<script>
import CardCount from "@/components/CardCount";
import { exportTable } from "@/api/app";
import fileDownload from "js-file-download";

export default {
  name: "DashboardMain",
  components: {
    CardCount
  },
  data() {
    return {
      activeIndex: "main",
      loading: false
    };
  },
  computed: {},
  methods: {
    handleSelect(index) {
      index == "main"
        ? this.$router.push("/dashboard")
        : this.$router.push(`/dashboard/stats/${index}`);
    },
    handleCommand(command) {
      this.loading = true;
      this.export(command);
    },
    export(table) {
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
