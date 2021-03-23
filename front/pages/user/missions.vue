<template>
  <div class="bg-gray-100 flex flex-col flex-grow">
    <div class="bg-primary pb-32">
      <div class="container mx-auto px-4">
        <div class="pt-10">
          <h1 class="text-3xl font-bold text-white">
            Missions auxquelles j'ai candidaté
          </h1>
        </div>
      </div>
    </div>
    <div class="-mt-32">
      <div class="container mx-auto px-4 my-12">
        <template v-if="participations.data && !participations.data.length">
          <div
            class="bg-white rounded-lg shadow-lg overflow-hidden px-6 py-8 lg:p-12"
          >
            Vous n'avez aucune participation pour le moment.
          </div>
        </template>
        <template v-else>
          <div class="flex flex-wrap -m-3">
            <div
              v-for="participation in participations.data"
              :key="participation.id"
              class="ais-Hits-item"
            >
              <nuxt-link
                class="flex flex-col flex-1"
                :to="
                  participation.conversation
                    ? `/messages/${participation.conversation.id}`
                    : `/missions-benevolat/${participation.mission.id}/${participation.mission.slug}`
                "
              >
                <CardMission
                  :participation="participation"
                  :mission="participation.mission"
                  show-state
                />
              </nuxt-link>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserMissions',
  middleware: 'logged',
  async asyncData({ $api, store }) {
    const participations = await $api.fetchProfileParticipations(
      store.getters.user.profile.id
    )
    return {
      participations,
    }
  },
}
</script>

<style lang="sass" scoped>
.ais-Hits-item
  width: 100%
  @apply border-0 shadow-none p-0 m-3
  @screen sm
    width: 292px
  @screen lg
    width: 294px
    @apply flex flex-col
</style>
