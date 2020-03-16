<template>
  <div class="settings">
    <div class="mb-12 font-bold text-2xl text-gray-800">
      Paramètres de compte
    </div>
    <el-tabs tab-position="top">
      <el-tab-pane label="Gérer mon profil">
        <div class="mt-5 max-w-lg">
          <form-profile></form-profile>
        </div>
      </el-tab-pane>
      <el-tab-pane label="Modifier mon email">
        <div class="mt-5 max-w-lg">
          <div class="mb-6 text-xs text-gray-600 flex">
            <i class="el-icon-info text-primary mt-1 mr-2"></i>
            Toutes les notifications vous seront envoyées sur
            votre adresse email.
          </div>
          <form-account></form-account>
        </div>
      </el-tab-pane>
      <el-tab-pane label="Modifier mon mot de passe">
        <div class="mt-5 max-w-lg">
          <div class="mb-6 text-xs text-gray-600 flex">
            <i class="el-icon-info text-primary mt-1 mr-2"></i>
            Assurez vous de modifier votre mot de passe régulièrement pour améliorer
            la sécurité de votre compte.
          </div>
          <form-password></form-password>
        </div>
      </el-tab-pane>
    </el-tabs>
  </div>
</template>

<script>
import FormAccount from "@/views/users/FormAccount.vue";
import FormPassword from "@/views/users/FormPassword.vue";
import FormProfile from "@/views/users/FormProfile.vue";

export default {
  components: {
    FormAccount,
    FormPassword,
    FormProfile
  }
};
</script>
