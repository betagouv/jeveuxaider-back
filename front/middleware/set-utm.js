export default function ({ route, $cookies }) {
  if (route.query.utm_source) {
    $cookies.set('utm_source', route.query.utm_source, {
      path: '/',
      maxAge: 60 * 60 * 24 * 10, // 10 jours
    })
  }
}
