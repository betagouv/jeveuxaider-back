<template>
  <div
    class="card--mission h-auto flex flex-col flex-1 bg-white rounded-lg overflow-hidden"
  >
    <div class="thumbnail--wrapper relative will-change-transform">
      <img
        v-if="thumbnail.default"
        :src="thumbnail.default"
        :srcset="`${thumbnail.x2} 2x`"
        :alt="mission.domaine_name"
        class="w-full h-full object-cover"
        width="300px"
        height="143px"
        @error="defaultThumbnail($event)"
      />
      <div class="pill absolute m-4 top-0 right-0">
        <template
          v-if="mission.city && mission.type == 'Mission en présentiel'"
        >
          <template v-if="mission.zip">
            {{ mission.city }} ({{ mission.zip }})
          </template>

          <template v-else-if="mission.department">
            {{ mission.city }} ({{ mission.department }})
          </template>
          <template v-else>
            {{ mission.city }}
          </template>
        </template>
        <template v-else> À distance </template>
      </div>

      <div
        v-if="formattedDate"
        class="pill absolute mr-4 bottom-0 right-0 rounded-b-none"
      >
        {{ formattedDate }}
      </div>
    </div>

    <div class="mb-auto p-4">
      <div class="pill-2">{{ mission.domaine_name }}</div>

      <client-only>
        <v-clamp
          tag="h2"
          :max-lines="3"
          autoresize
          class="name font-black text-black text-lg relative"
        >
          {{ mission.name }}

          <template slot="after" slot-scope="{ clamped }">
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
        <template slot="placeholder">
          <h2 class="name font-black text-black text-lg">{{ mission.name }}</h2>
        </template>
      </client-only>

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
      <div
        v-if="$store.getters.contextRole == 'admin'"
        class="bg-gray-50 rounded-lg p-4 text-xs"
      >
        <template
          v-if="mission._rankingInfo && mission._rankingInfo.matchedGeoLocation"
        >
          Distance :
          {{ mission._rankingInfo.matchedGeoLocation.distance / 1000 }} km<br />
        </template>

        Score : {{ mission.score }}<br />
        <span class="text-gray-400">
          Temps de réponse :
          <span v-if="mission.structure.response_time">
            {{ Math.round(mission.structure.response_time / 86400) }}
            jours</span
          ><span v-else>n/a</span> ({{
            mission.structure.response_time_score
          }}/100)
          <br />
          Taux de réponse : {{ mission.structure.response_ratio }}%
        </span>
      </div>
    </div>

    <div
      v-if="showState && participation"
      class="footer border-t p-4 text-center relative"
    >
      <span class="text-sm font-bold" :class="participationStateTheme">{{
        participation.state
      }}</span>
    </div>
    <div v-else class="footer border-t p-4 text-center relative">
      <span
        class="places-left font-bold"
        :class="[{ 'is-full': mission.has_places_left === false }]"
      >
        {{ placesLeftText }}
      </span>

      <img
        v-if="mission.provider == 'api_engagement'"
        :src="
          mission.has_places_left
            ? '/images/external_green.svg'
            : '/images/external_gray.svg'
        "
        width="15px"
        class="absolute mx-4 my-auto right-0 top-0 bottom-0"
        alt="Lien externe"
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
    participation: {
      type: Object,
      default: null,
    },
    showState: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    placesLeftText() {
      if (
        this.mission.publisher_name &&
        this.mission.publisher_name != 'Réserve Civique' &&
        this.mission.places_left > 99
      ) {
        return 'Plusieurs bénévoles recherchés'
      } else if (this.mission.has_places_left && this.mission.places_left > 0) {
        return (
          this.mission.places_left +
          ' ' +
          this.$options.filters.pluralize(this.mission.places_left, [
            'bénévole recherché',
            'bénévoles recherchés',
          ])
        )
      } else {
        return this.mission.has_places_left === false
          ? 'Complet'
          : 'Plusieurs bénévoles recherchés'
      }
    },
    participationStateTheme() {
      if (this.participation) {
        switch (this.participation.state) {
          case 'En attente de validation':
            return 'text-[#f6ad55]'
          case 'Validée':
            return 'text-[#16a972]'
          default:
            return ''
        }
      }
      return ''
    },
    formattedDate() {
      const startDate = this.mission.start_date
      const endDate = this.mission.end_date

      if (!startDate) {
        return
      }

      // API Engagement
      if (this.mission.provider == 'api_engagement') {
        if (!endDate) {
          return
        }
        if (this.$dayjs(endDate).diff(this.$dayjs(startDate), 'year') > 1) {
          return
        }
      }

      const startDateObject =
        Number.isInteger(startDate) && this.$dayjs.unix(startDate).isValid()
          ? this.$dayjs.unix(startDate)
          : this.$dayjs(startDate, 'YYYY-MM-DD HH:mm:ss', 'fr', true).isValid()
          ? this.$dayjs(startDate, 'YYYY-MM-DD HH:mm:ss')
          : this.$dayjs(startDate).isValid()
          ? this.$dayjs(startDate)
          : null

      let endDateObject
      if (endDate) {
        endDateObject =
          Number.isInteger(endDate) && this.$dayjs.unix(endDate).isValid()
            ? this.$dayjs.unix(endDate)
            : this.$dayjs(endDate, 'YYYY-MM-DD HH:mm:ss', 'fr', true).isValid()
            ? this.$dayjs(endDate, 'YYYY-MM-DD HH:mm:ss')
            : this.$dayjs(endDate).isValid()
            ? this.$dayjs(endDate)
            : null
      }

      if (
        endDate &&
        startDateObject.format('D MMMM YYYY') ==
          endDateObject.format('D MMMM YYYY')
      ) {
        return startDateObject.format('D MMMM')
      } else {
        return `À partir du ${startDateObject.format('D MMMM')}`
      }
    },
  },
}
</script>

<style lang="postcss" scoped>
.card--mission {
  box-shadow: 0px 4px 14px 0px rgba(0, 0, 0, 0.05);
  backface-visibility: hidden;
  transform: translate3d(0, 0, 0);
  &:hover {
    .thumbnail--wrapper img {
      transform: scale(1.05);
    }
  }
}

.thumbnail--wrapper {
  height: 143px;
  @apply bg-gray-200 overflow-hidden;
  img {
    transition: all 0.25s;
  }
}

.footer {
  .places-left {
    color: #30c48d;
    font-size: 13px;
    &.is-full {
      color: #d42b3b;
    }
  }
}

.structure,
.api-engagement {
  font-size: 13px;
  color: #696974;
}

.name {
  line-height: 22px;
}

.footer {
  border-color: #d9d9d9;
}

.pill {
  border-radius: 5px;
  background-color: white;
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
  font-size: 11px;
  color: #171725;
  height: 23.5px;
  @apply px-3 inline-flex items-center truncate;
}

.pill-2 {
  border-radius: 35px;
  background-color: #ebf4ff;
  font-size: 11px;
  letter-spacing: 0.01em;
  color: #070191;
  @apply font-bold uppercase py-1 px-3 mb-4 inline-flex;
}
</style>
