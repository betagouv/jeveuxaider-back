<template>
  <div class="message--details">
    <section>
      <div class="text-sm text-gray-500 mb-4 font-light">
        {{ participation.mission.city }}
        <span v-if="participation.mission.start_date">
          ·
          {{ participation.mission.start_date | formatCustom('D MMM') }}
        </span>
        <template
          v-if="
            participation.mission.start_date &&
            participation.mission.start_date.substring(0, 10) !=
              participation.mission.end_date.substring(0, 10)
          "
        >
          –
          {{ participation.mission.end_date | formatCustom('D MMM YYYY') }}
        </template>
        <template v-else-if="participation.mission.start_date">
          {{ participation.mission.start_date | formatCustom('YYYY') }}
        </template>
      </div>
      <h2 class="text-xl leading-8 font-bold text-gray-900">
        Votre mission chez {{ participation.mission.structure.name }}
      </h2>
    </section>

    <section>
      <div v-if="participation.mission.domaine" class="mb-4">
        <img
          class="bg-primary rounded-md p-3"
          :src="participation.mission.domaine.image"
          width="56px"
        />
      </div>
      <h3 class="text-xl leading-8 font-bold text-gray-900 mb-4">
        {{ participation.mission.name }}
      </h3>
      <div class="font-light">
        Votre participation est
        <span class="font-bold text-primary">{{
          participation.state | lowercase
        }}</span>
      </div>
      <participation-dropdown-state
        v-if="
          $store.getters.contextRole == 'responsable' ||
          $store.getters.contextRole == 'admin'
        "
        class="mt-3"
        :form="participation"
      />

      <hr class="my-6" />

      <div class="flex mb-6">
        <div
          v-if="participation.mission.start_date"
          class="w-1/2 border-r pr-4"
        >
          <div class="text-sm text-gray-500 mb-4 font-light">Début</div>
          <div class="font-light">
            {{ participation.mission.start_date | formatCustom('ddd D MMM') }}
          </div>
          <div class="text-2xl">
            {{ participation.mission.start_date | formatCustom('HH[h]mm') }}
          </div>
        </div>
        <div v-if="participation.mission.end_date" class="w-1/2 ml-4">
          <div class="text-sm text-gray-500 mb-4 font-light">Fin</div>
          <div class="font-light">
            {{ participation.mission.end_date | formatCustom('ddd D MMM') }}
          </div>
          <div class="text-2xl">
            {{ participation.mission.end_date | formatCustom('HH[h]mm') }}
          </div>
        </div>
      </div>

      <div class="mb-6">
        <div class="text-sm text-gray-500 mb-4 font-light">Adresse</div>
        <div class="font-light">
          {{ participation.mission.address }}<br />
          {{ participation.mission.zip }} {{ participation.mission.city }}
        </div>
      </div>

      <div class="mb-6">
        <div class="-m-2">
          <div
            v-for="tag in [
              participation.mission.format,
              participation.mission.type,
            ]"
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
      <div class="font-light" v-html="participation.mission.objectif"></div>
    </section>

    <section>
      <h3 class="text-xl leading-8 font-bold text-gray-900 mb-4">
        Description de la mission
      </h3>
      <div class="font-light" v-html="participation.mission.description"></div>
    </section>

    <section>
      <h3 class="text-xl leading-8 font-bold text-gray-900 mb-4">
        Public bénéficiaire
      </h3>
      <ul>
        <li
          v-for="beneficiaryPublic in participation.mission
            .publics_beneficiaires"
          :key="beneficiaryPublic"
          class="flex items-start font-light"
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
          <p class="ml-3">
            {{ beneficiaryPublic }}
          </p>
        </li>
      </ul>
    </section>

    <section>
      <h3 class="text-xl leading-8 font-bold text-gray-900 mb-4">
        Commentaires de l'organisation
      </h3>
      <div class="font-light">{{ participation.mission.information }}</div>
    </section>
  </div>
</template>

<script>
import ParticipationDropdownState from '@/components/ParticipationDropdownState'

export default {
  name: 'ConversationDetail',
  components: {
    ParticipationDropdownState,
  },
  props: {
    participation: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {}
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
