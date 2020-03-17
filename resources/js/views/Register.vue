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
        <div class="mb-6 font-bold text-2xl text-gray-800">Créez un compte</div>
        <el-form
          ref="registerForm"
          :model="form"
          label-position="top"
          :rules="rules"
          :hide-required-asterisk="true"
        >
          <el-form-item label="Adresse Email" prop="email">
            <el-input
              v-model="form.email"
              placeholder="Email"
              :disabled="disableFields"
            />
          </el-form-item>
          <div class="flex justify-between">
            <el-form-item label="Prénom" prop="first_name" class="mr-1">
              <el-input
                v-model="form.first_name"
                placeholder="Prénom"
                :disabled="disableFields"
              />
            </el-form-item>
            <el-form-item label="Nom" prop="last_name" class="ml-1">
              <el-input
                v-model="form.last_name"
                placeholder="Nom"
                :disabled="disableFields"
              />
            </el-form-item>
          </div>
          <el-form-item label="Mot de passe" prop="password">
            <el-input
              v-model="form.password"
              placeholder="Choisissez votre mot de passe"
              show-password
            />
          </el-form-item>
          <!-- <el-form-item label="J'ai lu et j'accepte" class="-mb-3">
            <el-checkbox v-model="form.cgu"
              >les conditions générales d’utilisation</el-checkbox
            >
          </el-form-item>
          <el-form-item prop="cgu">
            <el-checkbox v-model="form.confidentialite"
              >la polititique de confidentialité</el-checkbox
            >
          </el-form-item> -->
          <div class="flex pt-2">
            <el-button type="primary" :loading="loading" @click="onSubmit"
              >Créer mon compte</el-button
            >
          </div>
        </el-form>
        <div
          class="absolute bottom-0 m-auto mb-10 text-sm text-secondary pt-5 border-t border-gray-400"
          style="width: 340px;"
        >
          Vous avez déjà un compte ?
          <router-link to="/login">
            <span class="text-gray-800 font-semibold"
              >&nbsp;Connectez-vous</span
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
  name: "Register",
  data() {
    // var validateCGU = (rule, value, callback) => {
    //   if (this.form.cgu === false) {
    //     callback(
    //       new Error("Merci d'accepter les conditions générales d'utilisation")
    //     );
    //   } else if (this.form.confidentialite === false) {
    //     callback(new Error("Merci d'accepter la politique de confidentialité"));
    //   } else {
    //     callback();
    //   }
    // };
    return {
      loading: false,
      disableFields: false,
      form: {
        email: "",
        first_name: "",
        last_name: "",
        password: "",
        cgu: false,
        confidentialite: false
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
        // cgu: [{ validator: validateCGU, trigger: "blur" }],
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
      this.$refs["registerForm"].validate(valid => {
        if (valid) {
          this.$store
            .dispatch("auth/register", {
              email: this.form.email,
              password: this.form.password,
              first_name: this.form.first_name,
              last_name: this.form.last_name
            })
            .then(() => {
              this.loading = false;
              this.$router.push("/register/step/norole");
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
