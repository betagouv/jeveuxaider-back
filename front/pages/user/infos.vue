<template>
  <div class="">
    <div class="font-bold text-2xl text-gray-800 mb-4">
      Informations personnelles
    </div>

    <div class="mb-8 text-md text-gray-600">
      Enrichissez votre profil en décrivant vos motivations et en renseignant
      vos disponibilités.
    </div>

    <el-form
      ref="profileForm"
      :model="form"
      label-position="top"
      :rules="rules"
      :hide-required-asterisk="true"
    >
      <ImageField
        :model="model"
        :model-id="form.id ? form.id : null"
        :min-width="320"
        :min-height="320"
        :max-size="2000000"
        :preview-width="150"
        :field="form.image"
        label="Photo de profil"
        @add-or-crop="avatar = $event"
        @delete="avatar = null"
      ></ImageField>

      <el-form-item label="Email" prop="email" class="mb-6">
        <el-input v-model.trim="form.email" placeholder="Email" />
      </el-form-item>
      <div class="flex mb-4">
        <el-form-item label="Prénom" prop="first_name" class="flex-1 mr-2">
          <el-input
            v-model="form.first_name"
            placeholder="Prénom"
            :disabled="candEditFields"
          />
        </el-form-item>
        <el-form-item label="Nom" prop="last_name" class="flex-1 ml-2">
          <el-input
            v-model="form.last_name"
            placeholder="Nom"
            :disabled="candEditFields"
          />
        </el-form-item>
      </div>
      <div class="flex mb-4">
        <el-form-item
          label="Téléphone mobile"
          prop="mobile"
          class="flex-1 mr-2"
        >
          <el-input v-model="form.mobile" placeholder="Téléphone mobile" />
        </el-form-item>
        <el-form-item label="Téléphone fixe" prop="phone" class="flex-1 ml-2">
          <el-input v-model="form.phone" placeholder="Téléphone fixe" />
        </el-form-item>
      </div>
      <div class="flex mb-4">
        <el-form-item label="Code postal" prop="zip" class="flex-1 mr-2">
          <el-input v-model="form.zip" placeholder="Code postal" />
        </el-form-item>
        <el-form-item
          label="Date de naissance"
          prop="birthday"
          class="flex-1 ml-2"
        >
          <el-date-picker
            v-model="form.birthday"
            :disabled="candEditFields"
            type="date"
            placeholder="Date de naissance"
            autocomplete="off"
            format="dd-MM-yyyy"
            value-format="yyyy-MM-dd"
            style="width: 100%"
            :picker-options="{ firstDayOfWeek: 1 }"
          />
        </el-form-item>
      </div>

      <el-form-item
        label="Compétences"
        prop="skills"
        class="flex-1 max-w-xl mb-7"
      >
        <el-select
          v-model="form.skills"
          multiple
          filterable
          reserve-keyword
          remote
          :remote-method="fetchSkills"
          placeholder="Ex : peinture en bâtiment, soins infirmiers, service en restauration..."
          :loading="loading"
        >
          <el-option-group
            v-for="(skillss, index) in skillGroups"
            :key="index"
            :label="index"
          >
            <el-option
              v-for="item in skillss"
              :key="item.id"
              :label="item.name.fr"
              :value="item.name.fr"
            >
            </el-option>
          </el-option-group>
        </el-select>
      </el-form-item>

      <el-form-item label="Disponibilités" prop="disponibilities" class="mb-6">
        <el-select
          v-model="form.disponibilities"
          placeholder="Sélectionnez vos disponibilités"
          multiple
        >
          <el-option
            v-for="item in $store.getters.taxonomies.profile_disponibilities
              .terms"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </el-form-item>
      <div class="flex items-end">
        <el-form-item
          label="Fréquence"
          prop="disponibilities"
          class="w-full sm:w-1/2 pr-2"
        >
          <el-select
            v-model="form.frequence"
            placeholder="Sélectionnez votre fréquence"
          >
            <el-option
              v-for="item in $store.getters.taxonomies.profile_frequences.terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
        <el-form-item prop="frequence_granularite" class="w-full sm:w-1/2 pl-2">
          <el-select v-model="form.frequence_granularite" placeholder="Par...">
            <el-option
              v-for="item in $store.getters.taxonomies
                .profile_frequences_granularite.terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
      </div>
      <el-form-item
        label="Décrivez vos motivations"
        prop="description"
        class="flex-1 mt-4"
      >
        <el-input
          v-model="form.description"
          name="description"
          type="textarea"
          :autosize="{ minRows: 4, maxRows: 6 }"
          placeholder="Décrivez-vous de manière succinte"
        ></el-input>
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
import { groupBy, sortBy } from 'lodash'

