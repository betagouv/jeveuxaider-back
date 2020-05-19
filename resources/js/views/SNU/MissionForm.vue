<template>
  <div class="mission-form">
    <div class="flex">
      <div style="max-width: 600px">
        <p class="mt-2 mb-6 text-xs leading-snug text-gray-500 flex">
          Une question? Appelez-nous au
          <span class="font-bold">
            <a href="tel:0184800189">&nbsp;01 84 80 01 89&nbsp;</a>
          </span>
          ou chatez en cliquant sur le bouton en bas à droite.
        </p>

          <el-form
            ref="missionForm"
            :model="form"
            class="max-w-2xl"
            label-position="top"
            :rules="rules"
          >
          <div v-if="form.template">
            <div class="mb-6 text-xl text-gray-800">Choix du modèle</div>
            <div class="border rounded p-6">
              <div class="flex items-center">
                <h4 class="flex-shrink-0 pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700">
                  Domaine d'action
                </h4>
                <div class="flex-1 border-t-2 border-gray-200"></div>
              </div>
              <p class="mt-6 ml-3 text-gray-700">
                {{ form.template.thematique.name }}
              </p>
              <div class="flex items-center mt-6">
                <h4 class="flex-shrink-0 pr-4 bg-white text-sm tracking-wider font-semibold uppercase text-gray-700">
                  Titre de la mission
                </h4>
                <div class="flex-1 border-t-2 border-gray-200"></div>
              </div>
              <p class="mt-6 ml-3 text-gray-700">
                {{ form.template.subtitle }}
              </p>
              <el-button class="mt-3" size="small">Pévisualier le modèle</el-button>
            </div>
          </div>
          <div class="mt-6 mb-6 text-xl text-gray-800">Détails de la mission</div>
          <div v-if="!form.template">
            <el-form-item label="Objectif de la mission" prop="objectif" class="flex-1">
                <item-description>
                  Décrivez précisément la mission (contexte, objectifs,
                  bénéficiaires, activités, utilité, ressources...). Celle-ci doit
                  être conforme aux règles sanitaires et aux directives du
                  gouvernement.
                </item-description>
                <el-input
                  v-model="form.objectif"
                  name="objectif"
                  type="textarea"
                  :autosize="{ minRows: 2, maxRows: 6 }"
                  placeholder="Décrivez votre mission, en quelques mots"
                ></el-input>
              </el-form-item>
              <el-form-item label="Description de la mission" prop="description" class="flex-1">
                <el-input
                  v-model="form.description"
                  name="description"
                  type="textarea"
                  :autosize="{ minRows: 2, maxRows: 6 }"
                  placeholder="Décrivez votre mission, en quelques mots"
                ></el-input>
              </el-form-item>
          </div>

          <el-form-item label="Type de mission" prop="type">
            <el-select
              v-model="form.type"
              placeholder="Selectionner un type de mission"
              @change="handleTypeChanged()"
            >
              <el-option
                v-for="item in $store.getters.taxonomies.mission_types.terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              ></el-option>
            </el-select>
          </el-form-item>

      <el-form-item label="Format de mission" prop="format">
            <el-select v-model="form.format" placeholder="Selectionner un format de mission">
              <el-option
                v-for="item in $store.getters.taxonomies.mission_formats.terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              ></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Commentaire par la structure" prop="information" class="flex-1">
            <el-input
              v-model="form.information"
              name="information"
              type="textarea"
              :autosize="{ minRows: 2, maxRows: 6 }"
              placeholder="Décrivez votre mission, en quelques mots"
            ></el-input>
          </el-form-item>
            <el-form-item
            label="Nombre de volontaires susceptibles d’être accueillis de façon concomitante sur cette mission"
            prop="participations_max"
          >
            <item-description>
              Précisez ce nombre en fonction de vos contraintes logistiques et
              votre capacité à accompagner les volontaires.
            </item-description>
            <el-input-number v-model="form.participations_max" :step="1" :min="1" class="w-full"></el-input-number>
          </el-form-item>

          <el-form-item label="Publics bénéficiaires" prop="publics_beneficiaires">
            <el-select
              v-model="form.publics_beneficiaires"
              placeholder="Selectionner les publics bénéficiaires"
              multiple
            >
              <el-option
                v-for="item in $store.getters.taxonomies
                .mission_publics_beneficiaires.terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              ></el-option>
            </el-select>
          </el-form-item>
            <div class="mt-12 mb-6 text-xl text-gray-800">Dates de la mission</div>

            <div class="flex">
              <el-form-item label="Date de début" prop="start_date" class="flex-1 mr-2">
                <el-date-picker
                  v-model="form.start_date"
                  class="w-full"
                  type="datetime"
                  placeholder="Date de début"
                  value-format="yyyy-MM-dd HH:mm:ss"
                  default-time="09:00:00"
                ></el-date-picker>
              </el-form-item>
              <el-form-item label="Date de fin" prop="start_date" class="flex-1 ml-2">
                <el-date-picker
                  v-model="form.end_date"
                  class="w-full"
                  type="datetime"
                  placeholder="Date de fin"
                  default-time="18:00:00"
                  value-format="yyyy-MM-dd HH:mm:ss"
                ></el-date-picker>
              </el-form-item>
            </div>

            <el-form-item
              label="Informations complémentaires concernant les dates et horaires de la mission"
              prop="dates_infos"
              class="flex-1"
            >
              <el-input
                v-model="form.dates_infos"
                type="textarea"
                :autosize="{ minRows: 2, maxRows: 6 }"
                placeholder="Informations complémentaires concernant les dates et horaires de la mission"
              ></el-input>
            </el-form-item>

            <div
              class="mt-12 mb-6 flex text-xl text-gray-800"
            >La localisation des publics bénéficiaires</div>

            <el-form-item label="Département" prop="department">
              <el-select v-model="form.department" filterable placeholder="Département">
                <el-option
                  v-for="item in $store.getters.taxonomies.departments.terms"
                  :key="item.value"
                  :label="`${item.value} - ${item.label}`"
                  :value="item.value"
                ></el-option>
              </el-select>
            </el-form-item>
            <algolia-places-input
              ref="alogoliaInput"
              :value="form.full_address"
              @selected="setAddress"
              @clear="clearAddress"
            ></algolia-places-input>
            <el-form-item label="Adresse" prop="address">
              <el-input v-model="form.address" disabled placeholder="Adresse" />
            </el-form-item>
            <div class="flex">
              <el-form-item label="Code postal" prop="zip" class="flex-1 mr-2">
                <el-input v-model="form.zip" disabled placeholder="Code postal" />
              </el-form-item>
              <el-form-item label="Ville" prop="city" class="flex-1">
                <el-input v-model="form.city" disabled placeholder="Ville" />
              </el-form-item>
            </div>
            <div class="flex">
              <el-form-item label="Latitude" prop="latitude" class="flex-1 mr-2">
                <el-input v-model="form.latitude" disabled placeholder="Latitude" />
              </el-form-item>
              <el-form-item label="Longitude" prop="longitude" class="flex-1">
                <el-input v-model="form.longitude" disabled placeholder="Longitude" />
              </el-form-item>
            </div>

          <div class="mt-12 mb-6 flex text-xl text-gray-800">Responsable de la mission</div>
          <item-description>
            Les notifications lors de la prise de contact d'un volontaire
            concernant cette mission seront envoyées à cette personne.
            <br />Vous pouvez également
            <span
              class="underline cursor-pointer"
              @click="onAddTuteurLinkClicked"
            >ajouter un nouveau membre</span>
            à votre équipe.
          </item-description>
          <el-form-item v-if="form.structure" label="Responsable" prop="tuteur_id" class="flex-1">
            <el-select v-model="form.tuteur_id" placeholder="Sélectionner un responsable">
              <el-option
                v-for="item in form.structure.members"
                :key="item.id"
                :label="item.full_name"
                :value="item.id"
              ></el-option>
            </el-select>
          </el-form-item>
          <div class="flex pt-2">
            <el-button type="primary" :loading="loading" @click="onSubmit">Enregistrer</el-button>
            </div>
          </el-form>
      </div>

    </div>
  </div>
