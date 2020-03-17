<template>
  <div class="login flex h-full">
    <div class="flex flex-col justify-center items-center w-1/2">
      <div
        class="flex flex-col h-full justify-center relative"
        style="width: 340px;"
      >
        <img
          src="/images/logo-snu.png"
          style="max-width: 100px; width: 100px; margin-bottom: 45px; margin-top: -70px;"
        />
        <div class="mb-6 font-bold text-2xl text-gray-800">Bienvenue !</div>
        <el-form
          ref="loginForm"
          :model="form"
          label-position="top"
          :rules="rules"
          :hide-required-asterisk="true"
        >
          <el-form-item label="Adresse Email" prop="email">
            <el-input v-model="form.email" placeholder="Email" />
          </el-form-item>
          <el-form-item label="Mot de passe" prop="password">
            <el-input
              v-model="form.password"
              placeholder="Entrez votre mot de passe"
              show-password
              @keyup.native.enter="onSubmit"
            />
          </el-form-item>
          <div class="flex pt-2 justify-between items-center">
            <el-button type="primary" :loading="loading" @click="onSubmit"
              >Se connecter</el-button
            >
            <router-link to="/password/forgot">
              <span class="underline text-gray-800 text-sm"
                >Mot de passe oublié ?</span
              >
            </router-link>
          </div>
        </el-form>
        <div
          class="absolute bottom-0 m-auto mb-10 text-sm text-secondary pt-5 border-t border-gray-400"
          style="width: 340px;"
        >
          Pas encore de compte ?
          <router-link to="/register">
            <span class="text-gray-800 font-semibold"
              >&nbsp;Créez un compte</span
            >
          </router-link>
        </div>
      </div>
    </div>
    <div
      class="flex flex-col justify-center w-1/2"
      style="background-color: #f5f9fc;"
    >
      <div class="flex flex-col items-center">
        <h1
          class="text-2xl font-semibold text-center text-primary"
          style="margin-bottom: 92px;"
        >
          Plateforme du Service National Universel
        </h1>
        <img
          src="/images/groupe-jeunes.png"
          style="max-width: 280px;"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Login",
  data() {
    return {
      loading: false,
      form: {
        email: "",
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
        ]
      }
    };
  },
  methods: {
    onSubmit() {
      this.loading = true;
      this.$refs["loginForm"].validate(valid => {
        if (valid) {
        console.log(this)
          this.$store
            .dispatch("auth/login", {
              email: this.form.email,
              password: this.form.password
            })
            .then(() => {
              if (this.$store.getters.noRole === true) {
                this.$router.push("/register/step/norole");
              } else {
                this.$router.push("/");
              }
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
