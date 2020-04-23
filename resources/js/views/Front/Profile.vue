<template>
  <div class="bg-gray-100">
    <AppHeader />
    <div class="bg-blue-900 pb-32">
      <div class="container mx-auto px-4">
        <div class="pt-10">
          <h1 class="text-3xl font-bold text-white">Mon profil</h1>
        </div>
      </div>
    </div>
    <div class="-mt-32">
      <div class="container mx-auto px-4 my-12">
        <div
          class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16"
        >
          <h2 class="text-3xl leading-tight font-extrabold text-gray-900">
            {{ $store.getters.user.profile.first_name }}
            {{ $store.getters.user.profile.last_name }}
          </h2>

          <el-form
            ref="profileForm"
            :model="form"
            label-position="top"
            :rules="rules"
            :hide-required-asterisk="true"
          >
            <div class="mt-8 border-t border-gray-200 pt-8">
              <h3 class="text-lg leading-tight font-medium text-gray-900">
                Informations personnelles
              </h3>
              <p class="mt-1 mb-8 text-sm text-gray-500">
                Ces informations sont transmises aux structures lorsque vous
                candidatez
              </p>
              <div class="flex flex-wrap -m-2">
                <el-form-item
                  label="Prénom"
                  prop="first_name"
                  class="w-full sm:w-1/2 lg:w-1/3 p-2"
                >
                  <el-input v-model="form.first_name" placeholder="Prénom" />
                </el-form-item>
                <el-form-item
                  label="Nom"
                  prop="last_name"
                  class="w-full sm:w-1/2 lg:w-1/3 p-2"
                >
                  <el-input v-model="form.last_name" placeholder="Nom" />
                </el-form-item>
                <el-form-item
                  label="Code postal"
                  prop="zip"
                  class="w-full sm:w-1/2 lg:w-1/3 p-2"
                >
                  <el-input v-model="form.zip" placeholder="Code postal" />
                </el-form-item>
                <el-form-item
                  label="Date de naissance"
                  prop="birthday"
                  class="w-full sm:w-1/2 lg:w-1/3 p-2"
                >
                  <el-date-picker
                    type="date"
                    placeholder="Date de naissance"
                    v-model="form.birthday"
                    autocomplete="off"
                    format="dd-MM-yyyy"
                    value-format="yyyy-MM-dd"
                    style="width:100%;"
                  ></el-date-picker>
                </el-form-item>
                <el-form-item
                  label="E-mail"
                  prop="email"
                  class="w-full sm:w-1/2 lg:w-1/3 p-2"
                >
                  <el-input v-model="form.email" placeholder="E-mail" />
                </el-form-item>
                <el-form-item
                  label="Téléphone mobile"
                  prop="mobile"
                  class="w-full sm:w-1/2 lg:w-1/3 p-2"
                >
                  <el-input
                    v-model="form.mobile"
                    placeholder="Téléphone mobile"
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
  name: "FrontProfile",
  data() {
    var checkLowercase = (rule, value, callback) => {
      if (value !== value.toLowerCase()) {
        callback(new Error("Merci de ne saisir que des minuscules"));
      } else {
        callback();
      }
    };
    return {
      loading: false,
      form: this.$store.getters.user.profile,
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
          { validator: checkLowercase, trigger: 'blur' }
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
        zip: [
          {
            required: true,
            message: "Code postal obligatoire",
            trigger: "blur"
          },
          {
            pattern: /^\d+$/,
            message: "Ne doit contenir que des chiffres",
            trigger: "blur"
          },
          {
            min: 5,
            max: 5,
            message: "Format erroné",
            trigger: "blur"
          }
        ],
        mobile: [
          {
            required: true,
            message: "Téléphone obligatoire",
            trigger: "blur"
          },
          {
            pattern: /^[+|\s|\d]*$/,
            message: "Le format du numéro de téléphone est incorrect",
            trigger: "blur"
          }
        ]
      }
    };
  },
  methods: {
    onSubmit() {
      this.loading = true;
      this.$refs["profileForm"].validate(valid => {
        if (valid) {
          this.$store
            .dispatch("user/updateProfile", this.form)
            .then(() => {
              this.loading = false;
              this.$message({
                message: "Votre profil a été mis à jour.",
                type: "success"
              });
            })
            .catch(() => {
              this.loading = false;
            });
          this.loading = false;
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
