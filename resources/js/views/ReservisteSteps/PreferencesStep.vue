<template>
  <div v-if="$store.getters.profile" class="register-step">
    <portal to="register-steps-help">
      <p>
        Bienvenue {{ $store.getters.user.profile.first_name }} ! <br />Complétez
        votre profil de réserviste afin de mieux cibler
        <span class="font-bold">votre recherche de mission</span>.
      </p>
      <p>
        Une question?
        <br />
        <button onclick="$crisp.push(['do', 'chat:open'])">
          Chatez en cliquant sur le bouton en bas à droite.
        </button>
      </p>
    </portal>
    <el-steps :active="1" align-center class="p-4 sm:p-8 border-b border-b-2">
      <el-step title="Préférences" description="Je choisis mes préférences" />
      <el-step
        title="Informations"
        description="Je complète mes informations"
      />
    </el-steps>

    <div
      class="flex flex-col items-center lg:items-start lg:flex-row lg:justify-between"
    >
      <div class="p-4 sm:p-12 max-w-2xl order-2">
        <div class="font-bold text-2xl text-gray-800 mb-4">
          Sélectionnez vos préférences
        </div>
        <div class="mb-8 text-md text-gray-600">
          Enrichissez votre profil avec vos domaines d'action de prédilection
          ainsi que les compétences que vous souhaitez mettre au service des
          organisations publiques ou associatives.
        </div>
        <el-form
          ref="profileForm"
          :model="form"
          label-position="top"
          :rules="rules"
          class="max-w-xl"
        >
          <el-form-item
            label="Mes domaines d'action"
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
            :label="
              form.is_visible
                ? 'Votre profil est visible'
                : 'Votre profil n\'est pas visible'
            "
            prop="is_visible"
            class="mb-6"
          >
            <item-description>
              Un profil visible vous offre plus de chances de trouver une
              mission qui répond à votre envie d'engagement, en permettant à une
              organisation publique ou associative de vous contacter en fonction
              des domaines d'action que vous avez sélectionnés.
            </item-description>
            <el-switch
              v-model="form.is_visible"
              active-color="#1E429F"
              inactive-color="#959595"
            ></el-switch>
          </el-form-item>
        </el-form>
        <div class="flex pt-2">
          <el-button type="primary" :loading="loading" @click="onSubmit">
            Continuer
          </el-button>
        </div>
      </div>
      <!-- <div
        class="hidden lg:block p-4 mt-8 lg:mt-16 lg:mr-16 order-1 lg:order-3"
      >
        <img
          src="/images/competences.png"
          alt="Préférences"
          style="max-width: 450px;"
        />
      </div> -->
    </div>
  </div>
</template>

<script>
import { fetchTags } from '@/api/app'
import _ from 'lodash'
import ItemDescription from '@/components/forms/ItemDescription'

export default {
  name: 'PreferencesStep',
  components: {
    ItemDescription,
  },
  data() {
    return {
      loading: false,
      domaines: null,
      form: this.$store.getters.user.profile,
      rules: {},
    }
  },
  computed: {
    skillGroups() {
      return _.groupBy(this.optionsSkills, (skill) => skill.group)
    },
  },
  created() {
    fetchTags({ 'filter[type]': 'domaine' }).then((response) => {
      this.domaines = response.data.data
      if (this.form.domaines && typeof this.form.domaines[0] === 'object') {
        this.form.domaines = this.form.domaines.map((tag) => tag.name.fr)
      }
    })
  },
  methods: {
    fetchSkills(query) {
      if (query !== '') {
        this.loading = true
        fetchTags({ 'filter[type]': 'competence', 'filter[name]': query }).then(
          (response) => {
            this.optionsSkills = response.data.data
            this.loading = false
          }
        )
      } else {
        this.optionsSkills = []
      }
    },
    onSubmit() {
      this.loading = true
      this.$refs['profileForm'].validate((valid) => {
        if (valid) {
          this.$store
            .dispatch('user/updateProfile', {
              id: this.$store.getters.profile.id,
              ...this.form,
            })
            .then(() => {
              this.loading = false
              this.$router.push('/register/reserviste/step/infos')
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
