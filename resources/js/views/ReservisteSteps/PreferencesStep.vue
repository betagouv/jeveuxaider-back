<template>
  <div v-if="$store.getters.profile" class="register-step">
    <portal to="register-steps-help">
      <p>
        Bienvenue {{ $store.getters.user.profile.first_name }} ! <br />Complétez
        votre profil de réservistes afin de mieux cibler
        <span class="font-bold">vos attentes</span>.
      </p>
      <p>
        Une question? Appelez-nous au
        <br />
        <span class="font-bold">
          <a href="tel:0184800189">01 84 80 01 89</a>
        </span>
        ou
        <button onclick="$crisp.push(['do', 'chat:open'])">
          chatez en cliquant sur le bouton en bas à droite.
        </button>
      </p>
    </portal>
    <el-steps :active="1" align-center class="p-4 sm:p-8 border-b border-b-2">
      <el-step title="Préférences" description="Je choisis mes préférences" />
      <el-step
        title="Informations"
        description="Je complète mes informations"
      />
      <el-step
        title="Visibilité"
        description="Je rends mon profil visible des organisations publiques"
      />
    </el-steps>

    <div class="p-4 sm:p-12 max-w-2xl">
      <div class="font-bold text-2xl text-gray-800 mb-4">
        Sélectionnez vos préférences
      </div>
      <div class="mb-8 text-md text-gray-600">
        Enrichissez votre profil avec vos domaines d'action de prédilection
        ainsi que les compétences que vous souhaitez mettre au service des
        organisations publiques.
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
          label="Mes compétences"
          prop="skills"
          class="flex-1 max-w-xl"
        >
          <el-select
            v-model="form.skills"
            multiple
            filterable
            placeholder="Sélectionner vos compétences"
          >
            <el-option
              v-for="skill in skills"
              :key="skill.id"
              :label="skill.name.fr"
              :value="skill.name.fr"
            ></el-option>
          </el-select>
        </el-form-item>
      </el-form>
      <div class="flex pt-2">
        <el-button type="primary" :loading="loading" @click="onSubmit">
          Continuer
        </el-button>
      </div>
    </div>
  </div>
</template>

<script>
import { fetchTags } from '@/api/app'

export default {
  name: 'PreferencesStep',
  data() {
    return {
      loading: false,
      domaines: null,
      skills: null,
      form: this.$store.getters.user.profile,
      rules: {},
    }
  },
  created() {
    fetchTags().then((response) => {
      this.skills = response.data.data.filter((tag) => tag.type == 'competence')
      this.domaines = response.data.data.filter((tag) => tag.type == 'domaine')
      this.form.skills = this.form.skills.map((tag) => tag.name.fr)
      this.form.domaines = this.form.domaines.map((tag) => tag.name.fr)
    })
  },
  methods: {
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
