<template>
  <div class="px-12 max-w-4xl flex flex-col space-y-8">
    <div v-if="$store.getters.user.profile.structures.length">
      <div class="font-semibold text-md uppercase text-gray-800 mb-4">
        Organisations
        <span>({{ $store.getters.user.profile.structures.length }})</span>
      </div>
      <div class="bg-white border border-gray-200 sm:rounded-md">
        <ul class="divide-y divide-gray-200">
          <li
            v-for="structure in $store.getters.user.profile.structures"
            :key="structure.id"
          >
            <div
              class="
                px-5
                py-3
                flex
                hover:bg-gray-50
                hover:text-primary
                cursor-pointer
                items-center
                justify-between
              "
            >
              <router-link
                :to="`/dashboard/structure/${structure.id}`"
                class="flex-1 flex space-x-4"
              >
                <div class="">{{ structure.name }}</div>
                <TailwindBadge
                  v-if="structure.state == 'En attente de validation'"
                  color="yellow"
                  dot
                  >{{ structure.state }}</TailwindBadge
                >
              </router-link>
              <TailwindDropdownEllipsis>
                <template slot="menu">
                  <router-link
                    :to="`/dashboard/structure/${structure.id}`"
                    class="
                      block
                      px-4
                      py-2
                      text-sm text-gray-700
                      hover:bg-gray-100
                      hover:text-gray-900
                    "
                    role="menuitem"
                  >
                    Accéder à la vue d'ensemble
                  </router-link>
                  <router-link
                    :to="`/dashboard/structure/${structure.id}/missions/add`"
                    class="
                      block
                      px-4
                      py-2
                      text-sm text-gray-700
                      hover:bg-gray-100
                      hover:text-gray-900
                    "
                    role="menuitem"
                  >
                    Créer une nouvelle mission
                  </router-link>
                  <router-link
                    :to="`/dashboard/structure/${structure.id}/missions`"
                    class="
                      block
                      px-4
                      py-2
                      text-sm text-gray-700
                      hover:bg-gray-100
                      hover:text-gray-900
                    "
                    role="menuitem"
                  >
                    Afficher les missions
                  </router-link>
                  <router-link
                    :to="`/dashboard/structure/${structure.id}/participations`"
                    class="
                      block
                      px-4
                      py-2
                      text-sm text-gray-700
                      hover:bg-gray-100
                      hover:text-gray-900
                    "
                    role="menuitem"
                  >
                    Afficher les participations
                  </router-link>
                  <router-link
                    :to="`/dashboard/structure/${structure.id}/statistics`"
                    class="
                      block
                      px-4
                      py-2
                      text-sm text-gray-700
                      hover:bg-gray-100
                      hover:text-gray-900
                    "
                    role="menuitem"
                  >
                    Afficher les statistiques
                  </router-link>
                  <router-link
                    :to="`/dashboard/structure/${structure.id}/members`"
                    class="
                      block
                      px-4
                      py-2
                      text-sm text-gray-700
                      hover:bg-gray-100
                      hover:text-gray-900
                    "
                    role="menuitem"
                  >
                    Gérer les membres'
                  </router-link>
                </template>
              </TailwindDropdownEllipsis>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div v-if="$store.getters.user.profile.territoires.length">
      <div class="font-semibold text-md uppercase text-gray-800 mb-4">
        Territoires
        <span>({{ $store.getters.user.profile.territoires.length }})</span>
      </div>
      <div class="bg-white border border-gray-200 sm:rounded-md">
        <ul class="divide-y divide-gray-200">
          <li
            v-for="territoire in $store.getters.user.profile.territoires"
            :key="territoire.id"
          >
            <div
              class="
                px-5
                py-3
                flex
                hover:bg-gray-50
                hover:text-primary
                cursor-pointer
                items-center
                justify-between
              "
            >
              <router-link
                :to="`/dashboard/territoire/${territoire.id}`"
                class="flex-1 flex space-x-4"
              >
                <div class="">{{ territoire.name }}</div>
                <TailwindBadge
                  v-if="territoire.state == 'waiting'"
                  color="yellow"
                  dot
                  >{{
                    territoire.state | labelFromValue('territoires_states')
                  }}</TailwindBadge
                >
              </router-link>
              <TailwindDropdownEllipsis>
                <template slot="menu">
                  <router-link
                    :to="`/dashboard/territoire/${territoire.id}`"
                    class="
                      block
                      px-4
                      py-2
                      text-sm text-gray-700
                      hover:bg-gray-100
                      hover:text-gray-900
                    "
                    role="menuitem"
                  >
                    Accéder à la vue d'ensemble
                  </router-link>
                  <router-link
                    :to="`/dashboard/structure/${territoire.id}/missions/add`"
                    class="
                      block
                      px-4
                      py-2
                      text-sm text-gray-700
                      hover:bg-gray-100
                      hover:text-gray-900
                    "
                    role="menuitem"
                  >
                    Afficher les statistiques
                  </router-link>
                </template>
              </TailwindDropdownEllipsis>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {}
</script>
