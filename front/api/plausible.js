export default (axios, config) => ({
  async plausibleAggregate(period, metrics, filters) {
    return await axios.get(
      `${config.plausible.base_url}/api/v1/stats/aggregate?site_id=${config.plausible.site_id}&period=${period}&metrics=${metrics}&filters=${filters}`,
      {
        excludeContextRole: true,
        headers: {
          Authorization: `Bearer ${config.plausible.token}`,
        },
      }
    )
  },
})
