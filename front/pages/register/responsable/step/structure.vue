<template>
  <div class="relative">
    <portal to="sidebar"><Steps :steps="steps" /></portal>
    <div class="mb-12 text-center text-white">
      <h1 class="text-5xl font-medium leading-tight">
        <template v-if="structureId">
          À propos de <br />
          <span class="font-bold">{{ form.name }}</span>
        </template>
        <template v-else> Créez votre organisation </template>
      </h1>
    </div>
    <div class="rounded-lg bg-white max-w-lg mx-auto overflow-hidden">
      <div
        class="px-8 py-6 bg-white text-black text-3xl font-extrabold leading-9 text-center"
      >
        <template v-if="structureId">
          Validez ou complétez les informations suivantes
        </template>
        <template v-else>
          Renseignez les informations de votre organisation
        </template>
      </div>
      <div class="p-8 bg-gray-50 border-t border-gray-200">
        <el-form
          ref="structureForm"
          :model="form"
          label-position="top"
          class="form-register-steps"
          :rules="rules"
          :hide-required-asterisk="true"
        >
          <el-form-item label="Nom de votre organisation" prop="name">
            <input
              v-model="form.name"
              class="custom-input placeholder-gray-600"
              placeholder="Nom de votre organisation"
            />
          </el-form-item>
          <el-form-item label="Statut juridique" prop="statut_juridique">
            <el-select
              v-model="form.statut_juridique"
              placeholder="Statut juridique"
            >
              <el-option
                v-for="item in $store.getters.taxonomies.structure_legal_status
                  .terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            v-if="form.statut_juridique == 'Association'"
            label="Disposez vous d'un agrément ?"
            prop="association_types"
          >
            <el-select
              v-model="form.association_types"
              placeholder="Choix de l'agrément"
              multiple
            >
              <el-option
                v-for="item in $store.getters.taxonomies.association_types
                  .terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            v-if="form.statut_juridique == 'Structure publique'"
            label="Type de votre organisation publique"
            prop="structure_publique_type"
          >
            <el-select
              v-model="form.structure_publique_type"
              placeholder="Choix du type de votre organisation publique"
            >
              <el-option
                v-for="item in $store.getters.taxonomies
                  .structure_publique_types.terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            v-if="
              form.statut_juridique == 'Structure publique' &&
              (form.structure_publique_type == 'Service de l\'Etat' ||
                form.structure_publique_type == 'Etablissement public')
            "
            label="Sous-type de votre organisation publique"
            prop="structure_publique_etat_type"
          >
            <el-select
              v-model="form.structure_publique_etat_type"
              placeholder="Choix du sous-type"
            >
              <el-option
                v-for="item in $store.getters.taxonomies
                  .structure_publique_etat_types.terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            v-if="form.statut_juridique == 'Structure privée'"
            label="Type de votre organisation privée"
            prop="structure_privee_type"
          >
            <el-select
              v-model="form.structure_privee_type"
              placeholder="Chox du type d'organisation privée"
            >
              <el-option
                v-for="item in $store.getters.taxonomies.structure_privee_types
                  .terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            label="Domaines d'action (à mettre en tag)"
            prop="domaines"
            class="flex-1 max-w-xl"
          >
            <el-select
              v-model="form.domaines"
              multiple
              filterable
              placeholder="Sélectionner vos domaines d'actions"
            >
              <el-option
                v-for="domaine in domaines"
                :key="domaine.id"
                :label="domaine.name.fr"
                :value="domaine.name.fr"
              ></el-option>
            </el-select>
          </el-form-item>
          <el-form-item
            label="Publics bénéficiaires (à mettre en tag)"
            prop="publics_beneficiaires"
          >
            <el-select
              v-model="form.publics_beneficiaires"
              placeholder="Sélectionner les publics bénéficiaires"
              multiple
            >
              <el-option
                v-for="item in $store.getters.taxonomies
                  .mission_publics_beneficiaires.terms"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </el-form-item>

          <el-form-item
            label="Mission de l'organisation"
            prop="objectif"
            class="mb-6"
          >
            <textarea
              v-model="form.objectif"
              rows="4"
              class="custom-textarea placeholder-gray-600"
              placeholder="Quel est le but de votre organisation ?"
            />
          </el-form-item>

          <el-form-item
            label="Décrivez-nous votre organisation"
            prop="description"
            class="mb-6"
          >
            <textarea
              v-model="form.description"
              rows="4"
              class="custom-textarea placeholder-gray-600"
              placeholder="Dites-nous tout..."
            />
          </el-form-item>

          <el-form-item label="Département" prop="department" class="flex-1">
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
            label="Entrez l'adresse de l'organisation"
            :description="false"
            :class-name="false"
            @selected="setAddress"
            @clear="clearAddress"
          />

          <el-form-item
            label="Adresse de l'organisation"
            prop="address"
            class="mt-6"
          >
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

          <template v-if="form.statut_juridique != 'Collectivité'">
            <el-form-item label="Réseau national" prop="reseau" class="flex-1">
              <item-description container-class="mb-6">
                Si votre organisation est membre d'un réseau national ou
                territorial, sélectionnez-le. Vous permettrez au superviseur de
                votre réseau de visualiser les missions et bénévoles rattachés à
                votre organisation. Vous faciliterez également la validation de
                votre organisation par les autorités territoriales lors de votre
                inscription.
              </item-description>
              <el-select
                v-model="form.reseau_id"
                clearable
                placeholder="Réseau national"
                filterable
              >
                <el-option
                  v-for="item in $store.getters.reseaux"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
          </template>
        </el-form>
        <div class="sm:col-span-">
          <span class="block w-full rounded-md shadow-sm">
            <el-button
              type="primary"
              :loading="loading"
              class="shadow-lg block w-full text-center rounded-lg z-10 border border-transparent bg-green-400 px-4 sm:px-6 py-4 text-lg sm:text-xl leading-6 font-bold text-white hover:bg-green-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo transition ease-in-out duration-150"
              @click="onSubmit"
              >Continuer</el-button
            >
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FormWithAddress from '@/mixins/FormWithAddress'

