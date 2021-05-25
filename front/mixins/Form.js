export default {
  methods: {
    showErrors(fields) {
      const errors = []
      for (const property in fields) {
        errors.push(fields[property][0].message)
      }
      this.$message.error({
        message: errors.join('\r\n'),
      })
    },
  },
}
