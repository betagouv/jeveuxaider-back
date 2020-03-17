<template>
  <div v-if="!$store.getters.loading" class="young-form max-w-2xl pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">
      Volontaire
    </div>
    <div class="mb-8 flex">
      <div class="font-bold text-2xl">
        {{ form.first_name }} {{ form.last_name }}
      </div>
    </div>

    <el-form ref="youngForm" :model="form" label-position="top" :rules="rules">
      <div class="mb-6 text-xl text-gray-800">
        Informations générales
      </div>

      <el-form-item label="Email" prop="email">
        <el-input v-model="form.email" placeholder="Email" />
      </el-form-item>

      <el-form-item label="Prénom" prop="first_name" class="">
        <el-input v-model="form.first_name" placeholder="Prénom" />
      </el-form-item>

      <el-form-item label="Nom" prop="last_name" class="">
        <el-input v-model="form.last_name" placeholder="Nom" />
      </el-form-item>

      <el-form-item label="Téléphone" prop="phone" class="">
        <el-input v-model="form.phone" placeholder="Téléphone" />
      </el-form-item>

      <el-form-item label="Genre" prop="genre">
        <el-radio-group v-model="form.genre">
          <el-radio-button label="Fille"></el-radio-button>
          <el-radio-button label="Garçon"></el-radio-button>
        </el-radio-group>
      </el-form-item>

      <el-form-item label="Situation" prop="situation">
        <el-select
          v-model="form.situation"
          placeholder="Sélectionnez votre situation"
        >
          <el-option
            v-for="item in $store.getters.taxonomies.young_situations.terms"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          ></el-option>
        </el-select>
      </el-form-item>

      <el-form-item label="Engagement" prop="engaged">
        <item-description>
          Bénévole dans une association, auprès des sapeurs-pompiers, dans une
          Junior association ?
        </item-description>
        <el-radio-group v-model="form.engaged">
          <el-radio-button label="Oui">Déjà engagé</el-radio-button>
          <el-radio-button label="Non">Libre</el-radio-button>
        </el-radio-group>
      </el-form-item>

      <el-form-item
        v-if="form.engaged == 'Oui'"
        label="Structure d'engagement"
        prop="engaged"
        class=""
      >
        <item-description>
          Nom de l'association dans laquelle vous êtes déjà engagé, ou la forme
          d'engagement dans laquelle vous êtes investi (pompier, cadet de la
          gendarmerie...)
        </item-description>
        <el-input v-model="form.engaged_structure" type="textarea" />
      </el-form-item>

      <div class="mt-12 mb-6 flex text-xl text-gray-800">
        Préférences
      </div>

      <el-form-item label="Format de mission" prop="mission_type">
        <el-select v-model="form.mission_type" placeholder="Format de mission">
          <el-option
            v-for="item in $store.getters.taxonomies.mission_formats.terms"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          ></el-option>
        </el-select>
      </el-form-item>

      <el-form-item
        v-if="form.mission_type == 'Autonome'"
        label="Mission autonome - Projet"
        prop="mission_autonome_projet"
      >
        <item-description>
          Merci de décrire rapidement l'idée de projet que vous souhaitez mener
          (la thématique, les premières pistes...)
        </item-description>
        <el-input
          v-model="form.mission_autonome_projet"
          placeholder="Description du projet"
          type="textarea"
        />
      </el-form-item>

      <el-form-item
        v-if="form.mission_type == 'Autonome'"
        label="Mission autonome - Structure"
        prop="mission_autonome_structure"
      >
        <el-input
          v-model="form.mission_autonome_structure"
          placeholder="Nom de la structure"
          type="textarea"
        />
      </el-form-item>

      <el-form-item label="Défense" prop="interet_defense">
        <el-radio-group v-model="form.interet_defense">
          <el-radio-button
            v-for="item in $store.getters.taxonomies.interest_ratings.terms"
            :key="item.value"
            :label="item.label"
          ></el-radio-button>
        </el-radio-group>
      </el-form-item>

      <el-form-item
        v-if="form.interet_defense == 'Très intéressé'"
        label="Défense - Type de mission"
        prop="interet_defense_type"
      >
        <el-select
          v-model="form.interet_defense_type"
          placeholder="Type de mission"
        >
          <el-option
            v-for="item in $store.getters.taxonomies.interet_defense_types
              .terms"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          ></el-option>
        </el-select>
      </el-form-item>

      <el-form-item
        v-if="
          form.interet_defense == 'Très intéressé' &&
            form.interet_defense_type ==
              'Au sein d\'une préparation militaire ?'
        "
        label="Défense - Préparation militaire - Domaine"
        prop="interet_defense_domaine"
      >
        <el-select
          v-model="form.interet_defense_domaine"
          placeholder="Sélectionnez un domaine"
        >
          <el-option
            v-for="item in $store.getters.taxonomies.interet_defense_domaines
              .terms"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          ></el-option>
        </el-select>
      </el-form-item>

      <el-form-item
        v-if="
          form.interet_defense == 'Très intéressé' &&
            form.interet_defense_type ==
              'Au sein d\'une préparation militaire ?'
        "
        label="Défense - Préparation militaire - Motivations"
        prop="interet_defense_domaine"
      >
        <el-input
          v-model="form.interet_defense_motivation"
          type="textarea"
          placeholder="Décrivez les motivations"
        />
      </el-form-item>

      <el-form-item label="Sécurité" prop="interet_securite">
        <el-radio-group v-model="form.interet_securite">
          <el-radio-button
            v-for="item in $store.getters.taxonomies.interest_ratings.terms"
            :key="item.value"
            :label="item.label"
          ></el-radio-button>
        </el-radio-group>
      </el-form-item>

      <el-form-item
        v-if="form.interet_securite == 'Très intéressé'"
        label="Sécurité - Domaine"
        prop="interet_securite_domaine"
      >
        <el-select
          v-model="form.interet_securite_domaine"
          placeholder="Sélectionnez un domaine"
        >
          <el-option
            v-for="item in $store.getters.taxonomies.interet_securite_domaines
              .terms"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          ></el-option>
        </el-select>
      </el-form-item>

      <el-form-item label="Solidarité" prop="interet_solidarite">
        <el-radio-group v-model="form.interet_solidarite">
          <el-radio-button
            v-for="item in $store.getters.taxonomies.interest_ratings.terms"
            :key="item.value"
            :label="item.label"
          ></el-radio-button>
        </el-radio-group>
      </el-form-item>

      <el-form-item label="Santé" prop="interet_sante">
        <el-radio-group v-model="form.interet_sante">
          <el-radio-button
            v-for="item in $store.getters.taxonomies.interest_ratings.terms"
            :key="item.value"
            :label="item.label"
          ></el-radio-button>
        </el-radio-group>
      </el-form-item>

      <el-form-item label="Éducation" prop="interet_education">
        <el-radio-group v-model="form.interet_education">
          <el-radio-button
            v-for="item in $store.getters.taxonomies.interest_ratings.terms"
            :key="item.value"
            :label="item.label"
          ></el-radio-button>
        </el-radio-group>
      </el-form-item>

      <el-form-item label="Culture" prop="interet_culture">
        <el-radio-group v-model="form.interet_culture">
          <el-radio-button
            v-for="item in $store.getters.taxonomies.interest_ratings.terms"
            :key="item.value"
            :label="item.label"
          ></el-radio-button>
        </el-radio-group>
      </el-form-item>

      <el-form-item label="Sport" prop="interet_sport">
        <el-radio-group v-model="form.interet_sport">
          <el-radio-button
            v-for="item in $store.getters.taxonomies.interest_ratings.terms"
            :key="item.value"
            :label="item.label"
          ></el-radio-button>
        </el-radio-group>
      </el-form-item>

      <el-form-item label="Environnement" prop="interet_environnement">
        <el-radio-group v-model="form.interet_environnement">
          <el-radio-button
            v-for="item in $store.getters.taxonomies.interest_ratings.terms"
            :key="item.value"
            :label="item.label"
          ></el-radio-button>
        </el-radio-group>
      </el-form-item>

      <el-form-item label="Citoyenneté" prop="interet_citoyennete">
        <el-radio-group v-model="form.interet_citoyennete">
          <el-radio-button
            v-for="item in $store.getters.taxonomies.interest_ratings.terms"
            :key="item.value"
            :label="item.label"
          ></el-radio-button>
        </el-radio-group>
      </el-form-item>

      <el-form-item label="Contraintes" prop="contraintes">
        <item-description>
          Avez-vous des contraintes particulières à prendre en compte pour la
          mission d'intérêt général ? (interne, résidence alternée, manque de
          visibilité sur votre emploi du temps...)
        </item-description>
        <el-input
          v-model="form.contraintes"
          placeholder="Listez vos contraintes"
          type="textarea"
        />
      </el-form-item>

      <!-- <el-form-item label="Notes" prop="notes">
        <item-description>
          Vous pouvez laissez des informations relatives aux volontaires
        </item-description>
        <el-input
          v-model="form.notes"
          placeholder="Informations complémentaires ..."
          type="textarea"
        />
      </el-form-item> -->

      <div class="mt-12 mb-6 flex text-xl text-gray-800">
        Ville habituelle
        <el-tag
          v-if="!form.regular_latitude && !form.regular_longitude"
          type="danger"
          class="m-1 ml-3"
          size="small"
        >
          Non géolocalisé
        </el-tag>
      </div>
      <item-description>
        Renseignez la ville où le volontaire a l'habitude de se trouver
        réguilièrement (lycée, apprentissage etc)
      </item-description>
      <algolia-places-input
        :value="form.regular_city"
        selector="regular-places-search"
        @selected="setYoungAddress"
        @clear="clearYoungAddress"
      ></algolia-places-input>
      <el-form-item label="Ville" prop="regular_city" class="flex-1">
        <el-input v-model="form.regular_city" placeholder="Ville" />
      </el-form-item>
      <div class="flex">
        <el-form-item
          label="Latitude"
          prop="regular_latitude"
          class="flex-1 mr-2"
        >
          <el-input
            v-model="form.regular_latitude"
            disabled
            placeholder="Latitude"
          />
        </el-form-item>
        <el-form-item label="Longitude" prop="regular_longitude" class="flex-1">
          <el-input
            v-model="form.regular_longitude"
            disabled
            placeholder="Longitude"
          />
        </el-form-item>
      </div>

      <div class="mt-12 mb-6 flex text-xl text-gray-800">
        Adresse
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

      <div class="flex pt-2">
        <el-button type="primary" :loading="loading" @click="onSubmit">
          Enregistrer
        </el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import { getYoung, updateYoung, addYoung } from "@/api/young";
