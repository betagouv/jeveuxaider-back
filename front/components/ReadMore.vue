<template>
  <component :is="tag">
    <span
      class="wysiwyg-field read-more--content"
      v-html="formattedString"
    ></span>

    <span v-show="text.length > maxChars">
      <span
        v-show="!isReadMore"
        :class="moreClass"
        @click="triggerReadMore($event, true)"
      >
        {{ moreStr }}
      </span>
      <span v-show="isReadMore" @click="triggerReadMore($event, false)">
        {{ lessStr }}
      </span>
    </span>
  </component>
</template>

<script>
export default {
  props: {
    moreClass: {
      type: String,
      default: 'cursor-pointer uppercase font-bold text-sm text-gray-800',
    },
    moreStr: {
      type: String,
      default: 'Lire plus',
    },
    lessStr: {
      type: String,
      default: '',
    },
    text: {
      type: String,
      required: true,
    },
    maxChars: {
      type: Number,
      default: 100,
    },
    tag: {
      type: String,
      default: 'div',
    },
  },

  data() {
    return {
      isReadMore: false,
    }
  },

  computed: {
    formattedString() {
      let valContainer = this.text

      if (!this.isReadMore && this.text.length > this.maxChars) {
        valContainer = valContainer.substring(0, this.maxChars) + '...'
      }

      return valContainer
    },
  },

  methods: {
    triggerReadMore(e, b) {
      if (this.lessStr !== null || this.lessStr !== '') this.isReadMore = b
    },
  },
}
</script>

<style lang="sass" scoped>
.read-more--content
  ::v-deep p:last-child
    display: inline
</style>
