<template>
  <div class="settings max-w-lg px-12">
    <div class="mb-12 font-bold text-2xl text-gray-800">
      Paramètres de compte
    </div>
    <div class="account-form mb-12">
      <div class="mb-6 text-xl text-gray-800">
        Votre adresse email
      </div>
      <div class="mb-6 text-xs text-gray-600 flex">
        <i class="el-icon-info text-primary mt-1 mr-2"></i>
        Toutes les communications de votre comptes vous seront envoyées sur
        votre adresse email.
      </div>
      <SettingsAccountForm></SettingsAccountForm>
    </div>
    <div class="password-form mb-12">
      <div class="mb-6 text-xl text-gray-800">
        Votre mot de passe
      </div>
      <div class="mb-6 text-xs text-gray-600 flex">
        <i class="el-icon-info text-primary mt-1 mr-2"></i>
        Assurez vous de modifier votre mot de passe régulièrement pour améliorer
        la sécurité de votre compte.
      </div>
      <SettingsPasswordForm></SettingsPasswordForm>
    </div>
  </div>
</template>

<script>
import SettingsAccountForm from "@/components/forms/SettingsAccountForm.vue";
import SettingsPasswordForm from "@/components/forms/SettingsPasswordForm.vue";

export default {
  name: "Settings",
  components: {
    SettingsAccountForm,
    SettingsPasswordForm
  },
  data() {
    return {};
  }
};
</script>
