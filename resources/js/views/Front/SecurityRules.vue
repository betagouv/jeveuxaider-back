<template>
  <div>
    <AppHeader />

    <div class="bg-blue-900 pb-32">
      <div class="container mx-auto px-4">
        <div class="pt-10">
          <h1 class="text-3xl font-bold text-white">Règles de sécurité</h1>
        </div>
      </div>
    </div>

    <div class="-mt-32">
      <div class="container mx-auto px-4 my-12">
        <div class="bg-white rounded-lg shadow px-4 py-8 sm:p-8 lg:p-12 xl:p-16">
          <h2
            class="text-3xl leading-tight font-extrabold text-gray-900"
          >Des gestes simples pour préserver votre santé et celle des autres</h2>
          <div class="mt-20">
            <ul class="md:grid md:grid-cols-2 md:col-gap-8 md:row-gap-10">
              <li>
                <div class="flex">
                  <div class="flex-shrink-0">
                    <div
                      class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
                    >1</div>
                  </div>
                  <div class="ml-4">
                    <h5 class="text-lg text-gray-900">
                      En toutes circonstances,
                      <b>je fais en sorte de garder ma distance de sécurité</b>
                      d’au moins un mètre avec les bénéficiaires et les autres
                      bénévoles.
                    </h5>
                  </div>
                </div>
              </li>
              <li class="mt-10 md:mt-0">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <div
                      class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
                    >2</div>
                  </div>
                  <div class="ml-4">
                    <h5 class="text-lg text-gray-900">
                      En toutes circonstances,
                      <b>j’applique les gestes barrières</b> pour me protéger et
                      protéger les bénéficiaires et les autres bénévoles.
                    </h5>
                  </div>
                </div>
              </li>
              <li class="mt-10 md:mt-0">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <div
                      class="flex items-center justify-center h-12 w-12 font-semibold rounded-md bg-blue-800 text-white"
                    >3</div>
                  </div>
                  <div class="ml-4">
                    <h5 class="text-lg text-gray-900">
                      <b>Je suis un ambassadeur des règles de sécurité</b> et des
                      gestes barrière que j’explique et promeus auprès des
                      bénéficiaires et des autres bénévoles.
                    </h5>
                  </div>
                </div>
              </li>
              <li class="mt-10 md:mt-0">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <div
                      class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
                    >4</div>
                  </div>
                  <div class="ml-4">
                    <h5 class="text-lg text-gray-900">
                      Pour accomplir ma mission bénévole,
                      <b>je n’utilise pas les transports en commun</b> afin de
                      limiter les risques de contamination.
                    </h5>
                  </div>
                </div>
              </li>
              <li class="mt-10 md:mt-0">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <div
                      class="flex items-center justify-center h-12 w-12 font-semibold rounded-md bg-blue-800 text-white"
                    >5</div>
                  </div>
                  <div class="ml-4">
                    <h5 class="text-lg text-gray-900">
                      <b>Au moindres doute d’infection</b>, sans attendre,
                      j’informe l’association et je reste chez moi.
                    </h5>
                  </div>
                </div>
              </li>
              <li class="mt-10 md:mt-0">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <div
                      class="flex items-center justify-center font-semibold h-12 w-12 rounded-md bg-blue-800 text-white"
                    >6</div>
                  </div>
                  <div class="ml-4">
                    <h5 class="text-lg text-gray-900">
                      <b>
                        Âgé de 70 ans ou plus ou porteur d’une maladie
                        chronique</b>, je ne m’engage que dans des missions à distance, depuis
                      mon domicile, pour protéger au maximum ma santé et celle des
                      autres.
                    </h5>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <AppFooter />
  </div>
</template>

<script>
export default {
  name: "FrontMissions"
};
</script>
