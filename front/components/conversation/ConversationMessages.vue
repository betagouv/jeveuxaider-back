<template>
  <div class="mb-4">
    <div class="flex items-start">
      <Avatar class="mr-4" :source="thumbnail" :fallback="shortName" />

      <div>
        <div class="flex items-baseline">
          <span class="font-bold mr-2">{{ name }}</span>
          <span v-if="date" class="text-gray-500 font-light text-sm">
            {{ date | formatCustom('D MMM HH[h]mm') }}
          </span>
        </div>
        <div>
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MessageFull',
  props: {
    name: {
      type: String,
      required: true,
    },
    shortName: {
      type: String,
      required: true,
    },
    thumbnail: {
      type: String,
      default: undefined,
    },
    date: {
      type: String,
      default: null,
    },
  },
  computed: {
    formatStatus() {
      return this.status ? 'En cours' : 'Archivé'
    },
  },
}
</script>

<style lang="sass" scoped></style>
