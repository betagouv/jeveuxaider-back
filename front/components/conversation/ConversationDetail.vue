<template>
  <div class="message--details">
    <section>
      <h2 class="text-xl leading-8 font-bold text-gray-900">
        Mission proposée par {{ participation.mission.structure.name }}
      </h2>
      <div
        v-if="participation.mission.responsable"
        class="text-lg leading-8 font-semibold text-secondary"
      >
        Responsable : {{ participation.mission.responsable.full_name }}
      </div>
      <div class="flex flex-wrap mt-2">
        <router-link
          :to="`/missions/${participation.mission.id}/${participation.mission.slug}`"
          class="rounded-full border py-1 px-3 text-sm font-bold text-gray-900 hover:shadow-md"
          >Consulter la fiche</router-link
        >
      </div>
    </section>

    <template v-if="!isBenevole">
      <section>
        <h3 class="text-xl leading-8 font-bold text-gray-900 mb-4">
          À propos de {{ participation.profile.full_name }}
        </h3>

        <div v-if="participation.profile.email" class="mb-2 flex">
          <div class="text-gray-500 w-24 text-sm">Email</div>
          <div class="text-gray-900 flex-1">
            {{ participation.profile.email }}
          </div>
        </div>
        <div v-if="participation.profile.mobile" class="mb-2 flex">
          <div class="text-gray-500 w-24 text-sm">Portable</div>
          <div class="text-gray-900 flex-1">
            {{ participation.profile.mobile }}
          </div>
        </div>
        <div v-if="participation.profile.phone" class="mb-2 flex">
          <div class="text-gray-500 w-24 text-sm">Téléphone</div>
          <div class="text-gray-900 flex-1">
            {{ participation.profile.phone }}
          </div>
        </div>
        <div v-if="participation.profile.birthday" class="mb-2 flex">
          <div class="text-gray-500 w-24 text-sm">Naissance</div>
          <div class="text-gray-900 flex-1">
            {{ participation.profile.birthday }}
          </div>
        </div>
        <div v-if="participation.profile.zip" class="mb-2 flex">
          <div class="text-gray-500 w-24 text-sm">Code postal</div>
          <div class="text-gray-900 flex-1">
            {{ participation.profile.zip }}
          </div>
        </div>
        <div
          v-if="
            participation.profile.domaines &&
            participation.profile.domaines.length > 0
          "
          class="mb-2 flex"
        >
          <div class="text-gray-500 w-24 text-sm">Domaines</div>
          <div class="text-gray-900 flex-1">
            {{
              participation.profile.domaines
                .map(function (item) {
                  return item.name.fr
                })
                .join(', ')
            }}
          </div>
        </div>
        <div
          v-if="
            participation.profile.skills &&
            participation.profile.skills.length > 0
          "
          class="mb-2 flex"
        >
          <div class="text-gray-500 w-24 text-sm">Compétences</div>
          <div class="text-gray-900 flex-1">
            {{
              participation.profile.skills
                .map(function (item) {
                  return item.name.fr
                })
                .join(', ')
            }}
          </div>
        </div>
        <div v-if="participation.profile.disponibilities" class="mb-2 flex">
          <div class="text-gray-500 w-24 text-sm">Dispos</div>
          <div class="text-gray-900 flex-1">
            {{
              participation.profile.disponibilities
                .map(
                  (disponibility) =>
                    $store.getters.taxonomies.profile_disponibilities.terms.filter(
                      (dispo) => dispo.value == disponibility
                    )[0].label
                )
                .join(', ')
            }}
          </div>
        </div>
        <div v-if="participation.profile.frequence" class="mb-2 flex">
          <div class="text-gray-500 w-24 text-sm">Durée</div>
          <div class="text-gray-900 flex-1">
            {{ participation.profile.frequence }} par
            {{ participation.profile.frequence_granularite }}
          </div>
        </div>
        <div v-if="participation.profile.description" class="mb-2 flex">
          <div class="text-gray-500 w-24 text-sm">Motivation</div>
          <div class="text-gray-900 flex-1">
            {{ participation.profile.description }}
          </div>
        </div>
      </section>
    </template>

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
        La participation est
        <span
          class="font-bold text-primary"
          :class="{
            'text-orange-400':
              participation.state == 'En attente de validation',
            'text-green-800': participation.state == 'Validée',
            'text-green-600': participation.state == 'Effectuée',
            'text-red-500': participation.state == 'Refusée',
          }"
          >{{ participation.state | lowercase }}</span
        >
      </div>
      <participation-dropdown-state
        v-if="
          ($store.getters.contextRole == 'responsable' ||
            $store.getters.contextRole == 'admin') &&
          !isBenevole
        "
        class="mt-3"
        :form="participation"
        @updated="$emit('updated')"
      />
      <participation-cancel-button
        v-if="isBenevole && participation.state == 'En attente de validation'"
        class="mt-3"
        :form="participation"
        @updated="$emit('updated')"
      ></participation-cancel-button>

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
      <div
        v-if="participation.mission.type == 'Mission en présentiel'"
        class="mb-6"
      >
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
    <template v-if="isBenevole">
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
        <div
          class="font-light"
          v-html="participation.mission.description"
        ></div>
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
              {{
                beneficiaryPublic
                  | labelFromValue('mission_publics_beneficiaires')
              }}
            </p>
          </li>
        </ul>
      </section>

      <section>
        <h3 class="text-xl leading-8 font-bold text-gray-900 mb-4">
          Commentaires de l'organisation
        </h3>
        <div
          class="font-light"
          v-html="participation.mission.information"
        ></div>
      </section>
    </template>
  </div>
</template>

<script>
import ParticipationDropdownState from '@/components/ParticipationDropdownState'
import ParticipationCancelButton from '@/components/ParticipationCancelButton'

export default {
  name: 'ConversationDetail',
  components: {
    ParticipationDropdownState,
    ParticipationCancelButton,
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
  computed: {
    isBenevole() {
      return (
        this.participation.profile_id == this.$store.getters.user.profile.id
      )
    },
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
