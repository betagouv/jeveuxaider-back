<template>
  <div class="bg-gray-100">
    <AppHeader />
    <div class="bg-blue-900 pb-32">
      <div class="container mx-auto px-4">
        <div class="pt-10">
          <h1 class="text-3xl font-bold text-white">
            Mes paramètres de compte
          </h1>
        </div>
      </div>
    </div>

    <div class="-mt-32">
      <div class="container mx-auto px-4 my-12">
        <div
          class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
        >
          <h2 class="text-3xl leading-tight font-extrabold text-gray-900">
            TODO Prénom Nom
          </h2>

          <el-form
            ref="settingsForm"
            :model="form"
            label-position="top"
            :rules="rules"
            :hide-required-asterisk="true"
          >
            <div class="mt-8 border-t border-gray-200 pt-8">
              <h3 class="text-lg leading-tight font-medium text-gray-900">
                Changer mon mot de passe
              </h3>
              <p class="mt-1 mb-8 text-sm text-gray-500">
                Pour changer votre mot de passe, remplissez les champs
                ci-dessous
              </p>
              <div class="flex flex-wrap -m-2">
                <el-form-item
                  label="Ancien mot de passe"
                  prop="oldPassword"
                  class="w-full sm:w-1/2 lg:w-1/3 p-2"
                >
                  <el-input
                    v-model="form.oldPassword"
                    placeholder="Ancien mot de passe"
                    show-password
                  />
                </el-form-item>
                <el-form-item
                  label="Nouveau mot de passe"
                  prop="newPassword"
                  class="w-full sm:w-1/2 lg:w-1/3 p-2"
                >
                  <el-input
                    v-model="form.newPassword"
                    placeholder="Nouveau mot de passe"
                    show-password
                  />
                </el-form-item>
                <el-form-item
                  label="Confirmation du nouveau mot de passe"
                  prop="password_confirmation"
                  class="w-full sm:w-1/2 lg:w-1/3 p-2"
                >
                  <el-input
                    v-model="form.password_confirmation"
                    placeholder="Confirmez votre nouveau mot de passe"
                    show-password
                  />
                </el-form-item>
              </div>
              <div class="mt-8 border-t border-gray-200 pt-5">
                <el-button
                  type="primary"
                  :loading="loading"
                  @click="onSubmit"
                  class=""
                  >Enregistrer les modifications
                </el-button>
              </div>
            </div>
          </el-form>
        </div>
      </div>
    </div>
    <AppFooter />
  </div>
</template>

<script>
export default {
  name: "FrontSettings",
  components: {},
  data() {
    var validatePass2 = (rule, value, callback) => {
      if (value !== this.form.newPassword) {
        callback(new Error("Les mots de passe ne sont pas identiques"));
      } else {
        callback();
      }
    };
    return {
      loading: false,
      form: {},
      rules: {
        oldPssword: [
          {
            required: true,
            message: "Ce champ est requis",
            trigger: "change"
          }
        ],
        newPassword: [
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
  methods: {
    onSubmit() {
      this.loading = true;
      this.$refs["settingsForm"].validate(valid => {
        if (valid) {
          //   this.$store
          //     .dispatch("auth/registerVolontaire", {
          //       email: this.form.email,
          //       password: this.form.password,
          //       first_name: this.form.first_name,
          //       last_name: this.form.last_name,
          //       mobile: this.form.mobile,
          //       birthday: this.form.birthday,
          //       zip: this.form.zip
          //     })
          //     .then(() => {
          //       this.loading = false;
          //       this.$router.push("/missions");
          //     })
          //     .catch(() => {
          //       this.loading = false;
          //     });
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
    @apply mb-1
</style>
