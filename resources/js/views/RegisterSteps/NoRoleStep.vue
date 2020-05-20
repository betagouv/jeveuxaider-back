<template>
  <div
    v-if="$store.getters.profile"
    class="register-step h-full "
  >
    <portal to="register-steps-help">
      <p>
        Bienvenue {{ $store.getters.profile.first_name }} ! <br>Veuillez
        selectionner <span class="font-bold">votre type de profil</span> pour
        finaliser la création de votre compte sur la plateforme.
      </p>
    </portal>
    <div
      class="p-4 overflow-hidden flex flex-col justify-center items-center h-full"
    >
      <div class="font-bold text-4xl text-gray-800 mb-8">
        Je choisis mon type de profil
      </div>
      <div class="flex flex-wrap -m-4 lg:-m-8">
        <div
          class="w-full sm:w-64 border p-8 m-4 lg:m-8 rounded-lg flex flex-col text-center hover:border-primary"
          style="height: 270px"
        >
          <div class="font-bold mb-4">
            Responsable
          </div>
          <div class="text-gray-600 mb-4 flex-1">
            J'inscris ma structure en tant que responsable
          </div>
          <router-link
            :to="{
              name: 'ProfileStep'
            }"
          >
            <el-button type="primary">
              Choisir
            </el-button>
          </router-link>
        </div>
        <div
          class="w-full sm:w-64 border p-8 m-4 lg:m-8 rounded-lg flex flex-col text-center hover:border-primary"
          style="height: 270px"
        >
          <div class="font-bold mb-4">
            Autre
          </div>
          <div class="text-gray-600 mb-4 flex-1">
            Je suis un tuteur, un référent départemental ou un superviseur
          </div>
          <router-link
            :to="{
              name: 'OtherStep'
            }"
          >
            <el-button type="primary">
              Choisir
            </el-button>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "NoRoleStep"
};
</script>
