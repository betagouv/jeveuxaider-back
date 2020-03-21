<template>
  <Volet>
    <template v-slot:content="{ row }">
      <div class="text-xs text-gray-600 uppercase text-center mt-16 mb-12">
        {{ row.statut_juridique }}
      </div>
      <el-card shadow="hover" class="overflow-visible relative">
        <div slot="header" class="clearfix flex flex-col items-center">
          <div class="-mt-10">
            <el-avatar
              v-if="row.logo"
              :src="`${row.logo}`"
              class="w-10 rounded-full border"
            />
            <el-avatar v-else class="bg-primary">
              {{ row.name[0] }}
            </el-avatar>
          </div>
          <router-link
            :to="{
              name: 'Structure',
              params: { id: row.id }
            }"
            ><div class="font-bold text-lg text-primary mb-3 text-center">
              {{ row.name }}
            </div>
          </router-link>
          <div class="flex items-center">
            <router-link
              :to="{
                name: 'StructureFormEdit',
                params: { id: row.id }
              }"
            >
              <el-button icon="el-icon-edit" type="mini">Modifier</el-button>
            </router-link>
            <button
              v-if="
                $store.getters.contextRole == 'admin' ||
                  $store.getters.contextRole == 'referent'
              "
              type="button"
              class="ml-2 el-button is-plain el-button--danger el-button--mini"
              @click="onClickDelete"
            >
              <i class="el-icon-delete" />
            </button>
          </div>
        </div>
        <div class="flex flex-wrap items-center justify-center mb-4">
          <el-tag
            v-if="row.is_reseau"
            size="small"
            class="m-1 ml-0"
            type="danger"
            >Tête de réseau</el-tag
          >
          <el-tag v-if="row.reseau_id" class="m-1 ml-0" size="small">{{
            row.reseau_id | reseauFromValue
          }}</el-tag>

          <el-tag
            v-if="row.department"
            type="warning"
            class="m-1 ml-0"
            size="small"
            >{{ row.department | fullDepartmentFromValue }}</el-tag
          >
          <el-tooltip
            v-if="row.ceu"
            class="item"
            effect="dark"
            :content="row.structure_publique_etat_type"
            placement="top"
          >
            <el-tag size="small" class="m-1 ml-0" type="info">CEU</el-tag>
          </el-tooltip>
        </div>
        <structure-infos :structure="row"></structure-infos>

        <div
          v-if="row.missions_count > 0"
          class="font-bold text-lg text-primary mb-3 text-center mt-8"
        >
          <router-link
            :to="{
              name: 'Structure',
              params: { id: row.id }
            }"
          >
            <el-button v-if="row.missions_count == 1" type="primary"
              >Voir la mission</el-button
            >
            <el-button v-else type="primary"
              >Voir les {{ row.missions_count }} missions</el-button
            >
          </router-link>
        </div>
      </el-card>
      <!-- <el-form ref="structureForm" :model="form" label-position="top">
        <div class="mb-6 mt-12 flex text-xl text-gray-800">
          Réseau national
        </div>
        <div v-if="$store.getters.contextRole !== 'referent'">
          <item-description>
            Si votre structure est membre d'un réseau national (La Croix Rouge,
            Armée du Salut...), renseignez son nom. Vous permettez ainsi au
            superviseur de votre réseau de visualiser les missions et
            volontaires rattachés à votre structure.
          </item-description>
          <el-form-item label="Réseau national" prop="reseau" class="flex-1">
            <el-select
              v-model="form.reseau_id"
              clearable
              placeholder="Réseau national"
            >
              <el-option
                v-for="item in $store.getters.reseaux"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              ></el-option>
            </el-select>
          </el-form-item>
        </div>
        <el-form-item label="Tête de réseau" prop="is_reseau" class="flex-1">
          <el-checkbox v-model="form.is_reseau">
            <span class="text-xs font-light text-gray-600">
              Cette structure est une tête de réseau
            </span>
          </el-checkbox>
        </el-form-item>
        <div class="flex pt-2">
          <el-button type="primary" :loading="loading" @click="onSubmit">
            Enregistrer
          </el-button>
        </div>
      </el-form> -->
      <div class="mb-6 mt-12 flex text-xl text-gray-800">
        Équipe ({{ form.members.length }})
      </div>
      <item-description>
        Vous pouver
        <router-link
          :to="{
            name: 'StructureMembers',
            params: {
              id: form.id
            }
          }"
        >
          <span class="underline cursor-pointer">gérer l'équipe</span>
        </router-link>
        ou
        <router-link
          :to="{
            name: 'StructureMembersAdd',
            params: {
              id: form.id
            }
          }"
        >
          <span class="underline cursor-pointer"
            >ajouter un membre</span
          > </router-link
        >.
      </item-description>
      <div
        v-for="member in form.members"
        :key="member.id"
        class="member py-4 px-6"
      >
        <member-teaser :member="member"></member-teaser>
      </div>
    </template>
  </Volet>
</template>

<script>
import Volet from "@/layout/components/Volet";
import { updateStructure, deleteStructure } from "@/api/structure";
import VoletRow from "@/mixins/VoletRow";
import ItemDescription from "@/components/forms/ItemDescription";
import MemberTeaser from "@/components/MemberTeaser";
import StructureInfos from "@/components/infos/StructureInfos";

export default {
  name: "StructureVolet",
  components: {
    Volet,
    ItemDescription,
    MemberTeaser,
    StructureInfos
  },
  mixins: [VoletRow],
  data() {
    return {
      loading: false,
      form: {}
    };
  },
  methods: {
    onClickDelete() {
      if (this.row.missions_count > 0) {
        this.$alert(
          "Il est impossible de supprimer une structure qui contient des missions.",
          "Supprimer la structure",
          {
            confirmButtonText: "Retour",
            type: "warning"
          }
        );
      } else {
        this.$confirm(
          `La structure ${this.row.name} sera définitivement supprimée de la plateforme. Voulez-vous continuer ?`,
          "Supprimer la structure",
          {
            confirmButtonText: "Supprimer",
            confirmButtonClass: "el-button--danger",
            cancelButtonText: "Annuler",
            type: "error"
          }
        ).then(() => {
          deleteStructure(this.row.id).then(() => {
            this.$message({
              type: "success",
              message: `La structure ${this.row.name} a été supprimée.`
            });
            this.$emit("deleted", this.row);
            this.$store.commit("volet/setRow", null);
          });
        });
      }
    },
    onSubmit() {
      this.$confirm("Êtes vous sur de vos changements ?", "Confirmation", {
        confirmButtonText: "Je confirme",
        cancelButtonText: "Annuler",
        type: "warning"
      }).then(() => {
        this.loading = true;
        updateStructure(this.form.id, this.form)
          .then(response => {
            this.loading = false;
            this.$message({
              type: "success",
              message: "La structure a été mise à jour"
            });
            this.$emit("updated", response.data);
          })
          .catch(error => {
            this.loading = false;
            this.errors = error.response.data.errors;
          });
      });
    }
  }
};
</script>
