<template>
  <div class="">
    <div class="font-bold text-[1.75rem] text-[#242526] mb-4">
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
        :preview-width="'150px'"
        :field="form.image"
        label="Photo de profil"
        @add-or-crop="avatar = $event"
        @delete="avatar = null"
      />

      <el-form-item label="Email" prop="email">
        <el-input v-model.trim="form.email" placeholder="Email" />
      </el-form-item>

      <div class="grid gap-x-6 sm:grid-cols-2">
        <el-form-item label="Prénom" prop="first_name">
          <el-input
            v-model="form.first_name"
            placeholder="Prénom"
            :disabled="candEditFields"
          />
        </el-form-item>

        <el-form-item label="Nom" prop="last_name">
          <el-input
            v-model="form.last_name"
            placeholder="Nom"
            :disabled="candEditFields"
          />
        </el-form-item>
      </div>

      <div class="grid gap-x-6 sm:grid-cols-2">
        <el-form-item label="Téléphone mobile" prop="mobile">
          <el-input v-model="form.mobile" placeholder="Téléphone mobile" />
        </el-form-item>

        <el-form-item label="Téléphone fixe" prop="phone">
          <el-input v-model="form.phone" placeholder="Téléphone fixe" />
        </el-form-item>
      </div>

      <div class="grid gap-x-6 sm:grid-cols-2">
        <el-form-item label="Code postal" prop="zip">
          <el-input v-model="form.zip" placeholder="Code postal" />
        </el-form-item>

        <el-form-item label="Date de naissance" prop="birthday">
          <el-date-picker
            v-model="form.birthday"
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

      <el-form-item label="Profession" prop="type">
        <el-select
          v-model="form.type"
          placeholder="Sélectionnez votre profession"
        >
          <el-option
            v-for="item in $store.getters.taxonomies.profile_types.terms"
            :key="item.value"
            :label="item.label"
            :value="item.value"
          />
        </el-select>
      </el-form-item>

      <el-form-item label="Disponibilités" prop="disponibilities">
        <el-checkbox-group
          v-model="form.disponibilities"
          size="medium"
          class="custom-checkbox"
        >
          <el-checkbox
            v-for="item in $store.getters.taxonomies.profile_disponibilities
              .terms"
            :key="item.value"
            :label="item.value"
            class="!bg-white"
            border
            >{{ item.label }}</el-checkbox
          >
        </el-checkbox-group>
      </el-form-item>

      <el-form-item label="Fréquence" prop="disponibilities">
        <div class="flex flex-wrap sm:flex-nowrap items-center gap-4">
          <el-select
            v-model="form.commitment__duration"
            placeholder="Choisissez une durée"
          >
            <el-option
              v-for="item in $store.getters.taxonomies.duration.terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>

          <span class="flex-none"> par </span>

          <el-select
            v-model="form.commitment__time_period"
            placeholder="Choisissez une fréquence"
            class="w-full"
            clearable
          >
            <el-option
              v-for="item in $store.getters.taxonomies.time_period.terms"
              :key="item.value"
              :label="item.label"
              :value="item.value"
            />
          </el-select>
        </div>
      </el-form-item>

      <el-form-item label="Décrivez vos motivations" prop="description">
        <el-input
          v-model="form.description"
          name="description"
          type="textarea"
          :autosize="{ minRows: 4, maxRows: 6 }"
          placeholder="Vos motivations en quelques mots"
        ></el-input>
      </el-form-item>

      <div class="mt-12">
        <el-button type="primary" :loading="loading" class="" @click="onSubmit">
          Enregistrer les modifications
        </el-button>
      </div>
    </el-form>
  </div>
</template>

<script>
import FormMixin from '@/mixins/Form'

export default {
  mixins: [FormMixin],
  layout: 'profile',
  data() {
    return {
      loading: false,
      form: {
        ...this.$store.getters.user.profile,
        disponibilities: this.$store.getters.profile.disponibilities
          ? this.$store.getters.profile.disponibilities
          : [],
      },
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
        type: [
          {
            required: true,
            message: 'Choisissez votre profession',
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
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs.profileForm.validate((valid, fields) => {
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
          this.showErrors(fields)
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

<style lang="postcss" scoped>
::v-deep .el-form-item {
  @apply mb-6;
}
</style>
