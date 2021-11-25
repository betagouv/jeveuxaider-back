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
          <nuxt-link :class="linkClasses" to="/inscription">
            Inscription
          </nuxt-link>

          <nuxt-link :class="linkClasses" to="/login"> Connexion </nuxt-link>
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
