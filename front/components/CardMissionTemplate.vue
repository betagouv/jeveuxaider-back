<template>
  <div
    class="max-w-[320px] rounded-lg overflow-hidden shadow-lg cursor-pointer group hover:scale-105 transition flex flex-col"
  >
    <div
      class="bg-green-500 text-white px-3 py-1 inline-block mx-auto z-10 font-medium text-xs rounded-b"
    >
      Validation automatique
    </div>
    <img
      class="w-full mt-[-24px]"
      :src="imageUrl"
      alt="Sunset in the mountains"
    />
    <div class="px-6 py-4 flex-1">
      <div class="font-extrabold mb-2 tracking-tight text-lg">
        {{ title }}
      </div>
      <p class="text-gray-500 text-sm">
        {{ description }}
      </p>
    </div>
    <div
      class="border-t text-primary font-semibold text-center py-4 group-hover:bg-primary group-hover:text-white"
    >
      {{ actionTitle }}
    </div>
  </div>
</template>

<script>
import MissionMixin from '@/mixins/MissionMixin'

export default {
  mixins: [MissionMixin],
  props: {
    title: {
      type: String,
      default: null,
    },
    description: {
      type: String,
      default: null,
    },
    imageUrl: {
      type: String,
      default: '/images/card-thumbnail-default@2x.jpg',
    },
    actionTitle: {
      type: String,
      default: 'Choisir',
    },
    actionLink: {
      type: String,
      default: null,
    },
  },
}
</script>
