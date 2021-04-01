import Plausible from 'plausible-tracker'

export default (context, inject) => {
  const options = {
    domain: 'jeveuxaider.gouvd.fr',
    hashMode: false,
    trackLocalhost: true,
    apiHost: 'https://plausible.io',
  }
  const plausible = Plausible(options)
  plausible.enableAutoPageviews()
  inject('plausible', plausible)
}