import AlgoliaPlacesInput from "@/components/AlgoliaPlacesInput";
import ItemDescription from "@/components/forms/ItemDescription";
import FormWithAddress from "@/mixins/FormWithAddress";

export default {
  name: "YoungForm",
  components: { AlgoliaPlacesInput, ItemDescription },
  mixins: [FormWithAddress],
  props: {
    mode: {
      type: String,
      required: true
    },
    id: {
      type: Number,
      default: null
    }
  },
  data() {
    return {
      loading: false,
      form: {
        engaged: "Non"
      }
    };
  },
  computed: {
    rules() {
      let rules = {
        email: [
          {
            message: "Veuillez renseigner un email",
            trigger: "blur"
          }
        ],
        first_name: [
          {
            required: true,
            message: "Veuillez renseigner un prénom",
            trigger: "blur"
          }
        ],
        last_name: [
          {
            required: true,
            message: "Veuillez renseigner un nom",
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
        phone: {
          pattern: /^[+|\s|\d]*$/,
          message:
            "Formats attendus: 01 22 33 44 55, +331 22 33 44 55, +689 22 33 44 55",
          trigger: "blur"
        }
      };

      return rules;
    }
  },
  created() {
    if (this.mode == "edit") {
      this.$store.commit("setLoading", true);
      getYoung(this.id)
        .then(response => {
          this.$store.commit("setLoading", false);
          this.form = response.data;
        })
        .catch(() => {
          this.loading = false;
        });
    }
  },
  methods: {
    onSubmit() {
      this.loading = true;
      this.$refs["youngForm"].validate(valid => {
        if (valid) {
          if (this.id) {
            updateYoung(this.form.id, this.form)
              .then(() => {
                this.loading = false;
                this.$router.go(-1);
                this.$message({
                  message: "Le profil a été enregistré !",
                  type: "success"
                });
              })
              .catch(() => {
                this.loading = false;
              });
          } else {
            addYoung(this.form)
              .then(() => {
                this.loading = false;
                this.$router.go(-1);
                this.$message({
                  message: "Le volontaire a été enregistré !",
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
    }
  }
};
</script>
