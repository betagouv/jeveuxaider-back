<script>
export default {
  methods: {
    clearAddress() {
      this.form.address = ''
      this.form.zip = ''
      this.form.city = ''
      this.form.latitude = ''
      this.form.longitude = ''
    },
    clearYoungAddress() {
      this.form.regular_city = ''
      this.form.regular_latitude = ''
      this.form.regular_longitude = ''
    },
    setAddress(suggestion) {
      this.$set(this.form, 'address', suggestion.name)
      this.$set(this.form, 'zip', suggestion.postcode)
      this.$set(
        this.form,
        'city',
        suggestion.type == 'city' ? suggestion.name : suggestion.city
      )
      this.$set(this.form, 'latitude', suggestion.latlng.lat)
      this.$set(this.form, 'longitude', suggestion.latlng.lng)
    },
    setYoungAddress(suggestion) {
      this.$set(
        this.form,
        'regular_city',
        suggestion.type == 'city' ? suggestion.name : suggestion.city
      )
      this.$set(this.form, 'regular_latitude', suggestion.latlng.lat)
      this.$set(this.form, 'regular_longitude', suggestion.latlng.lng)
    },
  },
}
</script>
