<template>
  <div
    class="card--mission h-auto sm:h-full flex flex-col bg-white rounded-lg overflow-hidden"
  >
    <div class="thumbnail--wrapper relative">
      <img
        v-if="thumbnail.default"
        :src="thumbnail.default"
        :srcset="`${thumbnail.x2} 2x`"
        :alt="mission.domaine_name"
        class="w-full h-full object-cover"
      />
      <div class="pill absolute m-4 top-0 right-0">
        <template
          v-if="mission.city && mission.type == 'Mission en présentiel'"
        >
          <template v-if="mission.department">
            {{ mission.city }} ({{ mission.department }})
          </template>
          <template v-else>
            {{ mission.city }}
          </template>
        </template>
        <template v-else> À distance </template>
      </div>
    </div>

    <div class="mb-auto p-4">
      <div class="pill-2">{{ mission.domaine_name }}</div>

      <v-clamp
        tag="h2"
        :max-lines="3"
        autoresize
        class="name font-black text-black text-lg relative hidden sm:block"
      >
        {{ mission.name }}

        <template
          slot="after"
          slot-scope="{ expand, collapse, toggle, clamped, expanded }"
        >
          <!-- Tooltip if clamped -->
          <span
            v-if="clamped"
            v-tooltip="{
              delay: { show: 700, hide: 100 },
              content: mission.name,
              hideOnTargetClick: true,
              placement: 'top',
            }"
            class="absolute w-full h-full top-0 left-0"
          />
        </template>
      </v-clamp>

      <h2 class="sm:hidden name font-black text-black text-lg">
        {{ mission.name }}
      </h2>

      <h3
        class="structure mt-4 mb-1 truncate"
        v-text="mission.structure.name"
      />

      <div
        v-if="mission.provider == 'api_engagement'"
        class="api-engagement mt-4 mb-1"
      >
        <div class="flex items-center justify-between">
          <div class="mr-8">
            <div>Mission proposée par</div>
            <div class="font-bold text-black">{{ mission.publisher_name }}</div>
          </div>
          <img
            :src="mission.publisher_logo"
            :alt="mission.publisher_name"
            width="70px"
            class="h-auto"
          />
        </div>
      </div>
    </div>

    <div class="footer border-t p-4 text-center relative">
      <span
        class="places-left font-bold"
        :class="[{ 'is-full': !mission.has_places_left }]"
      >
        <template v-if="mission.has_places_left">
          {{ mission.places_left | formatNumber }}
          {{
            mission.places_left
              | pluralize(['bénévole recherché', 'bénévoles recherchés'])
          }}
        </template>
        <template v-else>Complet</template>
      </span>

      <img
        v-if="mission.provider == 'api_engagement'"
        :src="
          mission.has_places_left
            ? '/images/external_green.svg'
            : '/images/external_gray.svg'
        "
        width="15px"
        class="absolute m-4 right-0 top-0 bottom-0"
      />
    </div>
  </div>
</template>

<script>
import MissionMixin from '@/mixins/MissionMixin'

export default {
  mixins: [MissionMixin],
  props: {
    mission: {
      type: Object,
      default: null,
    },
  },
}
</script>

<style lang="sass" scoped>
.card--mission
  box-shadow: 0px 4px 14px 0px rgba(0, 0, 0, .05)
  &:hover
    .thumbnail--wrapper
      img
        transform: scale(1.05)

.thumbnail--wrapper
  height: 143px
  @apply bg-gray-200 overflow-hidden
  img
    transition: all .25s

.footer
  .places-left
    color: #30C48D
    font-size: 13px
    &.is-full
      color: #d42b3b

.structure,
.api-engagement
  font-size: 13px
  color: #696974

.name
  line-height: 22px

.footer
  border-color: #D9D9D9

.pill
  border-radius: 5px
  background-color: white
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1)
  font-size: 11px
  color: #171725
  height: 23.5px
  @apply px-3 mb-4 inline-flex items-center truncate

.pill-2
  border-radius: 35px
  background-color: #EBF4FF
  font-size: 11px
  letter-spacing: 0.01em
  color: #070191
  @apply font-bold uppercase py-1 px-3 mb-4 inline-flex
</style>
