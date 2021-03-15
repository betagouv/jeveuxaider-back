export default function ({ redirect, route, store }) {
  if (!store.getters.isLogged) {
    return redirect({
      name: 'login',
      query: {
        redirect: route.fullPath,
      },
    })
  }
  // Only if user has at least a role ( not benevole )
  if (store.getters.roles.length == 0) {
    return redirect('/')
  }
}
