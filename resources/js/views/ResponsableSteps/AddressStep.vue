<template>
  <div class="register-step">
    <portal to="register-steps-help">
      <p>
        Dites nous plus sur votre organisation.<br />
        Merci de
        <span class="font-bold">compléter l'adresse</span>
        de votre organisation d’accueil.
      </p>
      <p>
        Une question? Contactez <br /><span class="font-bold"
          ><a
            target="_blank"
            href="mailto:contact@reserve-civique.on.crisp.email"
          >
            le support</a
          >
        </span>
        ou
        <button onclick="$crisp.push(['do', 'chat:open'])">
          chatez en cliquant sur le bouton en bas à droite.
        </button>
      </p>
    </portal>
    <el-steps :active="3" align-center class="p-8 border-b">
      <el-step
        title="Profil"
        description="Je complète les informations de mon profil"
      />
      <el-step
        title="Organisation"
        description="J'enregistre mon organisation en tant que responsable"
      />
      <el-step
        title="Adresse"
        description="J'enregistre le lieu de mon établissement"
      />
    </el-steps>
    <div v-show="!showSuccessMessage" class="max-w-lg p-4 sm:p-12">
      <div class="font-bold text-2xl text-gray-800 mb-6">
        Lieu de mon organisation
      </div>
      <el-form
        ref="addressForm"
        :model="form"
        label-position="top"
        :rules="rules"
      >
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

        <algolia-places-input @selected="setAddress" @clear="clearAddress" />

        <el-form-item label="Adresse" prop="address" class="mt-6">
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
        <template v-if="collectivity">
          <div class="mb-6 mt-12 flex text-xl text-gray-800">
            Codes postaux de votre collectivité
          </div>
          <item-description container-class="mb-6">
            En tant que collectivité, vous aurez accès au statistiques des
            organisations enregistrées avec vos codes postaux. <br />Vous aurez
            aussi la possibilité de gérer la page de votre collectivité qui
            listera toutes les missions dans votre collectivité. Par exemple
            pour Bayonne :
            <a
              href="https://covid19.reserve-civique.gouv.fr/territoires/bayonne"
              target="_blank"
              >https://covid19.reserve-civique.gouv.fr/territoires/bayonne</a
            >
          </item-description>
          <el-form-item
            label="Liste des codes postaux"
            prop="zips"
            class="flex-1"
          >
            <el-select
              v-model="collectivity.zips"
              multiple
              allow-create
              filterable
              default-first-option
              placeholder="Saisissez tous les codes postaux"
            >
            </el-select>
          </el-form-item>
        </template>

        <div class="flex pt-2">
          <el-button type="primary" :loading="loading" @click="onSubmit">
            Valider
          </el-button>
        </div>
      </el-form>
    </div>
    <div
      v-show="showSuccessMessage"
      class="max-w-2xl mx-auto text-center mt-12"
    >
      <h2 class="font-bold text-3xl text-gray-800 mb-6">
        Votre demande d'inscription de collectivté a bien été enregistrée.
      </h2>
      <p class="font-xl text-secondary">
        Vous recevrez un email de confirmation lorsque votre compte aura été
        validé par l'équipe de la Réserve Civique.
      </p>
      <div class="">
        <router-link class="mr-4" to="/dashboard">
          <el-button type="primary"> Tableau de bord </el-button>
        </router-link>
        <router-link to="/">
          <el-button type="primary"> Aller à la page d'accueil </el-button>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import { updateStructure } from '@/api/structure'
import { updateCollectivity } from '@/api/app'
import AlgoliaPlacesInput from '@/components/AlgoliaPlacesInput'
import FormWithAddress from '@/mixins/FormWithAddress'
import ItemDescription from '@/components/forms/ItemDescription'

export default {
  name: 'AddressStep',
  components: { AlgoliaPlacesInput, ItemDescription },
  mixins: [FormWithAddress],
  data() {
    return {
      loading: false,
      structureId: this.$store.getters.structure_as_responsable.id,
      collectivity: {},
      form: {},
      showSuccessMessage: false,
      rules: {
        lieu: {
          required: true,
          message: 'Le lieu est requis',
          trigger: 'blur',
        },
        address: {
          required: true,
          message: 'Le champ adresse est requis',
          trigger: 'blur',
        },
        city: {
          required: true,
          message: 'Le champ ville est requis',
          trigger: 'blur',
        },
        department: {
          required: true,
          message: 'Le champ département est requis',
          trigger: 'blur',
        },
      },
    }
  },
  created() {
    this.form = this.$store.getters.structure_as_responsable || null
    this.collectivity = this.form.collectivity || null
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs['addressForm'].validate(async (valid) => {
        if (valid) {
          updateStructure(this.structureId, this.form)
            .then(() => {
              console.log('collectivity ?', this.collectivity)
              if (this.collectivity) {
                updateCollectivity(
                  this.collectivity.id,
                  this.collectivity
                ).then(() => {
                  this.loading = false
                  this.showSuccessMessage = true
                })
              } else {
                this.loading = false
                this.$router.push(
                  `/dashboard/structure/${this.structureId}/missions/add`
                )
              }
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          this.loading = false
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
</style>