export default {
  mixins: [FormWithAddress],
  layout: 'register-steps',
  async asyncData({ $api, store }) {
    const tags = await $api.fetchTags({ 'filter[type]': 'domaine' })
    return {
      domaines: tags.data.data,
      structureId: store.getters.structure_as_responsable
        ? store.getters.structure_as_responsable.id
        : null,
      form: store.getters.structure_as_responsable
        ? { ...store.getters.structure_as_responsable }
        : {},
    }
  },
  data() {
    return {
      loading: false,
      steps: [
        {
          name: 'Rejoignez le mouvement',
          status: 'complete',
        },
        {
          name: 'Votre profil',
          status: 'complete',
        },
        {
          name: `Informations sur l'organisation`,
          status: 'current',
        },
        {
          name: `Quelques mots sur l'organisation`,
          status: 'upcoming',
        },
        {
          name: `Votre organisation en image`,
          status: 'upcoming',
        },
      ],
      rules: {
        name: {
          required: true,
          message: 'Le nom de votre organisation est requis',
          trigger: 'blur',
        },
        statut_juridique: {
          required: true,
          message:
            'Veuillez renseigner la forme juridique de votre organisation',
          trigger: 'blur',
        },
        mobile: [
          {
            required: true,
            message: 'Un numéro de téléphone est obligatoire',
            trigger: 'blur',
          },
          {
            pattern: /^[+|\s|\d]*$/,
            message: 'Le format du numéro de téléphone est incorrect',
            trigger: 'blur',
          },
        ],
        phone: {
          pattern: /^[+|\s|\d]*$/,
          message: 'Le format du numéro de téléphone est incorrect',
          trigger: 'blur',
        },
      },
    }
  },
  created() {
    if (this.form.domaines && typeof this.form.domaines[0] === 'object') {
      this.form.domaines = this.form.domaines.map((tag) => tag.name.fr)
    }
  },
  methods: {
    onSubmit() {
      this.$refs.structureForm.validate((valid) => {
        if (valid) {
          this.loading = true
          this.$api
            .addOrUpdateStructure(this.structureId, this.form)
            .then(async () => {
              // Get profile to get new role
              await this.$store.dispatch('auth/fetchUser')
              this.loading = false
              this.$router.push('/register/responsable/step/address')
            })
            .catch(() => {
              this.loading = false
            })
        }
      })
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep .el-step__description
  @apply hidden
    @screen sm
      @apply block
::v-deep .el-upload-list__item-name
  @apply hidden
</style>
