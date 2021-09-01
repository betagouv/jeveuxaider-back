<template>
  <div>
    <div class="text-center mb-6">
      <div
        class="text-gray-900 font-extrabold text-2xl lg:text-3xl leading-8 mb-2 lg:mb-3"
      >
        {{ form.first_name }}, ravi de vous retrouver !
      </div>
      <div class="text-gray-500 font-semibold text-lg lg:text-xl">
        Renseignez votre mot de passe
      </div>
    </div>
    <div class="mx-auto max-w-sm">
      <el-form
        ref="loginForm"
        :model="form"
        :rules="rules"
        label-position="top"
        class="mb-0 form-register-steps"
        :hide-required-asterisk="true"
        @submit.prevent.native="onSubmit"
      >
        <el-form-item label="Email" prop="email" class="mb-5">
          <el-input
            v-model.trim="form.email"
            placeholder="prenom.nom@email.fr"
            disabled
            suffix-icon="el-icon-check"
          />
        </el-form-item>
        <el-form-item label="Mot de passe" prop="password" class="mb-5">
          <el-input
            v-model="form.password"
            placeholder="Mot de passe"
            label="Mot de passe"
            show-password
            autocomplete="new-password"
          />
        </el-form-item>
        <el-button
          :loading="loading"
          class="!font-bold !max-w-sm !mx-auto !w-full !flex !items-center !justify-center !px-5 !py-3 !border !border-transparent !text-xl !leading-6 !rounded-full !text-white !bg-[#16a972] hover:!bg-[#0e9f6e] focus:!outline-none focus:!ring !transition !mt-8"
          @click.prevent="onSubmit"
        >
          Se connecter
        </el-button>
        <div class="mt-2 text-center">
          <nuxt-link
            :to="`/password-reset?email=${form.email}`"
            target="_blank"
            class="text-sm leading-5 font-medium text-gray-400 hover:text-gray-900 focus:underline transition"
          >
            Mot de passe perdu ?
          </nuxt-link>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script>
import FormMixin from '@/mixins/Form'

export default {
  name: 'SoftGateLogin',
  mixins: [FormMixin],
  props: {
    datas: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      form: { ...this.datas },
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
        password: [
          {
            required: true,
            message: 'Renseignez votre mot de passe',
            trigger: 'change',
          },
        ],
      },
    }
  },
  created() {},
  methods: {
    onSubmit() {
      this.$refs.loginForm.validate((valid, fields) => {
        if (valid) {
          this.loading = true
          this.$store
            .dispatch('auth/login', {
              email: this.form.email,
              password: this.form.password,
            })
            .then(() => {
              this.loading = false

              if (
                this.$store.getters.user
                  .nbTodayParticipationsOnPendingValidation >= 3
              ) {
                this.$emit('too-many-participations')
              } else {
                this.$emit('next')
              }

              window.plausible &&
                window.plausible('Soft Gate - Étape 2 - Login')
            })
            .catch(() => {
              this.loading = false
            })
        } else {
          this.showErrors(fields)
        }
      })
    },
  },
}
</script>

<style lang="postcss" scoped>
::placeholder {
  font-weight: 500;
}
</style>
