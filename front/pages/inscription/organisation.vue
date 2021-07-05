<template>
  <div class="relative text-center z-10">
    <h2 class="text-3xl md:text-5xl text-white leading-tight tracking-tight font-bold">Excellent choix !</h2>
    <div class="text-xl md:text-3xl text-white mt-7 tracking-tight">Vous êtes...</div>
    <div class="flex flex-col space-y-4 items-center justify-center mt-10 mb-12 md:flex-row md:space-x-6 md:space-y-0">
      <div class="bg-white w-72 h-64 flex-col items-center justify-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150">
        <p class="text-4xl mb-0">💪</p>
        <p class="text-2xl leading-tight">Une<br /><span class="font-bold">association</span></p>
        <p class="text-gray-500 text-sm leading-tight">
          Trouver des bénévoles<br />
          pour vos missions
        </p>
      </div>
      <div class="bg-white w-72 h-64 flex-col items-center justify-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150">
        <p class="text-4xl mb-0">🏫️</p>
        <p class="text-2xl leading-tight">Une <span class="font-bold">collectivité territoriale</span></p>
        <p class="text-gray-500 text-sm leading-tight">
          Mairies, départements,<br />
          régions et EPCI
        </p>
      </div>
      <div class="bg-white w-72 h-64 flex-col items-center justify-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150">
        <p class="text-4xl mb-0">🚀</p>
        <p class="text-2xl leading-tight">
          Une <span class="font-bold">tête de<br />réseau</span>
        </p>
        <p class="text-gray-500 text-sm leading-tight">Gérer vos différentes antennes, <br />délégations, associations locales ...</p>
      </div>
      <div class="bg-white w-72 h-64 flex-col items-center justify-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150">
        <p class="text-4xl mb-0">🏢</p>
        <p class="text-2xl leading-tight">Autre organisation <br /><span class="font-bold">publique</span></p>
        <p class="text-gray-500 text-sm leading-tight">CCAS, Ehpad public, <br />services de l’Etat ...</p>
      </div>
      <div class="bg-white w-72 h-64 flex-col items-center justify-center px-4 py-10 rounded-xl transform cursor-pointer hover:scale-105 duration-150">
        <p class="text-4xl mb-0">🏩</p>
        <p class="text-2xl leading-tight"><span class="font-bold">Organisation privée</span><br />à but non lucratif</p>
        <p class="text-gray-500 text-sm leading-tight">Établissement de santé privé d'intérêt collectif, Ehpad privé, fondation, ESUS</p>
      </div>
      <div class="bg-white w-72 h-64 px-4 py-10 flex-col items-center justify-center rounded-xl transform cursor-pointer hover:scale-105 duration-150">
        <p class="text-4xl mb-0">🤔</p>
        <p class="text-2xl leading-tight">
          Vous êtes<br />
          <span class="font-bold">perdu ?</span>
        </p>
        <p class="text-gray-500 text-sm leading-tight">
          Notre équipe se fera une joie<br />
          de vous guider :)
        </p>
      </div>
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
      title: 'Devenez bénévole avec JeVeuxAider.gouv.fr, la plateforme publique du bénévolat par la Réserve Civique',
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: 'Créez votre page dédiée et centralisez les missions de vos associations et organisations publiques afin de promouvoir le bénévolat de proximité.',
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
