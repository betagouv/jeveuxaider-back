<template>
  <div class="mx-auto w-full">
    <div>
      <router-link to="/">
        <img
          class="h-8 w-auto"
          src="/images/logo-header-dark.png"
          alt="Réserve Civique"
        />
      </router-link>
      <h2 class="mt-8 text-3xl leading-tight font-extrabold text-gray-900">
        Connexion à l'espace de la
        <br />
        <span class="text-blue-800">Réserve Civique</span>
      </h2>
    </div>
    <div class="mt-8 border-t border-gray-200 pt-8"></div>
    <el-form
      ref="loginForm"
      :model="form"
      label-position="top"
      :rules="rules"
      :hide-required-asterisk="true"
    >
      <el-form-item label="E-mail" prop="email">
        <el-input v-model.trim="form.email" placeholder="Email" />
      </el-form-item>
      <el-form-item label="Mot de passe" prop="password" class="mb-0">
        <el-input
          v-model="form.password"
          placeholder="Entrez votre mot de passe"
          show-password
          @keyup.native.enter="onSubmit"
        />
      </el-form-item>
      <div class="flex pt-4 justify-end items-center">
        <router-link to="/password/forgot">
          <div class="text-sm leading-5">
            <router-link
              to="/password/forgot"
              class="font-medium text-blue-800 hover:text-blue-900 focus:outline-none focus:underline transition ease-in-out duration-150"
              >Mot de passe perdu ?</router-link
            >
          </div>
        </router-link>
      </div>
    </el-form>
    <div class="mt-8 sm:col-span-">
      <span class="block w-full rounded-md shadow-sm">
        <el-button
          type="primary"
          :loading="loading"
          @click="onSubmit"
          style="height: 48px;"
          class="w-full flex justify-center py-2 px-4 border border-transparent text-xl font-medium rounded-md text-white bg-blue-800 hover:bg-blue-900 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out"
          >Je me connecte</el-button
        >
      </span>
    </div>
    <div class="mt-6">
      <div class="relative">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm leading-5">
          <span class="px-2 bg-white text-gray-500">OU</span>
        </div>
      </div>
      <div class="mt-6 sm:col-span-">
        <router-link to="/register">
          <span class="block w-full rounded-md shadow-sm">
            <button
              type="submit"
              class="w-full flex justify-center py-2 px-4 border border-transparent text-s font-medium rounded-md border border-gray-300 rounded-md bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition duration-150 ease-in-out"
            >
              Je ne suis pas encore inscrit à la Réserve Civique
            </button>
          </span>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Login",
  data() {
    var checkLowercase = (rule, value, callback) => {
        if (value !== value.toLowerCase()) {
          callback(new Error('Merci de ne saisir que des minuscules'));
        } else {
          callback();
        }
      };
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
          },
          // { validator: checkLowercase, trigger: 'blur' }
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
          this.$store
            .dispatch("auth/login", {
              email: this.form.email,
              password: this.form.password
            })
            .then(() => {
              if (this.$route.query.redirect) {
                this.$router.push(this.$route.query.redirect);
              }
              else {
                // console.log("noRole", this.$store.getters.noRole);
                if (
                  this.$store.getters.noRole === true &&
                  this.$store.getters.contextRole != "volontaire"
                ) {
                  this.$router.push("/register/step/norole");
                }

                if (this.$store.getters.noRole === false) {
                  this.$router.push("/dashboard");
                } else {
                  this.$router.push("/missions");
                }
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
