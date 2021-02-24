<template>
  <div class="relative bg-blue-800 overflow-hidden">
    <img
      class="z-1 object-cover absolute h-screen lg:h-auto"
      src="/images/bg-jva.jpg"
    />

    <div class="pb-12 mt-12 px-4 relative w-full lg:inset-y-0 text-center z-10">
      <div class="">
        <h2
          class="mt-6 mb-4 md:mb-0 text-center text-3xl font-bold text-white leading-8 px-4"
        >
          Invitation à rejoindre JeVeuxAider
        </h2>
        <p class="text-center text-base text-blue-200">
          Engagez-vous pour faire vivre les valeurs de la République
        </p>
      </div>
    </div>

    <div
      class="relative mt-2 pb-16 sm:mx-auto sm:w-full sm:max-w-md text-left z-10"
    >
      <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
        <div class="relative">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
          </div>
          <div class="relative flex justify-center text-xl">
            <span class="px-2 bg-white font-bold text-gray-900">
              Je créé mon compte
            </span>
          </div>
        </div>
        <el-form
          ref="registerInvitationForm"
          :model="form"
          label-position="top"
          :rules="rules"
          :hide-required-asterisk="true"
          class="mt-4"
        >
          <div class="flex flex-wrap -m-2">
            <el-form-item
              label="Prénom"
              prop="first_name"
              class="w-full sm:w-1/2 p-2"
            >
              <el-input
                v-model="form.first_name"
                placeholder="Prénom"
                :disabled="disableFields"
              />
            </el-form-item>
            <el-form-item
              label="Nom"
              prop="last_name"
              class="w-full sm:w-1/2 p-2"
            >
              <el-input
                v-model="form.last_name"
                placeholder="Nom"
                :disabled="disableFields"
              />
            </el-form-item>
            <el-form-item label="E-mail" prop="email" class="w-full p-2">
              <el-input
                v-model.trim="form.email"
                placeholder="E-mail"
                :disabled="disableFields"
              />
            </el-form-item>
            <el-form-item
              label="Mot de passe"
              prop="password"
              class="w-full p-2"
            >
              <el-input
                v-model="form.password"
                placeholder="Choisissez votre mot de passe"
                show-password
              />
            </el-form-item>
            <el-form-item
              label="Confirmation du mot de passe"
              prop="password_confirmation"
              class="w-full p-2"
            >
              <el-input
                v-model="form.password_confirmation"
                placeholder="Confirmez votre mot de passe"
                show-password
              />
            </el-form-item>
          </div>
        </el-form>
        <div class="mt-4">
          <button
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-lg text-lg font-bold text-white bg-blue-800 hover:shadow-lg hover:scale-105 transform transition duration-150 ease-in-out"
            @click="onSubmit"
          >
            Je créé mon compte
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RegisterInvitation',
  data() {
    var validatePass2 = (rule, value, callback) => {
      if (value !== this.form.password) {
        callback(new Error('Les mots de passe ne sont pas identiques'))
      } else {
        callback()
      }
    }
    return {
      loading: false,
      disableFields: false,
      form: {
        email: '',
        first_name: '',
        last_name: '',
        password: '',
      },
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
        password: [
          {
            required: true,
            message: 'Choisissez votre mot de passe',
            trigger: 'change',
          },
          {
            min: 8,
            message: 'Votre mot de passe doit contenir au moins 8 charactères',
            trigger: 'blur',
          },
        ],
        password_confirmation: [{ validator: validatePass2, trigger: 'blur' }],
      },
    }
  },
  created() {
    if (this.$route.query.email) {
      this.form.email = this.$route.query.email
      this.form.first_name = this.$route.query.first_name
      this.form.last_name = this.$route.query.last_name
      this.disableFields = true
    }
  },
  methods: {
    onSubmit() {
      this.loading = true
      this.$refs['registerInvitationForm'].validate((valid) => {
        if (valid) {
          this.$store
            .dispatch('auth/registerInvitation', {
              email: this.form.email,
              password: this.form.password,
              first_name: this.form.first_name,
              last_name: this.form.last_name,
            })
            .then(() => {
              this.loading = false
              this.$router.push('/dashboard')
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
::v-deep .el-form-item
  @apply mb-3
</style>
