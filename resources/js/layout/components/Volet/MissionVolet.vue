<template>
  <Volet>
    <template v-slot:content="{ row }">
      <div class="text-xs text-gray-600 uppercase text-center mt-8 mb-12">
        {{ row.structure.name }}
      </div>
      <el-card
        shadow="hover"
        class="overflow-visible"
      >
        <div
          slot="header"
          class="clearfix flex flex-col items-center"
        >
          <div class="-mt-10">
            <el-avatar
              v-if="row.structure.logo"
              :src="`${row.structure.logo}`"
              class="w-10 rounded-full border"
            />
            <el-avatar
              v-else
              class="bg-primary"
            >
              {{ row.structure.name[0] }}
            </el-avatar>
          </div>
          <div class="font-bold text-lg text-center my-3 flex">
            {{ row.name|labelFromValue('mission_domaines') }}
          </div>
          <div class="flex items-center">
            <router-link
              :to="{
                name: 'MissionFormEdit',
                params: { id: row.id }
              }"
            >
              <el-button
                icon="el-icon-edit"
                type="mini"
              >
                Modifier
              </el-button>
            </router-link>
            <button
              v-if="
                $store.getters.contextRole == 'admin' ||
                  $store.getters.contextRole == 'referent' ||
                  $store.getters.contextRole == 'referent_regional'
              "
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
            type="warning"
            class="m-1 ml-0"
            size="small"
          >
            {{ row.department | fullDepartmentFromValue }}
          </el-tag>
        </div>
        <mission-infos :mission="row" />
      </el-card>
      <el-form
        ref="missionForm"
        :model="form"
        label-position="top"
      >
        <template v-if="showAskValidation">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">
            Publier la mission
          </div>
          <item-description>
            Une fois votre mission complétée, vous pouvez la publier pour qu'elle soit proposée aux utilisateurs.
          </item-description>
          <div class="flex pt-2">
            <el-button
              type="primary"
              :loading="loading"
              @click="onAskValidationSubmit"
            >
              Publier la mission
            </el-button>
          </div>
        </template>
        <template v-if="showStatut">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">
            Statut de la mission
          </div>
          <item-description>
            Vous pouvez sélectionner le statut de la mission. A noter que des
            notifications emails seront envoyées.
          </item-description>
          <el-form-item
            label="Statut"
            prop="state"
            class="flex-1"
          >
            <el-select
              v-model="form.state"
              placeholder="Statut"
            >
              <el-option
                v-for="item in statesAvailable"
                :key="item.label"
                :label="item.value"
                :value="item.label"
              />
            </el-select>
          </el-form-item>
        </template>
        <!-- <template v-if="showTuteur">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">Responsable de la mission</div>
          <item-description>
            Sélectionner le responsable qui va s'occuper de la mission. Vous pouvez
            également
            <router-link
              class="underline"
              :to="{
                name: 'StructureMembersAdd',
                params: { id: form.structure.id }
              }"
            >ajouter un nouveau responsable</router-link>à votre équipe.
          </item-description>
          <el-form-item label="Responsable" prop="tuteur_id" class="flex-1">
            <el-select v-model="form.tuteur_id" placeholder="Sélectionner un responsable">
              <el-option
                v-for="item in form.structure.members"
                :key="item.id"
                :label="item.full_name"
                :value="item.id"
              ></el-option>
            </el-select>
          </el-form-item>
        </template> -->
        <div
          v-if="showStatut"
          class="flex pt-2"
        >
          <el-button
            type="primary"
            :loading="loading"
            @click="onSubmit"
          >
            Enregistrer
          </el-button>
        </div>
      </el-form>
    </template>
  </Volet>
</template>

<script>
import Volet from "@/layout/components/Volet";
import { updateMission, deleteMission } from "@/api/mission";
import VoletRow from "@/mixins/VoletRow";
import ItemDescription from "@/components/forms/ItemDescription";
import MissionInfos from "@/components/infos/MissionInfos";

