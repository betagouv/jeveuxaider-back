<template>
  <div class="menu--anonyme h-full">
    <div class="flex flex-col lg:flex-row justify-between h-full">
      <div :class="wrapperLinkClasses">
        <a
          :class="linkClasses"
          target="_blank"
          rel="noopener"
          href="https://www.jeveuxaider.gouv.fr/engagement/organisations/"
        >
          Organisations
        </a>

        <nuxt-link :class="linkClasses" to="/territoires">
          Territoires
        </nuxt-link>

        <a
          :class="linkClasses"
          target="_blank"
          rel="noopener"
          href="https://www.jeveuxaider.gouv.fr/engagement/ecoles-et-universites/"
        >
          Écoles et universités
        </a>

        <a
          :class="linkClasses"
          target="_blank"
          rel="noopener"
          href="https://www.jeveuxaider.gouv.fr/engagement/actualites/"
        >
          Actualités
        </a>

        <a
          :class="linkClasses"
          target="_blank"
          rel="noopener"
          href="https://reserve-civique.crisp.help/fr/"
        >
          Centre d'aide
        </a>
      </div>

      <div class="bg-primary text-white lg:bg-opacity-0 lg:text-[#161616]">
        <div :class="wrapperLinkClasses">
          <nuxt-link
            v-if="$store.getters.roles && $store.getters.roles.length > 0"
            to="/dashboard"
            :class="linkClasses"
          >
            Tableau de bord
          </nuxt-link>

          <nuxt-link to="/user/missions" :class="linkClasses">
            Mes missions
          </nuxt-link>

          <!-- MOBILE -->
          <div class="lg:hidden flex flex-col">
            <button
              :class="linkClasses"
              @click="$store.commit('toggleSearchOverlay')"
            >
              Trouver une mission
            </button>

            <nuxt-link
              :to="
                $store.getters.isLogged &&
                $store.getters.contextRole == 'responsable'
                  ? `/dashboard/structure/${$store.getters.contextStructure.id}/missions/add`
                  : '/inscription/organisation'
              "
              :class="linkClasses"
            >
              Publier une mission
            </nuxt-link>

            <nuxt-link to="/messages" :class="linkClasses">
              Mes messages
            </nuxt-link>

            <nuxt-link to="/user/infos" :class="linkClasses">
              Mon compte
            </nuxt-link>

            <button :class="linkClasses" @click="handleLogout">
              Se déconnecter
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import MenuHeader from '@/mixins/menu-header'

export default {
  mixins: [MenuHeader],
}
</script>
