<template>
  <div class="px-12 max-w-4xl flex flex-col space-y-8">
    <div
      v-for="territoire in $store.getters.user.profile.territoires"
      :key="territoire.id"
      shadow="never"
      class="flex flex-col bg-gray-100 p-6 rounded-md"
    >
      <div class="flex items-center justify-between">
        <div class="label text-lg font-bold text-secondary uppercase">
          {{ territoire.name }}
        </div>
        <div class="">
          <TagModelState v-if="territoire.state" :state="territoire.state" />
        </div>
      </div>
      <div class="font-light text-gray-400 text-sm flex items-center">
        <div
          :class="territoire.is_published ? 'bg-green-500' : 'bg-red-500'"
          class="rounded-full h-2 w-2 mr-2"
        ></div>
        <nuxt-link
          v-if="territoire.is_published"
          :to="territoire.full_url"
          target="_blank"
          class="hover:underline"
        >
          {{ territoire.full_url }}
        </nuxt-link>
        <span v-else class="cursor-default">
          {{ territoire.full_url }}
        </span>
      </div>
      <div class="text-gray-400 my-3">
        <template v-if="territoire.type == 'department'">
          Accédez aux informations de votre page département pour compléter sa
          présentation.
        </template>
        <template v-if="territoire.type == 'collectivity'">
          <template v-if="territoire.state == 'waiting'">
            <p>
              Votre page collectivité a bien été créé et est en attente de
              validation par nos équipes.
            </p>
            <p>Vous serez prochainement notifié par email de sa validation.</p>
          </template>
          <template v-else
            >Accédez aux informations de votre page collectivité pour compléter
            sa présentation.</template
          >
        </template>
        <template v-if="territoire.type == 'city'">
          Accédez aux informations de votre page ville pour compléter sa
          présentation.
        </template>
      </div>
      <div class="flex justify-end">
        <template
          v-if="
            territoire.type == 'collectivity' && territoire.state == 'waiting'
          "
        >
        </template>
        <template v-else>
          <nuxt-link
            :to="`/dashboard/territoire/${territoire.id}`"
            class="text-gray-400 hover:text-primary flex space-x-4 font-bold"
          >
            <el-button type="primary" plain class="flex-none">
              Accéder à la vue d'ensemble
            </el-button>
          </nuxt-link>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
export default {}
</script>
