<template>
  <div class="register mx-auto w-full" style="max-width: 620px;">
    <div>
      <router-link to="/">
        <img class="h-8 w-auto" src="/images/logo-reserve-civique_dark.svg" alt="Réserve Civique" />
      </router-link>
      <h2 class="mt-8 text-3xl leading-tight font-extrabold text-gray-900">
        Invitation à rejoindre la 
        <span class="text-blue-800">Réserve Civique</span>
      </h2>
    </div>
    <div class="mt-8 border-t border-gray-200 pt-8"></div>
    <div>
      <h3 class="text-lg font-medium text-gray-900">Engagez-vous dans la lutte contre l’épidémie</h3>
      <p class="mt-1 text-sm text-gray-500">
        <router-link to="/regles-de-securite">
          Cet engagement nécessite un respect strict des règles sanitaires
          applicables.
        </router-link>
      </p>
    </div>
    <el-form
      ref="registerInvitationForm"
      :model="form"
      label-position="top"
      :rules="rules"
      :hide-required-asterisk="true"
      class="mt-6"
    >
      <div class="flex flex-wrap -m-2">
        <el-form-item label="Prénom" prop="first_name" class="w-full sm:w-1/2 p-2">
          <el-input v-model="form.first_name" placeholder="Prénom" :disabled="disableFields" />
        </el-form-item>
        <el-form-item label="Nom" prop="last_name" class="w-full sm:w-1/2 p-2">
          <el-input v-model="form.last_name" placeholder="Nom" :disabled="disableFields" />
        </el-form-item>
        <el-form-item label="E-mail" prop="email" class="w-full p-2">
          <el-input v-model="form.email" placeholder="E-mail" :disabled="disableFields" />
        </el-form-item>
        <el-form-item label="Mot de passe" prop="password" class="w-full sm:w-1/2 p-2">
          <el-input
            v-model="form.password"
            placeholder="Choisissez votre mot de passe"
            show-password
          />
        </el-form-item>
        <el-form-item
          label="Confirmation du mot de passe"
          prop="password_confirmation"
          class="w-full sm:w-1/2 p-2"
        >
          <el-input
            v-model="form.password_confirmation"
            placeholder="Confirmez votre mot de passe"
            show-password
          />
        </el-form-item>
      </div>
    </el-form>
    <div class="mt-8 sm:col-span-">
      <span class="block w-full rounded-md shadow-sm">
        <el-button
          type="primary"
          :loading="loading"
          @click="onSubmit"
          style="height: 48px;"
          class="w-full flex justify-center py-2 px-4 border border-transparent sm:text-xl font-medium rounded-md text-white bg-blue-800 hover:bg-blue-900 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out"
        >Je m'inscris</el-button>
      </span>
    </div>
  </div>
</template>

<script>
export default {
  name: "RegisterInvitation",
  data() {
    var validatePass2 = (rule, value, callback) => {
      if (value !== this.form.password) {
        callback(new Error("Les mots de passe ne sont pas identiques"));
      } else {
        callback();
      }
    };
    return {
      loading: false,
      disableFields: false,
      form: {
        email: "",
        first_name: "",
        last_name: "",
        password: ""
      },
      rules: {
        email: [
          {
            type: "email",
            message: "Le format de l'email n'est pas correct",
            trigger: "blur"
          },
          {
            required: true,
            message: "Veuillez renseigner votre email",
            trigger: "blur"
          }
        ],
        first_name: [
          {
            required: true,
            message: "Prénom obligatoire",
            trigger: "blur"
          }
        ],
        last_name: [
          {
            required: true,
            message: "Nom obligatoire",
            trigger: "blur"
          }
        ],
        password: [
          {
            required: true,
            message: "Choisissez votre mot de passe",
            trigger: "change"
          },
          {
            min: 8,
            message: "Votre mot de passe doit contenir au moins 8 charactères",
            trigger: "blur"
          }
        ],
        password_confirmation: [{ validator: validatePass2, trigger: "blur" }]
      }
    };
  },
  created() {
    if (this.$route.query.email) {
      this.form.email = this.$route.query.email;
      this.form.first_name = this.$route.query.first_name;
      this.form.last_name = this.$route.query.last_name;
      this.disableFields = true;
    }
  },
  methods: {
    onSubmit() {
      this.loading = true;
      this.$refs["registerInvitationForm"].validate(valid => {
        if (valid) {
          this.$store
            .dispatch("auth/registerInvitation", {
              email: this.form.email,
              password: this.form.password,
              first_name: this.form.first_name,
              last_name: this.form.last_name
            })
            .then(() => {
              this.loading = false;
              this.$router.push("/dashboard");
            })
            .catch(() => {
              this.loading = false;
            });
        } else {
          this.loading = false;
        }
      });
    }
  }
};
</script>

<style lang="sass" scoped>
::v-deep .el-form-item
  @apply mb-3
</style>
