<template>
  <div class="register-step">
    <portal to="register-steps-help">
      <p>
        Dites nous plus sur votre structure.<br>
        Merci de
        <span class="font-bold">compléter l'adresse</span>
        de votre structure d’accueil.
      </p>
      <p>
        Une question? Appelez-nous au<br><span class="font-bold"><a href="tel:0184800189">
          01 84 80 01 89</a> </span>
        ou
        <button onclick="$crisp.push(['do', 'chat:open'])">
          chatez en cliquant sur le bouton en bas à droite.
        </button>
      </p>
    </portal>
    <el-steps
      :active="3"
      align-center
      class="p-8 border-b"
    >
      <el-step
        title="Profil"
        description="Je complète les informations de mon profil"
      />
      <el-step
        title="Structure"
        description="J'enregistre ma structure en tant que responsable"
      />
      <el-step
        title="Adresse"
        description="J'enregistre le lieu de mon établissement"
      />
    </el-steps>
    <div class="max-w-lg p-4 sm:p-12">
      <div class="font-bold text-2xl text-gray-800 mb-6">
        Lieu de ma structure
      </div>
      <el-form
        ref="etablissementForm"
        :model="form"
        label-position="top"
        :rules="rules"
      >
        <el-form-item
          label="Département"
          prop="department"
          class="flex-1"
        >
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
            />
          </el-select>
        </el-form-item>

        <algolia-places-input
          @selected="setAddress"
          @clear="clearAddress"
        />

        <el-form-item
          label="Adresse"
          prop="address"
          class="mt-6"
        >
          <el-input
            v-model="form.address"
            disabled
            placeholder="Adresse"
          />
        </el-form-item>
        <div class="flex">
          <el-form-item
            label="Code postal"
            prop="zip"
            class="flex-1 mr-2"
          >
            <el-input
              v-model="form.zip"
              disabled
              placeholder="Code postal"
            />
          </el-form-item>
          <el-form-item
            label="Ville"
            prop="city"
            class="flex-1"
          >
            <el-input
              v-model="form.city"
              disabled
              placeholder="Ville"
            />
          </el-form-item>
        </div>

        <div class="flex">
          <el-form-item
            label="Latitude"
            prop="latitude"
            class="flex-1 mr-2"
          >
            <el-input
              v-model="form.latitude"
              disabled
              placeholder="Latitude"
            />
          </el-form-item>
          <el-form-item
            label="Longitude"
            prop="longitude"
            class="flex-1"
          >
            <el-input
              v-model="form.longitude"
              disabled
              placeholder="Longitude"
            />
          </el-form-item>
        </div>
        <div class="flex pt-2">
          <el-button
            type="primary"
            :loading="loading"
            @click="onSubmit"
          >
            Valider
          </el-button>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script>
import { updateStructure } from "@/api/structure";
import AlgoliaPlacesInput from "@/components/AlgoliaPlacesInput";
import FormWithAddress from "@/mixins/FormWithAddress";

export default {
  name: "AddressStep",
  components: { AlgoliaPlacesInput },
  mixins: [FormWithAddress],
  data() {
    return {
      loading: false,
      structureId: this.$store.getters.structure_as_responsable.id,
      form: {},
      rules: {
        lieu: {
          required: true,
          message: "Le lieu est requis",
          trigger: "blur"
        },
        address: {
          required: true,
          message: "Le champ adresse est requis",
          trigger: "blur"
        },
        city: {
          required: true,
          message: "Le champ ville est requis",
          trigger: "blur"
        },
        department: {
          required: true,
          message: "Le champ département est requis",
          trigger: "blur"
        }
      }
    };
  },
  methods: {
    onSubmit() {
      this.loading = true;
      this.$refs["etablissementForm"].validate(valid => {
        if (valid) {
          updateStructure(this.structureId, this.form)
            .then(() => {
              this.loading = false;
              this.$router.push(`/dashboard/structure/${this.structureId}/missions/add`);
            })
            .catch(() => {
              this.loading = false;
            });
        } else {
          this.loading = false;
        }
      });
    }
  }
};
</script>

<style lang="sass" scoped>
::v-deep .el-step__description
    @apply hidden
    @screen sm
        @apply block
</style>
