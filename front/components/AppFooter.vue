<template>
  <footer class="bg-white text-black text-center lg:text-left">
    <div class="container mx-auto px-4 py-8">
      <div class="flex flex-col lg:flex-row items-center lg:items-start">
        <div class="max-w-xs">
          <div class="mb-8">
            <nuxt-link to="/">
              <div>
                <img
                  class="mx-auto lg:mx-0"
                  src="@/assets/images/republique-francaise-logo.svg"
                  alt="République Française"
                  width="222"
                  height="200"
                />
              </div>
            </nuxt-link>
          </div>
          <div class="text-black desc">
            Trouvez une mission d'intérêt général auprès d'un organisme public
            ou associatif et faites vivre les valeurs de la République.
          </div>
        </div>
        <div
          id="menu-footer"
          class="lg:flex lg:flex-1 lg:justify-around mt-8 lg:mt-0"
        >
          <div
            class="lg:max-w-1/4 text-black flex flex-col items-center lg:items-start"
          >
            <a
              class="block p-3 hover:underline"
              href="https://reserve-civique.crisp.help/fr/"
              target="_blank"
              rel="noopener"
            >
              Centre d'aide
            </a>
            <nuxt-link
              class="block p-3 hover:underline"
              to="/regles-de-securite"
            >
              Règles de sécurité
            </nuxt-link>
            <nuxt-link
              class="block p-3 hover:underline"
              to="/charte-reserve-civique"
            >
              Charte
            </nuxt-link>
            <nuxt-link class="block p-3 hover:underline" to="/mentions-legales">
              Mentions légales
            </nuxt-link>
            <nuxt-link
              class="block p-3 hover:underline"
              to="/politique-de-confidentialite"
            >
              Politique de confidentialité
            </nuxt-link>
            <a
              class="block p-3 hover:underline"
              href="javascript:openAxeptioCookies()"
            >
              Gestion des cookies
            </a>
            <a
              class="p-3 hover:underline inline-flex items-center justify-center lg:justify-start"
              href="https://www.gouvernement.fr/info-coronavirus"
              target="_blank"
              rel="noopener"
              >Info Coronavirus
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 ml-2 flex-none"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                /></svg
            ></a>
            <a
              class="block p-3 hover:underline"
              href="https://www.jeveuxaider.gouv.fr/engagement/organisations/"
              target="_blank"
              rel="noopener"
              >Partenaires</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://www.jeveuxaider.gouv.fr/engagement/presse/"
              target="_blank"
              rel="noopener"
              >Presse</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://jeveuxaider.gouv.fr/engagement/communication/"
              target="_blank"
              rel="noopener"
              >Kit de communication</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://jeveuxaider.gouv.fr/statistiques"
              target="_blank"
              >Statistiques</a
            >
          </div>
          <div
            class="lg:max-w-1/4 text-black flex flex-col items-center lg:items-start"
          >
            <a
              class="inline-flex items-center justify-center lg:justify-start p-3 hover:underline"
              href="https://www.elysee.fr/"
              target="_blank"
              rel="noopener"
              >elysee.fr<svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 ml-2 flex-none"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                /></svg
            ></a>
            <a
              class="inline-flex items-center justify-center lg:justify-start p-3 hover:underline"
              href="https://service-public.fr"
              target="_blank"
              rel="noopener"
              >service-public.fr<svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 ml-2 flex-none"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                /></svg
            ></a>
            <a
              class="inline-flex items-center justify-center lg:justify-start p-3 hover:underline"
              href="https://legifrance.gouv.fr"
              target="_blank"
              rel="noopener"
              >legifrance.gouv.fr<svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 ml-2 flex-none"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                /></svg
            ></a>
            <a
              class="inline-flex items-center justify-center lg:justify-start p-3 hover:underline"
              href="https://data.gouv.fr"
              target="_blank"
              rel="noopener"
              >data.gouv.fr<svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 ml-2 flex-none"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                /></svg
            ></a>
            <a
              class="inline-flex items-center justify-center lg:justify-start p-3 hover:underline"
              href="https://www.france.fr"
              target="_blank"
              rel="noopener"
              >france.fr<svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 ml-2 flex-none"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                /></svg
            ></a>
          </div>
          <div
            class="lg:max-w-1/2 text-black flex flex-col items-center lg:items-start"
          >
            <a
              class="inline-flex items-center justify-center lg:justify-start p-3 hover:underline"
              href="https://www.service-civique.gouv.fr/"
              target="_blank"
              rel="noopener"
              >Service Civique<svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 ml-2 flex-none"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                /></svg
            ></a>
            <a
              class="inline-flex items-center justify-center lg:justify-start p-3 hover:underline"
              href="https://www.snu.gouv.fr/"
              target="_blank"
              rel="noopener"
              >Service National Universel<svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 ml-2 flex-none"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                /></svg
            ></a>
            <a
              class="inline-flex items-center justify-center lg:justify-start p-3 hover:underline"
              href="https://www.corpseuropeensolidarite.fr/"
              target="_blank"
              rel="noopener"
              >Corps Européen de solidarité<svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 ml-2 flex-none"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                /></svg
            ></a>
            <a
              class="inline-flex items-center justify-center lg:justify-start p-3 hover:underline"
              href="https://www.service-public.fr/particuliers/vosdroits/F11444"
              target="_blank"
              rel="noopener"
              >Volontariat de solidarité internationale<svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 ml-2 flex-none"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                /></svg
            ></a>
            <a
              class="inline-flex items-center justify-center lg:justify-start p-3 hover:underline"
              href="https://garde-nationale.gouv.fr/"
              target="_blank"
              rel="noopener"
              >Garde Nationale<svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 ml-2 flex-none"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                /></svg
            ></a>
          </div>
        </div>
      </div>
    </div>
    <hr class="border-grey-200 mt-4" />
    <div class="container mx-auto px-4 py-6 flex">
      <ul
        class="mx-auto lg:mx-0 sm:flex items-center text-xs sm:divide-x sm:divide-gray-400"
      >
        <li class="sm:pr-3">© JEVEUXAIDER.GOUV.FR, 2021</li>
        <li class="sm:pl-3">
          <nuxt-link to="/accessibilite"
            >Accessibilité : non conforme</nuxt-link
          >
        </li>
      </ul>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'AppFooter',
  methods: {
    isCurrentPath(path) {
      return window.location.pathname === path
    },
  },
}
</script>

<style lang="sass" scoped>
.desc.text-black
  max-width: 260px
  font-size: 14px
  line-height: 1.6
  letter-spacing: 0.05em
</style>
