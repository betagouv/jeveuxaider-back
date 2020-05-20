<template>
  <footer class="bg-white text-black text-center lg:text-left">
    <div class="container mx-auto px-4 py-8">
      <div class="flex flex-col lg:flex-row items-center lg:items-start">
        <div class="max-w-xs">
          <div class="mb-8">
            <router-link to="/">
              <div class="border-b border-gray-200 pb-6 mb-6">
                <img
                  class="mx-auto lg:mx-0"
                  src="/images/logo-reserve-civique.png"
                  alt=""
                  style="max-width: 220px; height: auto;"
                />
              </div>
              <div>
                <img
                  class="mx-auto lg:mx-0"
                  src="/images/logo-footer.png"
                  alt=""
                  style="height: 200px;"
                />
              </div>
            </router-link>
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
            <!-- <router-link class="block p-3 hover:underline" to="/a-propos"
              >A propos</router-link
            > -->
            <router-link
              class="block p-3 hover:underline"
              to="/regles-de-securite"
            >
              Règles de sécurité
            </router-link>
            <router-link class="block p-3 hover:underline" to="/faq">
              FAQ
            </router-link>
            <router-link
              class="block p-3 hover:underline"
              to="/mentions-legales"
            >
              Mentions légales
            </router-link>
            <a
              class="block p-3 hover:underline"
              href="https://www.gouvernement.fr/info-coronavirus"
              target="_blank"
              >Info Coronavirus</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://mobilisationemploi.gouv.fr/#/accueil"
              target="_blank"
              >Mobilisation pour l'emploi</a
            >
          </div>
          <div class="lg:max-w-1/4 text-black">
            <a
              class="block p-3 hover:underline"
              href="https://www.elysee.fr/"
              target="_blank"
              >elysee.fr</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://service-public.fr"
              target="_blank"
              >service-public.fr</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://legifrance.gouv.fr"
              target="_blank"
              >legifrance.gouv.fr</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://data.gouv.fr"
              target="_blank"
              >data.gouv.fr</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://www.france.fr"
              target="_blank"
              >france.fr</a
            >
          </div>
          <div class="lg:max-w-1/2 text-black">
            <a
              class="block p-3 hover:underline"
              href="https://www.service-civique.gouv.fr/"
              target="_blank"
              >Service Civique</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://www.snu.gouv.fr/"
              target="_blank"
              >Service National Universel</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://www.corpseuropeensolidarite.fr/"
              target="_blank"
              >Corps Européen de solidarité</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://www.service-public.fr/particuliers/vosdroits/F11444"
              target="_blank"
              >Volontariat de solidarité internationale</a
            >
            <a
              class="block p-3 hover:underline"
              href="https://garde-nationale.gouv.fr/"
              target="_blank"
              >Garde Nationale</a
            >
          </div>
        </div>
        <div class="mt-8 lg:mt-3">
          <div class="mb-8" style="font-size: 13px;">
            Suivez la Réserve Civique
          </div>
          <div
            class="flex -m-2 overflow-hidden justify-center lg:justify-start"
          >
            <a
              class="m-2 flex-shrink-0 flex items-center justify-center border border-black rounded-full w-10 h-10 transition opacity-75 hover:opacity-100"
              href="https://twitter.com/ReserveCivique"
              target="_blank"
            >
              <img src="/images/twitter_black.svg" alt="Twitter" />
            </a>
            <a
              class="m-2 flex-shrink-0 flex items-center justify-center border border-black rounded-full w-10 h-10 transition opacity-75 hover:opacity-100"
              href="https://www.facebook.com/reservecivique/"
              target="_blank"
            >
              <img src="/images/facebook_black.svg" alt="Facebook" />
            </a>
          </div>
        </div>
      </div>
    </div>
    <hr class="border-grey-200 mt-4" />
    <div class="container mx-auto px-4 py-6">
      <div style="font-size: 10px; letter-spacing: 0.05em;">
        © RESERVE-CIVIQUE.GOUV.FR, 2020
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
