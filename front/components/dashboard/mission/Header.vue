<template>
  <div class="header px-12 flex">
    <div class="header-titles flex-1">
      <div class="text-m text-gray-600 uppercase">Mission</div>
      <div class="mb-8 max-w-3xl">
        <div class="font-bold text-[1.75rem] text-[#242526] mr-2">
          {{ mission.name }}
        </div>
        <div
          v-if="!['Signalée'].includes(mission.state)"
          class="font-light text-gray-600 flex items-center"
        >
          <div
            :class="
              structure.state == 'Validée' &&
              ['Validée', 'Terminée'].includes(mission.state)
                ? 'bg-[#0e9f6e]'
                : 'bg-[#f56565]'
            "
            class="rounded-full h-2 w-2 mr-2 flex-none"
          ></div>
          <nuxt-link
            target="_blank"
            :to="`/missions-benevolat/${mission.id}/${mission.slug}`"
          >
            <span class="text-sm underline hover:no-underline break-all">
              {{ $config.appUrl }}/missions-benevolat/{{ mission.id }}/{{
                mission.slug
              }}
            </span>
          </nuxt-link>
        </div>
        <TagModelState class="mt-4" :state="mission.state" />
      </div>
    </div>
    <div>
      <DropdownMissionButton :mission="mission" />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    mission: {
      type: Object,
      required: true,
    },
    structure: {
      type: Object,
      required: true,
    },
  },
}
</script>
