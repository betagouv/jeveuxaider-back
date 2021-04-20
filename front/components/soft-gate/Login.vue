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
        class="mb-0 form-center"
        @submit.prevent.native="onSubmit"
      >
        <el-form-item prop="email" class="mb-5">
          <div
            class="input-shadow relative text-center bg-white px-5 py-1 w-full rounded-full text-gray-900 placeholder-gray-400 focus:shadow-outline"
          >
            {{ form.email }}

            <img
              class="absolute inset-y-0 my-auto"
              style="right: 15px"
              src="@/assets/images/email-check.svg"
              alt="Email valide"
            />
          </div>
        </el-form-item>
        <el-form-item prop="password" class="mb-5">
          <input
            v-model="form.password"
            :autofocus="true"
            type="password"
            class="input-shadow text-center bg-white px-5 py-1 w-full rounded-full text-gray-400 placeholder-gray-400 focus:shadow-outline"
            placeholder="Votre mot de passe"
          />
        </el-form-item>
        <el-button
          :loading="loading"
          class="font-bold max-w-sm mx-auto w-full flex items-center justify-center px-5 py-3 border border-transparent text-xl leading-6 rounded-full text-white bg-green-400 hover:bg-green-500 focus:shadow-outline transition duration-150 ease-in-out"
          @click.prevent="onSubmit"
        >
          Se connecter
        </el-button>
        <div class="mt-2 text-center">
          <nuxt-link
            :to="`/password/forgot?email=${form.email}`"
            target="_blank"
            class="text-sm leading-5 font-medium text-gray-400 hover:text-gray-900 focus:underline transition ease-in-out duration-150"
          >
            Mot de passe perdu ?
          </nuxt-link>
        </div>
      </el-form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SoftGateLogin',
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
      this.$refs.loginForm.validate((valid) => {
        if (valid) {
          this.loading = true
          this.$store
            .dispatch('auth/login', {
              email: this.form.email,
              password: this.form.password,
            })
            .then(() => {
              this.loading = false
              this.$emit('next')
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
::placeholder
  font-weight: 500
</style>
