<template>
  <div class="register mx-auto w-full" style="max-width: 620px;">
    <div>
      <router-link to="/">
        <img class="h-8 w-auto" src="/images/logo-reserve-civique_dark.svg" alt="Réserve Civique" />
      </router-link>
      <h2 class="mt-8 text-3xl leading-tight font-extrabold text-gray-900">
        Vous êtes une structure publique ou associative ?
        <br />Rejoignez la
        <span class="text-blue-800">Réserve Civique</span>
      </h2>
    </div>
    <div class="mt-8 border-t border-gray-200 pt-8"></div>
    <div>
      <h3
        class="text-lg leading-6 font-medium text-gray-900"
      >Engagez-vous dans la lutte contre l’épidémie</h3>
      <p class="mt-1 text-sm leading-5 text-gray-500">
        <a href="/regles-de-securite">Cet engagement nécessite un respect strict des règles sanitaires applicables ›</a>
      </p>
    </div>
    <el-form
      ref="registerVolontaireForm"
      :model="form"
      label-position="top"
      :rules="rules"
      :hide-required-asterisk="true"
      class="mt-6 flex flex-wrap"
    >
      <el-form-item label="Prénom" prop="first_name" class="sm:w-1/2 sm:pr-4">
        <el-input v-model="form.first_name" placeholder="Prénom" />
      </el-form-item>
      <el-form-item label="Nom" prop="last_name" class="sm:w-1/2">
        <el-input v-model="form.last_name" placeholder="Nom" />
      </el-form-item>
      <el-form-item label="E-mail" prop="email" class="sm:w-full">
        <el-input v-model="form.email" placeholder="E-mail" />
      </el-form-item>
      <el-form-item label="Mot de passe" prop="password" class="sm:w-1/2 sm:pr-4">
        <el-input
          v-model="form.password"
          placeholder="Choisissez votre mot de passe"
          show-password
        />
      </el-form-item>
      <el-form-item
        label="Confirmation du mot de passe"
        prop="password_confirmation"
        class="sm:w-1/2"
      >
        <el-input
          v-model="form.password_confirmation"
          placeholder="Confirmez votre mot de passe"
          show-password
        />
      </el-form-item>
    </el-form>
    <div class="mt-8 sm:col-span-">
      <span class="block w-full rounded-md shadow-sm">
        <el-button
          type="primary"
          :loading="loading"
          @click="onSubmit"
          style="height: 48px;"
          class="w-full flex justify-center py-2 px-4 border border-transparent text-xl font-medium rounded-md text-white bg-blue-800 hover:bg-blue-900 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out"
        >J'inscris ma structure</el-button>
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
        <router-link to="/user/login">
          <span class="block w-full rounded-md shadow-sm">
            <button
              type="submit"
              class="w-full flex justify-center py-2 px-4 border border-transparent font-medium border border-gray-300 rounded rounded-md bg-white text-sm leading-5 font-medium text-gray-500 hover:text-gray-400 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition duration-150 ease-in-out"
            >J'ai déjà un compte</button>
          </span>
        </router-link>
      </div>
    </div>
    
  </div>
</template>

<script>
export default {
  name: "RegisterVolontaire",
  data() {
    var validatePass2 = (rule, value, callback) => {
        if (value !== this.form.password) {
          callback(new Error('Les mots de passe ne sont pas identiques'));
        } else {
          callback();
        }
      };
    return {
      loading: false,
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
        password_confirmation: [
          { validator: validatePass2, trigger: 'blur' }
        ]
      },
    };
  },
  methods: {
    onSubmit() {
      this.loading = true;
      this.$refs["registerVolontaireForm"].validate(valid => {
        if (valid) {
          this.$store
            .dispatch("auth/registerResponsable", {
              email: this.form.email,
              password: this.form.password,
              first_name: this.form.first_name,
              last_name: this.form.last_name
            })
            .then(() => {
              this.loading = false;
              this.$router.push("/register/step/profile");
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
