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
            Réinitialisation du mot de passe
          </div>
          <div class="mb-6 font-light text-sm">
            Pour réinitialiser votre mot de passe, entrez l'adresse mail que
            vous avez utilisée pour vous connecter à la plateforme
          </div>
          <el-form
            ref="forgotPasswordForm"
            :model="form"
            label-position="top"
            :rules="rules"
            :hide-required-asterisk="true"
          >
            <el-form-item label="Adresse Email" prop="email">
              <el-input v-model="form.email" placeholder="Email" />
            </el-form-item>
            <div class="flex pt-2 justify-between items-center">
              <el-button type="primary" :loading="loading" @click="onSubmit"
                >Obtenir le lien de réinitialisation par email</el-button
              >
            </div>
          </el-form>
          <div
            class="absolute bottom-0 m-auto mb-10 text-sm text-secondary pt-5 border-t border-gray-400"
            style="width: 340px;"
          >
            Vous avez déjà un compte ?
            <router-link to="/user/login">
              <span class="text-gray-800 font-semibold"
                >&nbsp;Connectez vous</span
              >
            </router-link>
          </div>
        </div>
        <div v-else>
          <div class="mb-6 font-bold text-2xl text-gray-800">Email envoyé</div>
          <div class="mb-6 font-light text-sm">
            Une email contenant les instructions pour réinitialiser votre mot de
            passe vient de vous être envoyé.
            <br />Si vous ne vous souvenez plus de votre email de connexion,
            écrivez-nous à contact@snu-mig.fr.
          </div>
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
import { forgotPassword } from "@/api/auth";

export default {
  name: "PasswordForgot",
  data() {
    return {
      loading: false,
      submitted: false,
      form: {
        email: ""
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
        ]
      }
    };
  },
  methods: {
    onSubmit() {
      this.$refs["forgotPasswordForm"].validate(valid => {
        if (valid) {
          this.loading = true;
          forgotPassword(this.form.email)
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
