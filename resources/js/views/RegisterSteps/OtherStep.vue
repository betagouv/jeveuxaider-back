<template>
  <div v-if="$store.getters.profile" class="register-step h-full ">
    <portal to="register-steps-help">
      <p>
        Bienvenue {{ $store.getters.profile.first_name }} ! <br />Veuillez
        selectionner <span class="font-bold">votre type de profil</span> pour
        finaliser la création de votre compte sur la plateforme.
      </p>
    </portal>
    <div class="flex flex-col justify-center items-center h-full text-center">
      <div class="font-bold text-4xl text-gray-800 mb-8">
        Merci de contacter votre responsable
      </div>
      <div class="text-gray-600 mb-8">
        Merci de joindre votre responsable ou référent départemental.
      </div>
      <router-link
        :to="{
          name: 'NoRoleStep'
        }"
      >
        <el-button>Retour</el-button>
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: "OtherStep"
};
</script>