export default {
  name: "MissionVolet",
  components: { Volet, ItemDescription, MissionInfos },
  mixins: [VoletRow],
  data() {
    return {
      loading: false
    };
  },
  computed: {
    showAskValidation() {
      return this.$store.getters.contextRole == "responsable" &&
        this.row.state == "Brouillon"
        ? true
        : false;
    },
    showStatut() {
      let show = false;
      if (this.row.state == "Validée") {
        show = true;
      }
      if (
        this.$store.getters.contextRole == "admin" ||
        this.$store.getters.contextRole == "referent" ||
        this.$store.getters.contextRole == "referent_regional"
      ) {
        show = true;
      }
      return show;
    },
    statesAvailable(){
      if(this.$store.getters.contextRole == "responsable") {
        return this.$store.getters.taxonomies.mission_workflow_states.terms.filter((item) => (item.value != 'Signalée' && item.value != 'En attente de validation'))
      } else {
        return this.$store.getters.taxonomies.mission_workflow_states.terms
      }
    },
    // showTuteur() {
    //   return this.$store.getters.contextRole != "tuteur" && !this.row.tuteur_id
    //     ? true
    //     : false;
    // }
  },
  methods: {
    onClickDelete() {
      if (this.row.participations_count > 0) {
        this.$alert(
          "Il est impossible de supprimer une mission déjà assigner à un ou plusieurs volontaires.",
          "Supprimer la mission",
          {
            confirmButtonText: "Retour",
            type: "warning",
            center: true,
          }
        );
      } else {
        this.$confirm(
          `La mission ${this.row.name} sera définitivement supprimée de la plateforme. Voulez-vous continuer ?`,
          "Supprimer la mission",
          {
            confirmButtonText: "Supprimer",
            confirmButtonClass: "el-button--danger",
            cancelButtonText: "Annuler",
            center: true,
            type: "error"
          }
        ).then(() => {
          deleteMission(this.row.id).then(() => {
            this.$message({
              type: "success",
              message: `La mission ${this.row.name} a été supprimée.`
            });
            this.$emit("deleted", this.row);
            this.$store.commit("volet/setRow", null);
            this.$store.commit("volet/hide");
          });
        });
      }
    },
    onAskValidationSubmit() {
      if(this.form.structure.state == 'Validée') {
        this.form.state = "Validée";
        this.onSubmit();
      } else {
        this.$message({
          type: "error",
          message: "Votre structure doit être validée avant de pouvoir publier une mission"
        });
      }
    },
    onSubmit() {

      let message = "Êtes vous sur de vos changements ?"

      if(this.form.state == 'Annulée') {
        message = `Attention, vous êtes sur le point d'annuler une mission en lien avec ${this.form.participations_count} participation(s).<br><br> Les participations liées seront automatiquement annulées et les volontaires inscrits seront notifiés de l'annulation de la mission.<br><br> Êtes vous sûr de vouloir continuer ?`
      }

      if(this.form.state == 'Signalée') {
        message =  `Vous êtes sur le point de signaler une mission qui ne répond pas aux exigences de la charte ou des règles fixés par le Décret n° 2017-930 du 9 mai 2017 relatif à la Réserve Civique. Le responsable est en lien avec ${this.form.participations_count} volontaire(s). <br><br> Les participations à venir seront automatiquement annulées. Les coordonnées des volontaires seront masquées et une notification d'annulation sera envoyée aux volontaires initialement inscrits.`
      }

      this.$confirm(message, "Confirmation", {
        confirmButtonText: "Je confirme",
        cancelButtonText: "Annuler",
        type: "warning",
        center: true,
        dangerouslyUseHTMLString: true
      })
        .then(() => {
          this.loading = true;
          updateMission(this.form.id, this.form)
            .then(({data}) => {
              this.loading = false;
              this.$store.commit("volet/setRow", { ...this.row, ...data });
              this.$message({
                type: "success",
                message: "La mission a été mise à jour"
              });
              this.$emit("updated", { ...this.form, ...data });
            })
            .catch(error => {
              this.loading = false;
              console.log(error)
            });
        })
        .catch(() => {});
    }
  }
};
</script>
