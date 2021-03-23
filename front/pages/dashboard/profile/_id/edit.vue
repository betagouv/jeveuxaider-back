<template>
  <div class="pl-12 pb-12">
    <div class="text-m text-gray-600 uppercase">Utilisateur</div>
    <div class="mb-8 flex">
      <div class="font-bold text-2xl text-gray-800">
        {{ profile.first_name }} {{ profile.last_name }}
      </div>
      <TagProfileRoles
        :profile="profile"
        size="medium"
        class="flex items-center ml-3"
      />
    </div>
    <FormProfile :profile="profile" class="max-w-2xl" />
  </div>
</template>

<script>
export default {
  layout: 'dashboard',
  async asyncData({ $api, params, store, error }) {
    if (
      !['admin', 'referent', 'referent_regional'].includes(
        store.getters.contextRole
      )
    ) {
      return error({ statusCode: 403 })
    }
    const profile = await $api.getProfile(params.id)
    return {
      profile,
    }
  },
  methods: {},
}
</script>
