<script>
import { mapGetters } from 'vuex'

export default {
  name: 'VoletRow',
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
</script>
