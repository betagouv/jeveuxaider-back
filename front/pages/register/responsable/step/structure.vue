<template>
  <div class="relative">
    <portal to="sidebar"
      ><div class="text-xl lg:text-2xl font-bold mb-6 lg:mb-12">
        Ça ne devrait pas prendre plus de 3 minutes 😉
      </div>
      <Steps :steps="steps"
    /></portal>
    <div class="mb-6 lg:mb-12 text-center text-white">
      <h1 class="text-4xl lg:text-5xl font-medium leading-12 mb-4">
        <template v-if="structureId">
          À propos de <br />
          <span class="font-bold">{{ form.name }}</span>
        </template>
        <template v-else> Créez votre organisation </template>
      </h1>
    </div>
    <div class="rounded-lg bg-white max-w-xl mx-auto overflow-hidden">
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
            label="Domaines d'action"
            prop="domaines"
            class="flex-1 max-w-xl"
          >
            <el-checkbox-group
              v-model="domainesSelected"
              size="medium"
              class="custom-checkbox"
            >
              <el-checkbox
                v-for="domaine in domaines"
                :key="domaine.id"
                :label="domaine.name.fr"
                class="bg-white"
                border
                :checked="isDomaineSelected(domaine.id)"
                @change="handleClickDomaine(domaine)"
              ></el-checkbox>
            </el-checkbox-group>
          </el-form-item>
          <el-form-item
            label="Publics bénéficiaires"
            prop="publics_beneficiaires"
            class="flex-1 max-w-xl"
          >
            <el-checkbox-group
              v-model="form.publics_beneficiaires"
              size="medium"
              class="custom-checkbox"
            >
              <el-checkbox
                v-for="item in $store.getters.taxonomies
                  .mission_publics_beneficiaires.terms"
                :key="item.value"
                :label="item.label"
                class="bg-white"
                border
              ></el-checkbox>
            </el-checkbox-group>
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
            <el-form-item
              label="Faites-vous partie d'un réseau national ?"
              prop="reseau"
              class="flex-1"
            >
              <!-- <item-description container-class="mb-6">
                Si votre organisation est membre d'un réseau national ou
                territorial, sélectionnez-le. Vous permettrez au superviseur de
                votre réseau de visualiser les missions et bénévoles rattachés à
                votre organisation. Vous faciliterez également la validation de
                votre organisation par les autorités territoriales lors de votre
                inscription.
              </item-description> -->
              <el-select
                v-model="form.reseau_id"
                clearable
                placeholder="Choix de votre réseau national"
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
        ? {
            ...store.getters.structure_as_responsable,
          }
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
          href: '/register/responsable/step/profile',
        },
        {
          name: 'Votre profil',
          status: 'complete',
          href: '/register/responsable/step/profile',
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
        domaines: {
          required: true,
          message: "Sélectionnez au moins un domaine d'action",
          trigger: 'blur',
        },
        publics_beneficiaires: {
          required: true,
          message: 'Sélectionnez au moins un type',
          trigger: 'blur',
        },
        address: [
          {
            required: true,
            message: 'Le champ adresse est requis',
            trigger: 'blur',
          },
        ],
        city: [
          {
            required: true,
            message: 'Le champ ville est requis',
            trigger: 'blur',
          },
        ],
        department: [
          {
            required: true,
            message: 'Le champ département est requis',
            trigger: 'blur',
          },
        ],
      },
    }
  },
  computed: {
    domainesSelected: {
      get() {
        return this.form.domaines.map((item) => item.name.fr)
      },
      set(items) {
        //
      },
    },
  },
  mounted() {
    document.getElementById('step-container').scrollTop = 0
  },
  methods: {
    isDomaineSelected(id) {
      return this.form.domaines.filter((item) => item.id == id).length > 0
    },
    handleClickDomaine(domaine) {
      if (this.isDomaineSelected(domaine.id)) {
        this.form.domaines = this.form.domaines.filter(
          (item) => item.id !== domaine.id
        )
      } else {
        this.$set(this.form, 'domaines', [...this.form.domaines, domaine])
      }
    },
    handleClickPublicBeneficiaire(value) {
      if (this.form.publics_beneficiaires.includes(value)) {
        this.form.publics_beneficiaires = this.form.publics_beneficiaires.filter(
          (item) => item !== value
        )
      } else {
        this.$set(this.form, 'publics_beneficiaires', [
          ...this.form.publics_beneficiaires,
          value,
        ])
      }
    },
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
              this.$router.push('/register/responsable/step/infos')
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

<style lang="sass" scoped></style>
