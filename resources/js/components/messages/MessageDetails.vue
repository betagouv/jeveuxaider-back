<template>
  <div class="message--details">
    <section>
      <div class="text-sm text-gray-500 mb-4 font-light">
        [VILLE] · 99 févr. – 99 févr
      </div>
      <h2 class="text-xl leading-8 font-bold text-gray-900">
        Votre mission chez [NOM STRUCTURE]
      </h2>
    </section>

    <section>
      <div class="mb-4">[IMAGE MISSION]</div>
      <h3 class="text-xl leading-8 font-bold text-gray-900 mb-4">
        [TITRE MISSION]
      </h3>
      <div>Votre participation est [STATUT PARTICIPATION]</div>

      <hr class="my-6" />

      <div class="flex mb-6">
        <div class="w-1/2 border-r">
          <div class="text-sm text-gray-500 mb-4 font-light">Début</div>
          <div>lun. 55 déc.</div>
          <div class="text-2xl">09:30</div>
        </div>
        <div class="w-1/2 ml-4">
          <div class="text-sm text-gray-500 mb-4 font-light">Fin</div>
          <div>dim. 77 déc.</div>
          <div class="text-2xl">99:99</div>
        </div>
      </div>

      <div class="mb-6">
        <div class="text-sm text-gray-500 mb-4 font-light">Adresse</div>
        <div>[ADRESSE MISSION]</div>
      </div>

      <div class="mb-6">
        <div class="-m-2">
          <div
            v-for="tag in tags"
            :key="tag"
            class="px-4 py-1 m-2 shadow-md inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-500"
          >
            {{ tag }}
          </div>
        </div>
      </div>
    </section>

    <section>
      <h3 class="text-xl leading-8 font-bold text-gray-900 mb-4">
        Objectif de la mission
      </h3>
      <div style="height: 300px">[OBJECTIF MISSION]</div>
    </section>

    <section>
      <h3 class="text-xl leading-8 font-bold text-gray-900 mb-4">
        Description de la mission
      </h3>
      <div style="height: 300px">[DESCRIPTION MISSION]</div>
    </section>

    <section>
      <h3 class="text-xl leading-8 font-bold text-gray-900 mb-4">
        Public bénéficiaire
      </h3>
      <ul>
        <li
          v-for="beneficiaryPublic in beneficiaryPublics"
          :key="beneficiaryPublic"
          class="flex items-start"
        >
          <div class="flex-shrink-0" style="margin-top: 2px">
            <svg
              class="h-5 w-5 text-green-400"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"
              />
            </svg>
          </div>
          <p class="ml-3 text-gray-700">
            {{ beneficiaryPublic }}
          </p>
        </li>
      </ul>
    </section>

    <section>
      <h3 class="text-xl leading-8 font-bold text-gray-900 mb-4">
        Commentaires de l'organisation
      </h3>
      <div style="height: 300px">[COMMENTAIRE MISSION]</div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'MessageDetails',
  data() {
    return {
      tags: ['Tag 1', 'Tag 2'],
      beneficiaryPublics: ['Public 1', 'Public 2'],
    }
  },
}
</script>

<style lang="sass" scoped>
.message--details
  background-color: #EBEBEB
section
  @apply bg-white p-6
  &:not(:last-of-type)
    @apply mb-1
hr
  @apply border-cool-gray-200
</style>
