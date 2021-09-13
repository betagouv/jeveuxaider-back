export default function ({ route, $cookies }) {
  console.log('execute midleware set-utm')
  if (route.query.utm_source) {
    console.log('has utm source', route.query.utm_source)
    $cookies.set('utm_source', route.query.utm_source, {
      path: '/',
      maxAge: 60 * 60 * 24 * 10, // 10 jours,
      domain: '.jeveuxaider.gouv.fr',
    })
    console.log('cookie set')
  }
}
