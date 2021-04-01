import Plausible from 'plausible-tracker'

export default (context, inject) => {
  const options = {
    domain: 'jeveuxaider.gouv.fr',
    hashMode: false,
    trackLocalhost: false,
    apiHost: 'https://plausible.io',
  }
  const plausible = Plausible(options)
  plausible.enableAutoPageviews()
  inject('plausible', plausible)
}
