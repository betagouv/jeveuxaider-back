<template>
  <div class="">
    <div class="font-bold text-2xl text-gray-800 mb-4">
      Préférences
    </div>

    <div class="mb-8 text-md text-gray-600">
      Enrichissez votre profil avec vos domaines d'action de prédilection ainsi
      que les compétences que vous souhaitez mettre au service des organisations
      publiques ou associatives.
    </div>

    <el-form
      ref="profileForm"
      :model="form"
      label-position="top"
      :rules="rules"
      :hide-required-asterisk="true"
    >
      <el-form-item
        label="Mes domaines d'action"
        prop="domaines"
        class="flex-1 max-w-xl mb-7"
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
        <el-switch
          v-model="form.is_visible"
          active-color="#1E429F"
          inactive-color="#959595"
        ></el-switch>
      </el-form-item>

      <div class="mt-8">
        <el-button type="primary" :loading="loading" class="" @click="onSubmit">
          Enregistrer les modifications
        </el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import { fetchTags } from '@/api/app'
import _ from 'lodash'

export default {
  name: 'FrontUserPreferences',
  data() {
    return {
      loading: false,
      form: this.$store.getters.user.profile,

      domaines: null,
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
            this.loading = false
            this.optionsSkills = response.data.data
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
            .dispatch('user/updateProfile', this.form)
            .then(() => {
              this.loading = false
              this.$message({
                message: 'Vos préférences ont été mises à jour.',
                type: 'success',
              })
            })
            .catch(() => {
              this.loading = false
            })
          this.loading = false
        } else {
          this.loading = false
        }
      })
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep .el-form-item
    @apply mb-3
</style>
