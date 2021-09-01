<template>
  <div class="relative text-center z-10">
    <h2
      class="text-3xl md:text-5xl text-white font-light leading-tight tracking-tight"
    >
      Un petit clic pour vous, <br />
      <span class="font-bold">un grand geste pour la solidarité.</span>
    </h2>
    <div class="text-xl md:text-3xl text-white mt-2 tracking-tight">
      Que peut-on faire pour vous ?
    </div>
    <div
      class="flex flex-col space-y-4 items-center justify-center mt-4 mb-12 md:flex-row md:space-x-6 md:space-y-0"
    >
      <nuxt-link to="/register/volontaire">
        <div
          class="bg-white w-72 flex-col items-center justify-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150"
        >
          <p class="text-4xl mb-0">💪</p>
          <p class="text-2xl leading-tight">
            Je veux devenir<br /><span class="font-bold">bénévole</span>
          </p>
          <p class="text-gray-500 text-sm leading-tight">
            Citoyens français et résidents réguliers (mineurs inclus)
          </p>
        </div>
      </nuxt-link>
      <div class="text-white text-lg">ou</div>
      <nuxt-link to="/inscription/organisation">
        <div
          class="bg-white w-72 flex-col items-center justify-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150"
        >
          <p class="text-4xl mb-0">📢</p>
          <p class="text-2xl leading-tight">
            Je veux publier<br /><span class="font-bold">des missions</span>
          </p>
          <p class="text-gray-500 text-sm leading-tight">
            Organisations publiques, collectivités et associations
          </p>
        </div>
      </nuxt-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Login',
  layout: 'header-only',
  middleware: 'guest',
  head() {
    return {
      title:
        'Devenez bénévole avec JeVeuxAider.gouv.fr, la plateforme publique du bénévolat par la Réserve Civique',
      meta: [
        {
          hid: 'description',
          name: 'description',
          content:
            'Créez votre page dédiée et centralisez les missions de vos associations et organisations publiques afin de promouvoir le bénévolat de proximité.',
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: '/images/share-image.jpg',
        },
      ],
    }
  },
}
</script>