</template>

<script>
import {
  addMission,
  updateMission,
  fetchMissions
} from "@/api/mission";
import AlgoliaPlacesInput from "@/components/AlgoliaPlacesInput";
import FormWithAddress from "@/mixins/FormWithAddress";
import ItemDescription from "@/components/forms/ItemDescription";

export default {
  name: "MissionForm",
  components: {
    AlgoliaPlacesInput,
    ItemDescription
  },
  mixins: [FormWithAddress],
  props: {
    mission: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      loading: false,
      canEdit: false,
      form: {
        state: this.template ? "Validée" : "En attente de validation",
        participations_max: 1,
        template: this.template || null,
        ...this.mission
      },
      rules: {
        name: [
          {
            required: true,
            message: "Veuillez choisir un domaine d'action",
            trigger: "blur"
          }
        ],
        domaine_id: [
          {
            required: true,
            message: "Veuillez choisir un domaine d'action principal",
            trigger: "blur"
          }
        ],
        format: [
          {
            required: true,
            message: "Veuillez choisir un format de mission",
            trigger: "blur"
          }
        ],
        type: [
          {
            required: true,
            message: "Veuillez choisir un type de mission",
            trigger: "blur"
          }
        ],
        publics_beneficiaires: [
          {
            required: true,
            message: "Veuillez choisir au moins un public bénéficiaire",
            trigger: "blur"
          }
        ],
        objectif: [
          {
            required: true,
            message: "Veuillez renseigner un objectif de la mission",
            trigger: "blur"
          }
        ],
        description: [
          {
            required: true,
            message: "Veuillez renseigner un descriptif de la mission",
            trigger: "blur"
          }
        ],
        department: [
          {
            required: true,
            message: "Veuillez sélectionner un département",
            trigger: "blur"
          }
        ],
        address: [
          {
            required: true,
            message: "Veuillez renseigner une adresse",
            trigger: "blur"
          }
        ],
        city: [
          {
            required: true,
            message: "Veuillez renseigner un ville",
            trigger: "blur"
          }
        ],
        tuteur_id: [
          {
            required: true,
            message: "Veuillez sélectionner le responsable de la mission",
            trigger: "blur"
          }
        ]
      }
    };
  },
  computed: {
    mode() {
      return this.id ? "edit" : "add";
    }
  },
  async beforeRouteEnter(to, from, next) {
    await next(async vm => {
      if (to.name == "MissionFormEdit") {
        await fetchMissions({ "filter[id][]": vm.id }).then(response => {
          if (response.data.total != 1) {
            vm.$message({
              message: "Vous n'avez pas les droits d'accéder à cette page",
              type: "error"
            });
            next("/403");
          }
        });
      }
    });
  },
  methods: {
    onAddTuteurLinkClicked() {
      let routeData = this.$router.resolve({
        name: "StructureMembersAdd",
        params: { id: this.form.structure.id }
      });
      window.open(routeData.href, "_blank");
    },
    onSubmit() {
      if (this.form.structure && this.form.structure.state == "Validée") {
        this.form.state = "Validée";
      }
      this.addOrUpdateMission();
    },
    addOrUpdateMission() {
      this.loading = true;
      this.$refs["missionForm"].validate(valid => {
        if (valid) {
          if (this.id) {
            updateMission(this.id, this.form)
              .then(() => {
                this.loading = false;
                this.$router.go(-1);
                this.$message({
                  message: "La mission a été mise à jour !",
                  type: "success"
                });
              })
              .catch(() => {
                this.loading = false;
              });
          } else if (this.structureId) {
            addMission(this.structureId, this.form)
              .then(() => {
                this.loading = false;
                this.$router.push(`/dashboard/missions`);
                this.$message({
                  message: "La mission a été ajoutée !",
                  type: "success"
                });
              })
              .catch(() => {
                this.loading = false;
              });
          }
        } else {
          this.loading = false;
        }
      });
    },
    handleTypeChanged() {
      if (this.form.type == "Mission en présentiel") {
         this.$confirm(
          "Merci de bien respecter les règles de sécurités pour les missions en présentiel !<br>",
          "Confirmation",
          {
            confirmButtonText: "Je confirme",
            cancelButtonText: "Annuler",
            type: "warning",
            center: true,
            dangerouslyUseHTMLString: true
          }
        );
      }
    }
  }
};
</script>

<style lang="sass" scoped>
::v-deep
  .el-input-number__decrease,
  .el-input-number__increase
    bottom: 1px
    display: flex
    align-items: center
    justify-content: center
</style>
