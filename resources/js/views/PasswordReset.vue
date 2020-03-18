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
        <div v-if="!submitted">
          <div class="mb-6 font-bold text-2xl text-gray-800">
            Votre nouveau mot de passe
          </div>
          <div class="mb-6 font-light text-sm">
            Définissez votre nouveau mot de passe
          </div>
          <el-form
            ref="resetPasswordForm"
            :model="form"
            label-position="top"
            :rules="rules"
            :hide-required-asterisk="true"
          >
            <el-form-item label="Nouveau mot de passe" prop="password">
              <el-input
                v-model="form.password"
                placeholder="Définir un mot de passe"
                show-password
              />
            </el-form-item>
            <el-form-item
              label="Confirmez votre nouveau mot de passe"
              prop="password_confirmation"
            >
              <el-input
                v-model="form.password_confirmation"
                placeholder="Confirmez votre nouveau mot de passe"
                show-password
              />
            </el-form-item>
            <div class="flex pt-2 justify-between items-center">
              <el-button type="primary" :loading="loading" @click="onSubmit">
                Modifier
              </el-button>
            </div>
          </el-form>
        </div>
        <div v-else>
          <div class="mb-6 font-bold text-2xl text-gray-800">
            Votre nouveau mot de passe
          </div>
          <div class="mb-6 font-light text-sm">
            Votre mot de passe a été mis à jour avec succès.
          </div>
          <router-link to="/user/login">
            <el-button type="primary">Connectez-vous</el-button>
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
import { resetPassword } from "@/api/auth";

export default {
  name: "PasswordReset",
  data() {
    return {
      loading: false,
      submitted: false,
      form: {
        email: "",
        password: "",
        password_confirmation: "",
        token: ""
      },
      rules: {
        password: [
          {
            required: true,
            message: "Veuillez renseigner un mot de passe",
            trigger: "blur"
          },
          {
            min: 8,
            message: "Votre mot de passe doit contenir au moins 8 caractères",
            trigger: "blur"
          }
        ],
        password_confirmation: [
          {
            required: true,
            message: "Veuillez confirmer votre mot de passe",
            trigger: "blur"
          },
          {
            min: 8,
            message: "Votre mot de passe doit contenir au moins 8 caractères",
            trigger: "blur"
          }
        ]
      }
    };
  },
  created() {
    this.form.token = this.$route.params.token;
    this.form.email = this.$route.query.email;
  },
  methods: {
    onSubmit() {
      this.loading = true;
      this.$refs["resetPasswordForm"].validate(valid => {
        if (valid) {
          this.loading = true;
          resetPassword(this.form)
            .then(() => {
              this.loading = false;
              this.submitted = true;
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