export default {
  layout: 'profile',
  data() {
    return {
      loading: false,
      form: { ...this.$store.getters.user.profile },
      skills: null,
      domaines: null,
      optionsSkills: [],
      model: 'profile',
      rules: {
        email: [
          {
            type: 'email',
            message: "Le format de l'email n'est pas correct",
            trigger: 'blur',
          },
          {
            required: true,
            message: 'Veuillez renseigner votre email',
            trigger: 'blur',
          },
        ],
        first_name: [
          {
            required: true,
            message: 'Prénom obligatoire',
            trigger: 'blur',
          },
        ],
        last_name: [
          {
            required: true,
            message: 'Nom obligatoire',
            trigger: 'blur',
          },
        ],
        zip: [
          {
            required: true,
            message: 'Code postal obligatoire',
            trigger: 'blur',
          },
          {
            pattern: /^\d+$/,
            message: 'Ne doit contenir que des chiffres',
            trigger: 'blur',
          },
          {
            min: 5,
            max: 5,
            message: 'Format erroné',
            trigger: 'blur',
          },
        ],
        mobile: [
          {
            required: true,
            message: 'Téléphone obligatoire',
            trigger: 'blur',
          },
          {
            pattern: /^[+|\s|\d]*$/,
            message: 'Le format du numéro de téléphone est incorrect',
            trigger: 'blur',
          },
        ],
      },
      avatar: null,
    }
  },
  computed: {
    candEditFields() {
      return (
        this.$store.getters.user.social_accounts.filter(
          (socialAccount) => socialAccount.provider == 'franceconnect'
        ).length > 0
      )
    },
    skillGroups() {
      return groupBy(
        sortBy(this.optionsSkills, ['group']),
        (skill) => skill.group
      )
    },
  },
  created() {
    if (this.form.skills && typeof this.form.skills[0] === 'object') {
      this.form.skills = this.form.skills.map((tag) => tag.name.fr)
    }
  },
  methods: {
    fetchSkills(query) {
      if (query !== '') {
        this.loading = true
        this.$api
          .fetchTags({ 'filter[type]': 'competence', 'filter[name]': query })
          .then((response) => {
            this.loading = false
            this.optionsSkills = response.data.data
          })
      } else {
        this.optionsSkills = []
      }
    },
    onSubmit() {
      this.loading = true
      this.$refs.profileForm.validate((valid) => {
        if (valid) {
          if (this.avatar) {
            this.$api
              .uploadImage(
                this.form.id,
                this.model,
                this.avatar.blob,
                this.avatar.cropSettings
              )
              .then(() => {
                this.updateProfile()
              })
          } else {
            this.updateProfile()
          }
        } else {
          this.loading = false
        }
      })
    },
    async updateProfile() {
      const profile = await this.$store.dispatch(
        'user/updateProfile',
        this.form
      )
      if (profile) {
        this.loading = false
        this.$message({
          message: 'Vos informations ont été mises à jour.',
          type: 'success',
        })
      }
    },
  },
}
</script>

<style lang="sass" scoped>
::v-deep .el-form-item
    @apply mb-3
</style>
