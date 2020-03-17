<template>
  <div v-if="!$store.getters.loading" class="young-mission has-full-table">
    <div class="header px-12 flex">
      <div class="header-titles flex-1">
        <div class="text-m text-gray-600 uppercase">
          Volontaire
        </div>
        <div class="mb-8 flex">
          <div class="font-bold text-2xl text-gray-800">
            {{ young.first_name }} {{ young.last_name }}
          </div>
          <state-tag
            :state="young.state"
            class="relative ml-3"
            style="top: 1px"
          ></state-tag>
          <el-tag
            v-if="!young.regular_latitude && !young.regular_longitude"
            type="danger"
            class="m-1 ml-0"
            size="medium"
          >
            Non géolocalisé
          </el-tag>
          <el-tag
            v-if="!RegExp('^[^@]+@[^@]+\.[^@]{2,}$').test(young.email)"
            type="danger"
            class="m-1 ml-0"
            size="medium"
          >
            Email incorrect
          </el-tag>
        </div>
      </div>
      <router-link
        v-if="$store.getters.contextRole == 'admin'"
        :to="{ name: 'YoungFormEdit', params: { id: young.id } }"
      >
        <el-button type="secondary" icon="el-icon-edit"
          >Modifier la fiche</el-button
        >
      </router-link>
    </div>
    <div class="px-12 mb-12">
      <div class="mb-6 text-2xl">Informations</div>
      <young-infos :young="young"></young-infos>
    </div>
    <div v-if="young.mission" class="px-12 mb-12">
      <div class="mb-6 text-2xl">
        {{ young.mission.name }}
        <span class="text-sm text-gray-600"
          >({{ young.mission.structure.name }})</span
        >
      </div>
      <mission-infos v-if="mission" :mission="mission" />
    </div>
    <portal to="volet">
      <template>
        <el-form ref="youngForm" :model="form" label-position="top">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">
            Statut du dossier
          </div>
          <item-description>
            Vous pouvez sélectionner le statut du dossier. A noter que des
            notifications emails seront envoyées.
          </item-description>
          <el-form-item label="Statut" prop="state" class="flex-1">
            <el-select v-model="form.state" placeholder="Statut">
              <el-option
                v-for="item in $store.getters.taxonomies.young_workflow_states
                  .terms"
                v-show="item.label != 'En attente de mission'"
                :key="item.label"
                :label="item.value"
                :value="item.label"
              />
            </el-select>
          </el-form-item>
          <div class="flex pt-2">
            <el-button type="primary" :loading="loading" @click="onSubmit">
              Enregistrer
            </el-button>
          </div>
        </el-form>
      </template>
    </portal>
  </div>
</template>

<script>
import { getYoung } from "@/api/young";
import { getMission } from "@/api/mission";
import StateTag from "@/components/StateTag";
import YoungInfos from "@/components/infos/YoungInfos";
import MissionInfos from "@/components/infos/MissionInfos";
import ItemDescription from "@/components/forms/ItemDescription";
import { updateYoung } from "@/api/young";

export default {
  name: "Young",
  components: { StateTag, YoungInfos, MissionInfos, ItemDescription },
  props: {
    id: {
      type: Number,
      default: null
    }
  },
  data() {
    return {
      loading: false,
      young: {},
      mission: null,
      form: {}
    };
  },
  async created() {
    this.$store.commit("setLoading", true);
    const response = await getYoung(this.id);
    this.$store.commit("setLoading", false);
    this.young = response.data;
    this.form = { ...response.data };
    this.$store.commit("volet/show", { ...this.form });
    if (this.young.mission) {
      getMission(this.young.mission.id).then(response => {
        this.mission = response.data;
      });
    }
  },

  methods: {
    onSubmit() {
      this.$confirm("Êtes vous sur de vos changements ?", "Confirmation", {
        confirmButtonText: "Je confirme",
        cancelButtonText: "Annuler",
        type: "warning"
      }).then(() => {
        this.loading = true;
        updateYoung(this.form.id, this.form)
          .then(response => {
            this.loading = false;
            this.$message({
              type: "success",
              message: "Le volontaire a été mis à jour"
            });
            this.$store.commit("volet/setRow", response.data);
            this.young = response.data;
          })
          .catch(() => {
            this.loading = false;
          });
      });
    }
  }
};
</script>
