<template>
  <div v-if="!$store.getters.loading" class="mission-form pl-12 pb-12">
    <template v-if="mode == 'edit'">
      <div class="text-m text-gray-600 uppercase">Mission</div>
      <div class="mb-8 flex">
        <div class="font-bold text-2xl text-gray-800 max-w-3xl">
          {{ form.name }}
        </div>
        <state-tag
          :state="form.state"
          class="relative ml-3"
          style="top: 1px"
        ></state-tag>
      </div>
    </template>
    <template v-else>
      <div class="text-m text-gray-600 uppercase">Mission</div>
      <div class="mb-12 font-bold text-2xl text-gray-800">
        Création d'une nouvelle mission
      </div>
    </template>
    <div class="flex">
      <div style="max-width: 600px">
         <p  class="mt-2 mb-6 text-xs leading-snug text-gray-500 flex">
            Une question? Appelez-nous au <span class="font-bold"> <a href="tel:0184800189"> 
             &nbsp;01 84 80 01 89&nbsp;</a></span>
             ou chatez en cliquant sur le bouton en bas à droite.
          </p>  

        <el-form
          ref="missionForm"
          :model="form"
          class="max-w-2xl"
          label-position="top"
          :rules="rules"
        >
          <div class="mb-6 text-xl text-gray-800">Informations générales</div>

          <el-form-item v-if="form.structure" label="Ma structure" class>
            <el-input
              v-model="form.structure.name"
              placeholder="Structure de la mission"
              disabled
            />
          </el-form-item>

          <el-form-item label="Domaine d'action" prop="name">
            <item-description>
              Choisissez parmi la liste des missions prioritaires face à la
              crise
            </item-description>
            <el-select
              v-model="form.name"
              placeholder="Choisir un domaine d'action"
            >
              <el-option
                v-for="item in $store.getters.taxonomies.mission_domaines.terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              ></el-option>
            </el-select>
          </el-form-item>

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

          <el-form-item
            label="Nombre de volontaires susceptibles d’être accueillis de façon concomitante sur cette mission"
            prop="participations_max"
          >
            <item-description>
              Précisez ce nombre en fonction de vos contraintes logistiques et
              votre capacité à accompagner les volontaires.
            </item-description>
            <el-input-number
              v-model="form.participations_max"
              :step="1"
              :min="1"
              class="w-full"
            ></el-input-number>
          </el-form-item>

          <el-form-item label="Format de mission" prop="format">
            <el-select
              v-model="form.format"
              placeholder="Selectionner un format de mission"
            >
              <el-option
                v-for="item in $store.getters.taxonomies.mission_formats.terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              ></el-option>
            </el-select>
          </el-form-item>

          <div class="mt-12 mb-6 text-xl text-gray-800">
            Dates de la mission
          </div>

          <div class="flex">
            <el-form-item
              label="Date de début"
              prop="start_date"
              class="flex-1 mr-2"
            >
              <el-date-picker
                v-model="form.start_date"
                class="w-full"
                type="datetime"
                placeholder="Date de début"
                value-format="yyyy-MM-dd HH:mm:ss"
                default-time="09:00:00"
              ></el-date-picker>
            </el-form-item>
            <el-form-item
              label="Date de fin"
              prop="start_date"
              class="flex-1 ml-2"
            >
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

          <!-- <el-form-item
        label="Périodicité de la mission"
        prop="periodicite"
        class="flex-1"
      >
        <el-select
          v-model="form.periodicite"
          placeholder="Sélectionnez la périodicité"
        >
          <el-option
            v-for="item in $store.getters.taxonomies.mission_periodicites.terms"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          ></el-option>
        </el-select>
          </el-form-item>-->

          <div class="mt-12 mb-6 text-xl text-gray-800">
            Détail de la mission
          </div>
          <div class>
            <el-form-item
              label="Commentaire par la structure"
              prop="description"
              class="flex-1"
            >
              <item-description>
                Décrivez précisément la mission (contexte, objectifs,
                bénéficiaires, activités, utilité, ressources...). Celle-ci doit
                être conforme aux règles sanitaires et aux directives du
                gouvernement.
              </item-description>
              <el-input
                v-model="form.description"
                name="description"
                type="textarea"
                :autosize="{ minRows: 2, maxRows: 6 }"
                placeholder="Décrivez votre mission, en quelques mots"
              ></el-input>
            </el-form-item>

            <el-form-item
              label="Publics bénéficiaires"
              prop="publics_beneficiaires"
            >
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

            <!-- <el-form-item label="Publics volontaires" prop="publics_volontaires">
          <item-description
            >Votre mission apparaîtra dans les résultats de recherche lorsqu'un
            ou plusieurs de ses publics bénéficiaires seront
            saisis.</item-description
          >
          <el-select
            v-model="form.publics_volontaires"
            placeholder="Selectionner les publics volontaires"
            multiple
          >
            <el-option
              v-for="item in $store.getters.taxonomies
                .mission_publics_volontaires.terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            ></el-option>
          </el-select>
            </el-form-item>-->
          </div>

          <div class="mt-12 mb-6 flex text-xl text-gray-800">
            La localisation des publics bénéficiaires
          </div>

          <el-form-item label="Département" prop="department">
            <el-select
              v-model="form.department"
              filterable
              placeholder="Département"
            >
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
              <el-input
                v-model="form.latitude"
                disabled
                placeholder="Latitude"
              />
            </el-form-item>
            <el-form-item label="Longitude" prop="longitude" class="flex-1">
              <el-input
                v-model="form.longitude"
                disabled
                placeholder="Longitude"
              />
            </el-form-item>
          </div>

          <div class="mt-12 mb-6 flex text-xl text-gray-800">
            Responsable de la mission
          </div>
          <item-description>
            Les notifications lors de la prise de contact d'un volontaire
            concernant cette mission seront envoyées à cette personne.
            <br />Vous pouvez également
            <span
              class="underline cursor-pointer"
              @click="onAddTuteurLinkClicked"
              >ajouter un nouveau membre</span
            >
            à votre équipe.
          </item-description>
          <el-form-item label="Responsable" prop="tuteur_id" class="flex-1">
            <el-select
              v-model="form.tuteur_id"
              placeholder="Sélectionner un responsable"
            >
              <el-option
                v-for="item in form.structure.members"
                :key="item.id"
                :label="item.full_name"
                :value="item.id"
              ></el-option>
            </el-select>
          </el-form-item>
          <div class="flex pt-2">
            <el-button type="primary" :loading="loading" @click="onSubmit"
              >Enregistrer</el-button
            >
            <!-- <el-button
          v-if="showAskValidation"
          type="primary"
          :loading="loading"
          @click="onAskValidationSubmit"
          >Enregistrer et proposer la mission</el-button
            >-->
          </div>
        </el-form>
      </div>
      <div class="flex-1 px-12">
        <div
          v-if="
            form.name ==
              'Je distribue des produits de première nécessité (aliments, hygiène, …) et des repas aux plus démunis'
          "
          class="border rounded p-8"
        >
          <AideAlimentaireUrgence />
        </div>
        <div
          v-if="
            form.name ==
              'Je garde des enfants de soignants ou d’une structure de l’Aide Sociale à l’Enfance'
          "
          class="border rounded p-8"
        >
          <GardeExceptionnelleEnfants />
        </div>
        <div
          v-if="
            form.name ==
              'Je maintiens un lien (téléphone, visio, mail, …) avec des personnes fragiles isolées (âgées, malades, situation de handicap, de pauvreté, de précarité, etc.)'
          "
          class="border rounded p-8"
        >
          <LienPersonnesFragilesIsolees />
        </div>
        <div
          v-if="
            form.name ==
              'Je fais les courses de produits essentiels pour mes voisins les plus fragiles.'
          "
          class="border rounded p-8"
        >
          <SolidariteDeProximite />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getMission, addMission, updateMission } from "@/api/mission";
