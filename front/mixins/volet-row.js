import { mapGetters } from 'vuex'

export default {
  data() {
    return {
      form: {},
    }
  },
  computed: {
    ...mapGetters({
      row: 'volet/row',
    }),
  },
  watch: {
    row: {
      immediate: true,
      handler(val) {
        this.form = { ...val }
      },
    },
  },
}
