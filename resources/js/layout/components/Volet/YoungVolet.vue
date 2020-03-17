<template>
  <Volet>
    <template v-slot:content="{ row }">
      <el-card shadow="hover" class="overflow-visible mt-24">
        <div slot="header" class="clearfix flex flex-col items-center">
          <div class="-mt-10">
            <el-avatar class="bg-primary">
              {{ row.first_name[0] }}{{ row.last_name[0] }}
            </el-avatar>
          </div>
          <router-link
            :to="{
              name: 'Young',
              params: { id: row.id }
            }"
          >
            <div class="font-bold text-lg mb-3 text-primary">
              {{ row.full_name }}
            </div>
          </router-link>
          <div
            v-if="
              $store.getters.contextRole == 'admin' ||
                $store.getters.contextRole == 'referent'
            "
            class="flex items-center"
          >
            <router-link
              :to="{
                name: 'YoungFormEdit',
                params: { id: row.id }
              }"
            >
              <el-button icon="el-icon-edit" type="mini">Modifier</el-button>
            </router-link>
            <button
              type="button"
              class="ml-2 el-button is-plain el-button--danger el-button--mini"
              @click="onClickDelete"
            >
              <i class="el-icon-delete" />
            </button>
          </div>
        </div>
        <div class="flex items-center justify-center mb-4">
          <el-tag
            v-if="row.department"
            type="info"
            class="m-1 ml-0"
            size="small"
            >{{ row.department | fullDepartmentFromValue }}</el-tag
          >
        </div>
        <young-infos :young="row"></young-infos>
      </el-card>

      <div class="mb-4 mt-12 flex text-xl text-gray-800">
        Mission proposée
      </div>
      <div v-if="row.mission_id" class="">
        <div class="flex items-center">
          <div>
            <el-avatar
              class="bg-primary rounded-full"
              :src="`${row.mission.structure.logo}`"
            >
              {{ row.mission.structure.name[0] }}
            </el-avatar>
          </div>
          <div class="mx-2 flex-1">
            <div class="text-gray-800">
              {{ row.mission.name }}
            </div>
            <div class="uppercase text-xs text-secondary">
              {{ row.mission.structure.name }}
            </div>
          </div>
          <div>
            <el-button
              v-if="
                $store.getters.contextRole == 'admin' ||
                  $store.getters.contextRole == 'referent'
              "
              type="danger"
              icon="el-icon-delete"
              size="mini"
              class="is-plain"
              @click="onClickUnassignMission"
            ></el-button>
          </div>
        </div>
      </div>
      <div v-else>
        <div class="text-gray-600 text-sm">Aucune mission.</div>
        <div class="mt-6">
          <router-link
            :to="{
              name: 'YoungAssignation',
              params: {
                id: form.id
              }
            }"
            ><el-button type="primary" icon="el-icon-search">
              Trouver une mission
            </el-button></router-link
          >
        </div>
      </div>

      <template v-if="row.state != 'En attente de mission'">
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
    </template>
  </Volet>
</template>

<script>
import Volet from "@/layout/components/Volet";
import { updateYoung, deleteYoung } from "@/api/young";
import VoletRow from "@/mixins/VoletRow";
import ItemDescription from "@/components/forms/ItemDescription";
import YoungInfos from "@/components/infos/YoungInfos";

export default {
  name: "YoungVolet",
  components: { Volet, ItemDescription, YoungInfos },
  mixins: [VoletRow],
  data() {
    return {
      loading: false,
      form: {}
    };
  },
  methods: {
    onClickDelete() {
      if (this.row.mission) {
        this.$alert(
          "Il est impossible de supprimer un volontaire déjà relié à une mission.",
          "Supprimer le volontaire",
          {
            confirmButtonText: "Retour",
            type: "warning"
          }
        );
      } else {
        this.$confirm(
          `Le volontaire de ${this.row.full_name} sera définitivement supprimé de la plateforme. Voulez-vous continuer ?`,
          "Supprimer le volontaire",
          {
            confirmButtonText: "Supprimer",
            confirmButtonClass: "el-button--danger",
            cancelButtonText: "Annuler",
            type: "error"
          }
        ).then(() => {
          deleteYoung(this.row.id).then(() => {
            this.$message({
              type: "success",
              message: `Le volontaire ${this.row.full_name} a été supprimé.`
            });
            this.$emit("deleted", this.row);
            this.$store.commit("volet/setRow", null);
          });
        });
      }
    },
    onClickUnassignMission() {
      this.$confirm(
        "Vous êtes sur le point de désassigner la mission de " +
          this.row.full_name,
        "Confirmation",
        {
          confirmButtonText: "Je confirme",
          cancelButtonText: "Annuler",
          type: "error"
        }
      ).then(() => {
        updateYoung(this.row.id, {
          ...this.row,
          mission_id: null,
          state: "En attente de mission"
        }).then(response => {
          this.$message({
            type: "success",
            message: "La mission a été dédassignée de " + this.row.full_name
          });
          this.$store.commit("volet/setRow", response.data);
          this.$emit("updated", response.data);
        });
      });
    },
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
            this.$emit("updated", response.data);
          })
          .catch(() => {
            this.loading = false;
          });
      });
    }
  }
};
</script>
