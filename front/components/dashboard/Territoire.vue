<template>
  <div class="px-12 max-w-4xl flex flex-col space-y-8">
    <div
      v-for="territoire in $store.getters.user.profile.territoires"
      :key="territoire.id"
      shadow="never"
      class="border rounded-lg border-gray-200"
    >
      <div class="p-5">
        <div class="flex items-center space-x-4">
          <div class="label text-lg font-bold text-secondary uppercase">
            {{ territoire.name }}
          </div>
          <div class="flex items-center">
            <TagModelState v-if="territoire.state" :state="territoire.state" />
            <el-tag
              v-if="territoire.is_published"
              size="medium"
              class="m-1 ml-0"
            >
              En ligne
            </el-tag>
            <el-tag
              v-if="!territoire.is_published"
              size="medium"
              class="m-1 ml-0"
            >
              Hors ligne
            </el-tag>
          </div>
        </div>
        <div class="text-gray-400 mt-3">
          <template v-if="territoire.type == 'department'">
            Accédez aux informations de votre page département pour compléter sa
            présentation.
          </template>
          <template v-if="territoire.type == 'collectivity'">
            Accédez aux informations de votre page collectivité pour compléter
            sa présentation.
          </template>
          <template v-if="territoire.type == 'city'">
            Accédez aux informations de votre page ville pour compléter sa
            présentation.
          </template>
        </div>
      </div>
      <div class="px-5 py-3 border-t border-gray-200">
        <div class="flex justify-end">
          <nuxt-link
            :to="`/dashboard/territoire/${territoire.id}`"
            class="text-gray-400 hover:text-primary flex space-x-4 font-bold"
            ><span>Accéder à la vue d'ensemble</span>
            <div
              class="w-5 h-5"
              v-html="
                require('@/assets/images/icones/heroicon/arrow-narrow-right.svg?include')
              "
          /></nuxt-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {}
</script>
