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
  },
}
</script>
