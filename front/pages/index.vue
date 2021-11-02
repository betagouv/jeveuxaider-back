<template>
  <div>
    <!-- BANNER -->
    <HomeBanner />

    <!-- ORGANISATIONS -->
    <section class="relative overflow-hidden md:h-[500px] md:flex items-center">
      <img
        src="/images/deco_1.svg"
        alt="Décorations"
        class="deco--1 absolute hidden xl:block"
        data-not-lazy
      />

      <div class="md:container md:mx-auto xl:max-w-[1412px]">
        <div class="mx-auto max-w-[1170px]">
          <img
            srcset="
              /images/mosaique_orgas@2x.webp 2x,
              /images/mosaique_orgas@2x.png  2x
            "
            alt="Mosaïque organisations"
            class="sm:hidden object-cover object-left-bottom w-full h-[370px] translate-y-[-25px] mosaic"
            width="440"
            height="371"
          />

          <img
            srcset="
              /images/mosaique_orgas_desktop.webp,
              /images/mosaique_orgas_desktop@2x.webp 2x,
              /images/mosaique_orgas_desktop.png,
              /images/mosaique_orgas_desktop@2x.png  2x
            "
            alt="Mosaïque organisations"
            class="hidden sm:block object-cover object-left-bottom w-full h-[370px] md:h-[650px] md:translate-y-[-138px] md:absolute md:right-0 md:top-0 mosaic"
            width="790"
            height="670"
          />

          <div
            class="max-w-[440px] mx-auto md:ml-0 px-4 xl:px-0 text-center md:text-left"
          >
            <h2
              class="font-extrabold text-4xl lg:text-[50px] lg:leading-[52px] tracking-tighter mb-6"
            >
              Chacun pour tous
            </h2>
            <p class="text-[#696974] text-2xl mb-6">
              Plus de <strong>60 000 missions de bénévolat</strong> sont
              disponibles chez les petits et grands acteurs de l'engagement.
            </p>
            <a
              href="https://www.jeveuxaider.gouv.fr/engagement/organisations/"
              class="text-lg text-primary hover:underline"
              target="_blank"
            >
              Consulter toutes les organisations ›
            </a>
          </div>
        </div>
      </div>
    </section>

    <hr class="mt-12 pt-12 md:hidden border-[#CDCDCD]" />

    <!-- MISSIONS PRIORITAIRES -->
    <section
      v-if="prioritizedMissions.length > 0"
      class="overflow-hidden xl:mt-6 pb-16"
    >
      <div class="container mx-auto px-8 sm:px-4 xl:max-w-[1412px]">
        <div class="mx-auto max-w-[1170px]">
          <div class="flex justify-between items-baseline mb-6">
            <h2
              class="text-4xl lg:text-[40px] lg:leading-[42px] tracking-tight text-center md:text-left"
            >
              Les missions de bénévolat
              <strong class="font-extrabold">prioritaires</strong>
            </h2>
            <span
              class="hidden lg:block ml-4 text-[32px] xl:text-[40px] text-[#A7A7B0] font-light"
            >
              #{{ Date.now() | formatCustom('MMMM') }}
            </span>
          </div>

          <SlideshowMissions
            :missions="prioritizedMissions"
            more-link="/missions-benevolat?toggle[is_priority]=true"
          />
        </div>
      </div>
    </section>

    <!-- DOMAINES D'ACTION -->
    <section class="py-16 bg-white overflow-hidden">
      <div class="container mx-auto px-8 sm:px-4 xl:max-w-[1412px]">
        <div class="mx-auto max-w-[1170px]">
          <div class="flex justify-between items-baseline mb-6">
            <h2
              class="text-4xl lg:text-[40px] lg:leading-[42px] tracking-tight text-center md:text-left"
            >
              Trouvez votre
              <strong class="font-extrabold">domaine d'action</strong>
            </h2>
            <span
              class="hidden md:block ml-4 text-[32px] xl:text-[40px] text-[#A7A7B0] font-light"
            >
              #jeveuxaider
            </span>
          </div>

          <SlideshowDomaines />
        </div>
      </div>
    </section>

    <!-- ENGAGEZ-VOUS -->
    <section class="py-16 bg-jva-grayLight overflow-hidden">
      <div class="container mx-auto px-4 relative xl:max-w-[1412px]">
        <div class="mx-auto max-w-[1170px]">
          <h2
            class="text-4xl lg:text-[50px] lg:leading-[42px] tracking-tight text-center lg:text-left"
          >
            Engagez-vous
            <strong class="font-extrabold">près de chez vous</strong>
          </h2>

          <div
            class="mt-12 max-w-3xl lg:max-w-[500px] mx-auto lg:ml-0 flex flex-wrap gap-4 items-center justify-center lg:justify-start"
          >
            <nuxt-link
              v-for="(city, index) in hightlightedCities"
              :key="city.name"
              class="text-[#696974] leading-none truncate px-[18px] h-[40px] flex items-center rounded-full text-[13px] shadow-md font-extrabold tracking-wide uppercase bg-white transform transition will-change-transform hover:scale-110"
              :class="[
                {
                  'w-[40px] h-[40px] !p-0 flex items-center justify-center text-[26px] font-normal':
                    index == hightlightedCities.length - 1,
                },
              ]"
              :to="city.url"
            >
              <template v-if="index != hightlightedCities.length - 1">
                {{ city.name }}
              </template>

              <img
                v-else
                src="/images/more.svg"
                alt="Voir plus de villes"
                width="13"
                height="13"
                data-not-lazy
              />
            </nuxt-link>
          </div>
        </div>

        <div
          class="bg-white mt-16 text-center md:text-left rounded-[10px] overflow-hidden lg:max-w-[660px] xl:ml-[56px]"
          style="box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05)"
        >
          <div class="flex flex-col items-center md:flex-row md:items-end">
            <div class="px-6 pt-8 md:pt-0 md:pb-12 md:pl-12 md:pr-0">
              <h2
                class="text-4xl lg:text-[40px] lg:leading-[42px] tracking-tight"
              >
                Ou <strong class="font-extrabold">à distance</strong>
              </h2>

              <div class="mt-4 text-xl text-[#696974]">
                Plus de 1 000 missions de bénévolat sont réalisables en
                autonomie
              </div>

              <nuxt-link
                class="rounded-full text-white bg-jva-green hover:scale-105 !outline-none focus:scale-105 transition px-8 py-3 transform will-change-transform shadow-xl font-bold inline-flex mt-8"
                to="/missions-benevolat?refinementList[type][0]=Mission à distance"
              >
                <span>Découvrir le télébénévolat</span>
              </nuxt-link>
            </div>

            <img
              src="/images/telebenevolat.svg"
              alt="Télébénévolat"
              class="-mt-16 md:mt-0 md:mr-[-97px]"
              width="379"
              height="292"
              data-not-lazy
            />
          </div>
        </div>

        <img
          src="/images/map_france_2.svg"
          alt="Carte de la France"
          class="hidden lg:block absolute top-0 right-0 mr-[-180px] xl:-mr-0 pr-4 h-full"
          width="607"
          height="624"
          style="filter: drop-shadow(8px 20px 16px rgba(0, 0, 0, 0.1))"
        />
      </div>
    </section>

    <!-- ACTUALITÉS -->
    <section v-if="articles.length > 0" class="py-16 bg-white overflow-hidden">
      <div class="container mx-auto px-8 sm:px-4 xl:max-w-[1412px]">
        <div class="mx-auto max-w-[1170px]">
          <div class="flex justify-between items-baseline mb-6">
            <h2
              class="text-4xl lg:text-[40px] lg:leading-[42px] tracking-tight text-center md:text-left"
            >
              Les actualités de
              <span>l'<strong class="font-extrabold">engagement</strong></span>
            </h2>
            <span
              class="hidden md:block ml-4 text-[32px] xl:text-[40px] text-[#A7A7B0] font-light"
            >
              #blog
            </span>
          </div>

          <SlideshowArticles :articles="articles" />
        </div>
      </div>
    </section>

    <!-- TÉMOIGNAGES -->
    <section class="py-16 bg-jva-grayLight overflow-hidden relative">
      <img
        src="/images/deco_2.svg"
        alt="Décorations"
        class="deco--2 absolute hidden lg:block"
        data-not-lazy
      />

      <img
        src="/images/deco_3.svg"
        alt="Décorations"
        class="deco--3 absolute hidden lg:block"
        data-not-lazy
      />

      <div class="container mx-auto px-4 xl:max-w-[1412px]">
        <h2
          class="text-4xl lg:text-[40px] lg:leading-[42px] tracking-tight text-center text-[#AFAFAE]"
        >
          Paroles de
          <strong class="font-extrabold">bénévoles</strong>
        </h2>

        <SlideshowTestimonies class="mt-12" />
      </div>
    </section>

    <!-- NEWSLETTER & GROUPE FACEBOOK -->
    <section>
      <div class="container mx-auto px-4 xl:max-w-[1412px]">
        <div class="grid gap-5 md:grid-cols-2">
          <!-- NEWSLETTER -->
          <div
            class="px-6 pt-10 pb-12 lg:px-8 lg:py-12 xl:px-16 xl:py-12 bg-[#D0E2FF] rounded-[10px] shadow-xl relative overflow-hidden"
          >
            <div class="flex flex-col text-center md:text-left">
              <h2
                class="text-4xl lg:text-[40px] lg:leading-[42px] tracking-tight mb-6 max-w-[442px]"
              >
                <span>Un</span>
                <strong class="font-extrabold">e-mail par mois</strong>
                <span>pour plus d'engagement</span>
              </h2>

              <div id="newsletter-headline" class="text-[#696974] text-xl mb-7">
                Chaque mois, nous vous proposons de nouvelles missions de
                bénévolat à distance ou près de chez vous.
              </div>

              <form
                v-if="!newsletterForm.isSuccess"
                aria-labelledby="newsletter-headline"
                class="relative flex flex-col lg:flex-row lg:items-start"
              >
                <input
                  v-model="newsletterForm.email"
                  aria-label="Email address"
                  type="email"
                  required
                  class="w-full px-5 py-3 appearance-none rounded-full text-gray-900 bg-white placeholder-gray-500 focus:placeholder-gray-400 transition mb-4 lg:mb-0 lg:mr-4 !outline-none focus-visible:ring"
                  placeholder="Renseignez votre e-mail"
                />

                <button
                  class="w-full lg:w-auto flex items-center justify-center px-12 py-3 font-bold rounded-full text-white bg-primary hover:scale-105 transform transition"
                  @click.prevent="handleSubmitNewsletter()"
                >
                  <img
                    src="/images/envelope.svg"
                    alt="Enveloppe"
                    class="mr-2"
                    width="16"
                    height="14"
                    data-not-lazy
                  />
                  <span>S'inscrire</span>
                </button>

                <div
                  v-if="newsletterForm.error"
                  class="text-red-600 absolute w-full text-center md:text-left pt-2 bottom-0 translate-y-full"
                >
                  {{ newsletterForm.error }}
                </div>
              </form>

              <div
                v-else
                class="text-jva-green text-xl text-center md:text-left"
              >
                Merci&nbsp;! Vous êtes désormais inscrit(e) à notre
                newsletter&nbsp;😉
              </div>
            </div>

            <img
              src="/images/newsletter.svg"
              alt="Newsletter"
              class="hidden md:flex absolute top-[58px] lg:top-0 right-0 transform translate-x-[101px] xl:translate-x-[40px]"
              width="200"
              height="153"
            />
          </div>

          <!-- GROUPE FACEBOOK -->
          <div
            class="px-6 pt-10 pb-12 lg:px-8 lg:py-12 xl:px-16 xl:py-12 bg-white rounded-[10px] shadow-xl relative overflow-hidden"
          >
            <div
              class="flex flex-col items-start text-center md:text-left h-full relative z-10"
            >
              <h2
                class="text-4xl lg:text-[40px] lg:leading-[42px] tracking-tight mb-6 max-w-[442px]"
              >
                <span>Échangez avec la</span>
                <strong class="font-extrabold">communauté</strong>
              </h2>

              <div
                id="facebook-headline"
                class="text-[#696974] text-xl mb-7 lg:max-w-[256px] xl:max-w-[392px]"
              >
                Posez toutes vos questions aux bénévoles et à l'équipe dans le
                groupe Facebook
              </div>

              <a
                href="https://www.facebook.com/jeveuxaider.gouv.fr"
                target="_blank"
                rel="noopener"
                class="w-full lg:w-auto flex items-center justify-center px-12 py-3 font-bold rounded-full text-white bg-primary hover:scale-105 transform transition mt-auto relative z-10"
                aria-labelledby="facebook-headline"
              >
                <img
                  src="/images/facebook_alt.svg"
                  alt="Facebook"
                  class="mr-2"
                  width="20"
                  height="20"
                  data-not-lazy
                />
                <span>Rejoindre le groupe</span>
              </a>
            </div>

            <img
              src="/images/group_facebook.svg"
              alt="Groupe Facebook"
              class="hidden md:flex absolute bottom-0 right-0 transform translate-y-[70px] lg:translate-y-0"
              width="306"
              height="331"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- ACTEURS DE L'ENGAGEMENT -->
    <section class="py-16">
      <div class="container mx-auto px-4 xl:max-w-[1412px]">
        <h2
          class="text-4xl lg:text-[40px] lg:leading-[42px] tracking-tight text-center text-[#AFAFAE] mb-16"
        >
          Acteurs de l'engagement,
          <strong class="font-extrabold">rejoingnez le mouvement&nbsp;!</strong>
        </h2>

        <div class="grid gap-5 md:grid-cols-2">
          <!-- ASSOCIATIONS & ORGANISATIONS PUBLIQUES -->
          <div
            class="px-6 pt-10 pb-12 lg:px-8 lg:py-12 xl:px-16 xl:py-12 bg-white rounded-[10px] shadow-xl relative overflow-hidden"
          >
            <div
              class="flex flex-col items-start text-center md:text-left h-full"
            >
              <div
                class="flex-none w-full font-bold text-primary tracking-wide leading-[22px] mb-6 truncate"
              >
                ASSOCIATIONS ET ORGANISATIONS PUBLIQUES
              </div>
              <h2
                class="text-4xl lg:text-[40px] lg:leading-[42px] tracking-tight mb-8"
              >
                <span>Facilitez-vous le</span>
                <strong class="font-extrabold">
                  recrutement de vos bénévoles
                </strong>
              </h2>
              <img
                src="/images/associations_organisations_publiques.svg"
                alt="Associations & organisations publiques"
                width="311"
                height="219"
                class="mx-auto mb-8 md:h-full md:max-h-[170px] lg:max-h-[270px]"
              />

              <ul class="text-left space-y-3 mb-8">
                <li
                  v-for="(goal, index) in goals.associations"
                  :key="index"
                  class="flex space-x-4 items-start"
                >
                  <img
                    src="/images/puce_li_check_blue.svg"
                    alt="Check"
                    class="flex-none mt-1"
                    data-not-lazy
                    width="19px"
                    height="19px"
                  />
                  <span class="text-lg xl:text-xl text-[#696974]">
                    {{ goal }}
                  </span>
                </li>
              </ul>

              <nuxt-link
                to="/inscription/organisation"
                class="w-full lg:w-auto flex items-center justify-center px-12 py-3 font-bold rounded-full text-white bg-primary hover:scale-105 transform transition mt-auto lg:mx-auto"
              >
                Inscrire mon organisation
              </nuxt-link>
            </div>
          </div>

          <!-- COLLECTIVITÉS & TERRITOIRES -->
          <div
            class="px-6 pt-10 pb-12 lg:px-8 lg:py-12 xl:px-16 xl:py-12 bg-jva-green rounded-[10px] shadow-xl relative overflow-hidden"
          >
            <div
              class="flex flex-col items-start text-center md:text-left h-full"
            >
              <div
                class="flex-none w-full font-bold text-white tracking-wide leading-[22px] mb-6 truncate"
              >
                COLLECTIVITÉS ET TERRITOIRES
              </div>
              <h2
                class="text-white justify-end text-4xl lg:text-[40px] lg:leading-[42px] tracking-tight mb-8"
              >
                <span>Encouragez</span>
                <strong class="font-extrabold"> l'engagement local </strong>
                <span>de vos citoyens</span>
              </h2>
              <img
                src="/images/collectivites_territoires.svg"
                alt="Collectivités et territoires"
                width="311"
                height="299"
                class="mx-auto mb-8 md:h-full md:max-h-[170px] lg:max-h-[270px]"
              />

              <ul class="text-left space-y-3 mb-8">
                <li
                  v-for="(goal, index) in goals.collectivites"
                  :key="index"
                  class="flex space-x-4 items-start"
                >
                  <img
                    src="/images/puce_li_check_white.svg"
                    alt="Check"
                    class="flex-none mt-1"
                    data-not-lazy
                    width="19px"
                    height="19px"
                  />
                  <span class="text-lg xl:text-xl text-white">
                    {{ goal }}
                  </span>
                </li>
              </ul>

              <nuxt-link
                to="/inscription/organisation?orga_type=Collectivité"
                class="w-full lg:w-auto flex items-center justify-center px-12 py-3 font-bold rounded-full text-jva-green bg-white hover:scale-105 transform transition mt-auto lg:mx-auto"
              >
                Inscrire ma collectivité
              </nuxt-link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ -->
    <section class="py-16 bg-white">
      <div class="container mx-auto px-4 xl:max-w-[1412px]">
        <div class="mx-auto max-w-[1170px]">
          <div class="flex justify-between items-baseline mb-12">
            <h2
              class="text-4xl lg:text-[40px] lg:leading-[42px] tracking-tight text-center md:text-left"
            >
              Toutes les réponses à
              <strong class="font-extrabold">vos questions</strong>
            </h2>
            <span
              class="hidden md:block ml-4 text-[32px] xl:text-[40px] text-[#A7A7B0] font-light"
            >
              #faq
            </span>
          </div>

          <div class="grid lg:grid-cols-2 gap-x-8 gap-y-16 xl:gap-24">
            <div>
              <div class="font-bold text-primary tracking-wide uppercase mb-4">
                Bénévoles
              </div>

              <Accordion :items="faq.benevoles" />
            </div>
            <div>
              <div class="font-bold text-primary tracking-wide uppercase mb-4">
                Responsable d'organisation
              </div>

              <Accordion :items="faq.responsables" />
            </div>
          </div>

          <a
            href="https://reserve-civique.crisp.help/fr/"
            class="text-lg text-primary hover:underline inline-flex mt-8"
            target="_blank"
          >
            Toutes les réponses à vos questions ›
          </a>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  data() {
    return {
      prioritizedMissions: [],
      articles: [],
      hightlightedCities: [
        {
          name: 'Paris',
          url: '/villes/paris',
        },
        {
          name: 'Toulouse',
          url: '/villes/toulouse',
        },
        {
          name: 'Lyon',
          url: '/villes/lyon',
        },
        {
          name: 'Marseille',
          url: '/villes/marseille',
        },
        {
          name: 'Bordeaux',
          url: '/villes/bordeaux',
        },
        {
          name: 'Lille',
          url: '/villes/lille',
        },
        {
          name: 'Rennes',
          url: '/villes/rennes',
        },
        {
          name: 'Montpellier',
          url: '/villes/montpellier',
        },
        {
          name: 'Strasbourg',
          url: '/villes/strasbourg',
        },
        {
          name: 'Nice',
          url: '/villes/nice',
        },
        {
          name: 'Rouen',
          url: '/villes/rouen',
        },
        {
          name: 'Angers',
          url: '/villes/angers',
        },
        {
          name: '+',
          url: '/territoires',
        },
      ],
      newsletterForm: {
        email: '',
        error: '',
        isSuccess: false,
      },
      goals: {
        associations: [
          `Inscrivez votre organisation en quelques clics`,
          `Publiez vos missions de bénévolat`,
          `Trouvez des bénévoles motivés et disponibles`,
        ],
        collectivites: [
          `Créez une page dédiée à votre collectivité`,
          `Centralisez les missions des organisations locales`,
          `Encouragez et valorisez l'engagement de vos citoyens`,
        ],
      },
      faq: {
        benevoles: [
          {
            icon: '✊',
            title: `Qui peut s'inscrire sur JeVeuxAider.gouv.fr&nbsp;?`,
            content: `Bonjour et merci pour votre engagement&nbsp;!
              <br /><br />
              L'activité de réserviste répond à des conditions précises.
              <br /><br />
              Ainsi, tout citoyen ou résident régulier de plus de 16 ans souhaitant s’engager dans son temps libre dans le cadre des missions prioritaires pour la continuité de la Nation peut se rendre sur la plateforme JeVeuxAider.gouv.fr de la Réserve Civique et proposer son aide aux associations référencées.
              <br /><br />
              <a class="text-primary hover:underline" href="https://reserve-civique.crisp.help/fr/article/qui-peut-realiser-une-mission-sur-jeveuxaidergouvfr-i15md9/" target="_blank">En lire plus ›</a>`,
          },
          {
            icon: '🔍',
            title: `Comment trouver une mission&nbsp;?`,
            content: `Bonjour et merci pour votre engagement !
              <br /><br />
              La plateforme JeVeuxAider.gouv.fr est alimentée par les propositions de missions émanant d'organisations telles que des associations ou des collectivités. Vous pouvez facilement voir les missions disponibles.
              <br /><br />
              <a class="text-primary hover:underline" href="https://reserve-civique.crisp.help/fr/article/comment-trouver-une-missison-sur-jeveuxaidergouvfr-a1fbnf/" target="_blank">En lire plus ›</a>`,
          },
          {
            icon: '‍️‍️🖐',
            title: `Comment candidater à une mission&nbsp;?`,
            content: `Bonjour,
              <br /><br />
              Après avoir identifié la mission qui vous convenait et avoir bien pris connaissance du descriptif, vous pouvez candidater.
              <br /><br />
              <ul class="list-disc pl-4">
                <li>Cliquer sur le bouton vert situé à droite de l’écran «&nbsp;Je propose mon aide&nbsp;»</li>
              </ul>
              <br />
              <a class="text-primary hover:underline" href="https://reserve-civique.crisp.help/fr/article/comment-candidater-a-une-mission-sur-jeveuxaidergouvfr-1cdiioe/" target="_blank">En lire plus ›</a>`,
          },
        ],
        responsables: [
          {
            icon: '😁',
            title: `Comment s’inscrire sur JeVeuxAider.gouv.fr&nbsp;?`,
            content: `Bonjour et merci pour votre engagement !
              <br /><br />
              <ul class="list-decimal pl-6">
                <li class="pl-2">Une fois sur JeVeuxAider.gouv.fr, cliquez sur le bouton "&nbsp;Inscription&nbsp;" en haut à droite de l'écran.</li>
                <li class="pl-2">Sélectionnez "&nbsp;Je veux publier des missions&nbsp;" afin de créer un compte organisation.</li>
              </ul>
              <br />
              <a class="text-primary hover:underline" href="https://reserve-civique.crisp.help/fr/article/comment-inscrire-mon-organisation-sur-jeveuxaidergouvfr-rku0gq/" target="_blank">En lire plus ›</a>`,
          },
          {
            icon: '✊',
            title: `Qui peut s’inscrire sur JeVeuxAider.gouv.fr&nbsp;?`,
            content: `Bonjour,
              <br /><br />
              Peuvent déposer des missions relevant des 10 domaines d’action rappelés <a class="text-primary hover:underline" href="https://reserve-civique.crisp.help/fr/article/quelles-sont-les-missions-que-lon-peut-proposer-a8ahqy/" target="_blank">ici</a>:
              <br /><br />
              <ul class="list-disc pl-4">
                <li>les organismes sans but lucratif</li>
                <li>
                  les structures publiques telles que :
                  <ul class="list-disc pl-4">
                    <li>les établissements scolaires</li>
                    <li>les établissements publics de santé</li>
                    <li>les collectivités territoriales</li>
                    <li>les services de l’Etat</li>
                  </ul>
                </li>
              </ul>
              <br />
              <a class="text-primary hover:underline" href="https://reserve-civique.crisp.help/fr/article/quest-ce-quune-organisation-1m7331c/" target="_blank">En lire plus ›</a>`,
          },
          {
            icon: '‍️‍️📣',
            title: `Comment publier une mission&nbsp;?`,
            content: `Bonjour,
              <br /><br />
              Une fois votre organisation créée sur JeVeuxAider.gouv.fr, il vous est possible de mettre en ligne des missions.
              <br /><br />
              <ul class="list-decimal pl-6">
                <li class="pl-2">Cliquez sur votre prénom puis sur le nom de votre organisation en haut à droite de votre écran. Vous arrivez alors sur votre espace responsable.</li>
                <li class="pl-2">Cliquez sur l'onglet "Mes Missions" à gauche de votre écran.</li>
              </ul>
              <br />
              <a class="text-primary hover:underline" href="https://reserve-civique.crisp.help/fr/article/comment-publier-une-mission-sur-la-plateforme-16n8nk6/" target="_blank">En lire plus ›</a>`,
          },
        ],
      },
    }
  },
  async fetch() {
    const { data } = await this.$api.fetchPromotedToFrontPageMissions()
    this.prioritizedMissions = data

    const { data: articles } = await this.$axios.get(
      `${this.$config.blog.restApiUrl}/posts/?per_page=6`,
      {
        excludeContextRole: true,
      }
    )
    const articlesWithMedia = []
    for (const article of articles) {
      const url = article._links['wp:featuredmedia']
        ? article._links['wp:featuredmedia'][0].href
        : article._links['wp:attachment'][0].href
      const { data: media } = await this.$axios.get(url, {
        excludeContextRole: true,
      })

      if (!Array.isArray(media)) {
        articlesWithMedia.push({ ...article, media })
      } else {
        articlesWithMedia.push({ ...article, media: media[0] })
      }
    }
    this.articles = articlesWithMedia
  },
  head() {
    return {
      title:
        'Je Veux Aider | Devenez bénévole dans une association en quelques clics | La plateforme publique du bénévolat par la Réserve Civique',
      link: [
        {
          rel: 'canonical',
          href: 'https://www.jeveuxaider.gouv.fr/',
        },
        {
          rel: 'preload',
          as: 'image',
          href: '/images/banner_mobile.jpg',
          imagesrcset: `/images/banner_mobile.webp, /images/banner_mobile.jpg, /images/banner_mobile@2x.webp 2x, /images/banner_mobile@2x.jpg  2x`,
        },
      ],
      meta: [
        {
          hid: 'description',
          name: 'description',
          content:
            "Trouvez une mission de bénévolat dans une association, organisation publique ou une commune, partout en France, sur le terrain ou à distance. 50 000 places disponibles dans 10 domaines d'action : solidarité, insertion, éducation, environnement, santé, sport, culture ...",
        },
        {
          hid: 'og:image',
          property: 'og:image',
          content: '/images/share-image.jpg',
        },
        {
          hid: 'facebook-domain-verification',
          name: 'facebook-domain-verification',
          content: '8jnmyx2s1iopvryhthxappg6b3tryp',
        },
      ],
    }
  },
  methods: {
    handleSubmitNewsletter() {
      const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      if (re.test(String(this.newsletterForm.email).toLowerCase())) {
        this.$axios
          .post('/sendinblue/contact', { email: this.newsletterForm.email })
          .then(() => {
            this.newsletterForm.isSuccess = true
          })
          .catch((error) => {
            console.log(error)
            this.newsletterForm.error = 'Message erreur !'
          })
      } else {
        this.newsletterForm.error = "L'email renseigné n'est pas valide"
      }
      // setTimeout(() => (this.newsletterForm.error = ''), 5000)
    },
  },
}
</script>

<style lang="postcss" scoped>
.mosaic {
  @screen md {
    width: calc(50% - 10px);
  }

  @screen lg {
    width: calc(50% + 50px);
  }
}

.text-shadow {
  text-shadow: 0px 4px 14px rgb(0 0 0 / 25%), 0px 4px 30px rgb(0 0 0 / 85%);
}

.deco--1 {
  left: calc(50% - 727px);
  top: -6px;
}

.deco--2 {
  left: calc(50% - 728px);
  top: 50px;
}

.deco--3 {
  right: calc(50% - 728px);
  top: -12px;
}
</style>