import { getStructure } from "@/api/structure";
import AlgoliaPlacesInput from "@/components/AlgoliaPlacesInput";
import StateTag from "@/components/StateTag";
import FormWithAddress from "@/mixins/FormWithAddress";
import ItemDescription from "@/components/forms/ItemDescription";
import AideAlimentaireUrgence from "@/components/domaines/AideAlimentaireUrgence";
import GardeExceptionnelleEnfants from "@/components/domaines/GardeExceptionnelleEnfants";
import LienPersonnesFragilesIsolees from "@/components/domaines/LienPersonnesFragilesIsolees";
import SolidariteDeProximite from "@/components/domaines/SolidariteDeProximite";

export default {
  name: "MissionForm",
  components: {
    AlgoliaPlacesInput,
    StateTag,
    ItemDescription,
    AideAlimentaireUrgence,
    GardeExceptionnelleEnfants,
    LienPersonnesFragilesIsolees,
    SolidariteDeProximite
  },
  mixins: [FormWithAddress],
  props: {
    structureId: {
      type: Number,
      default: null
    },
    id: {
      type: Number,
      default: null
    }
  },
  data() {
    return {
      loading: false,
      mission: {},
      form: {
        state: "Validée",
        participations_max: 1
      },
      rules: {
        name: [
          {
            required: true,
            message: "Veuillez choisir un domaine d'action",
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
        // periodicite: [
        //   {
        //     required: true,
        //     message: "Veuillez choisir une périodicité",
        //     trigger: "blur"
        //   }
        // ],
        publics_beneficiaires: [
          {
            required: true,
            message: "Veuillez choisir au moins un public bénéficiaire",
            trigger: "blur"
          }
        ],
        // publics_volontaires: [
        //   {
        //     required: true,
        //     message: "Veuillez choisir au moins un public volontaire",
        //     trigger: "blur"
        //   }
        // ],
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
            required: false,
            message: "Veuillez renseigner une adresse",
            trigger: "blur"
          }
        ],
        city: [
          {
            required: false,
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
    showAskValidation() {
      return this.$store.getters.contextRole == "responsable" &&
        (!this.mission.state ||
          this.mission.state == "Brouillon" ||
          this.mission.state == "En attente de correction")
        ? true
        : false;
    },
    mode() {
      return this.id ? "edit" : "add";
    }
  },
  created() {
    if (this.structureId) {
      this.$store.commit("setLoading", true);
      getStructure(this.structureId)
        .then(response => {
          this.$set(this.form, "structure", response.data);
          if (
            response.data.members.filter(
              member => member.id == this.$store.getters.user.profile.id
            ).length > 0
          ) {
            this.form.tuteur_id = this.$store.getters.user.profile.id;
          }
          this.$store.commit("setLoading", false);
        })
        .catch(() => {
          this.loading = false;
        });
    } else if (this.id) {
      this.$store.commit("setLoading", true);
      getMission(this.id)
        .then(response => {
          this.form = response.data;
          this.mission = { ...response.data };
          this.$store.commit("setLoading", false);
        })
        .catch(() => {
          this.loading = false;
        });
    }
  },
  methods: {
    onAddTuteurLinkClicked() {
      let routeData = this.$router.resolve({
        name: "StructureMembersAdd",
        params: { id: this.form.structure.id }
      });
      window.open(routeData.href, "_blank");
    },
    onAskValidationSubmit() {
      this.form.state = "En attente de validation";
      this.addOrUpdateMission();
    },
    onSubmit() {
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
      if (this.form.type == "Mission à distance") {
        this.rules.address[0].required = false;
        this.rules.city[0].required = false;
      } else {
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

        this.rules.address[0].required = true;
        this.rules.city[0].required = true;
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
