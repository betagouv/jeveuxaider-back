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
                  width="222px"
                  height="200px"
                />
              </div>
            </nuxt-link>
          </div>
          <div
            class="text-black"
            style="
              max-width: 260px;
              font-size: 14px;
              line-height: 1.6;
              letter-spacing: 0.05em;
            "
          >
            Trouvez une mission d'intérêt général auprès d'un organisme public
            ou associatif et faites vivre les valeurs de la République.
          </div>
        </div>
        <div
          id="menu-footer"
          class="lg:flex lg:flex-1 lg:justify-around mt-8 lg:mt-0"
        >
          <div class="lg:max-w-1/4 text-black">
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
            <a
              class="block p-3 hover:underline"
              href="https://www.gouvernement.fr/info-coronavirus"
              target="_blank"
              rel="noopener"
              >Info Coronavirus</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://www.jeveuxaider.gouv.fr/engagement/partenaires/"
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
          </div>
          <div class="lg:max-w-1/4 text-black">
            <a
              class="block p-3 hover:underline"
              href="https://www.elysee.fr/"
              target="_blank"
              rel="noopener"
              >elysee.fr</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://service-public.fr"
              target="_blank"
              rel="noopener"
              >service-public.fr</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://legifrance.gouv.fr"
              target="_blank"
              rel="noopener"
              >legifrance.gouv.fr</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://data.gouv.fr"
              target="_blank"
              rel="noopener"
              >data.gouv.fr</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://www.france.fr"
              target="_blank"
              rel="noopener"
              >france.fr</a
            >
          </div>
          <div class="lg:max-w-1/2 text-black">
            <a
              class="block p-3 hover:underline"
              href="https://www.service-civique.gouv.fr/"
              target="_blank"
              rel="noopener"
              >Service Civique</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://www.snu.gouv.fr/"
              target="_blank"
              rel="noopener"
              >Service National Universel</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://www.corpseuropeensolidarite.fr/"
              target="_blank"
              rel="noopener"
              >Corps Européen de solidarité</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://www.service-public.fr/particuliers/vosdroits/F11444"
              target="_blank"
              rel="noopener"
              >Volontariat de solidarité internationale</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://garde-nationale.gouv.fr/"
              target="_blank"
              rel="noopener"
              >Garde Nationale</a
            >
          </div>
        </div>
      </div>
    </div>
    <hr class="border-grey-200 mt-4" />
    <div class="container mx-auto px-4 py-6">
      <div style="font-size: 10px; letter-spacing: 0.05em">
        © JEVEUXAIDER.GOUV.FR, 2021
      </div>
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
