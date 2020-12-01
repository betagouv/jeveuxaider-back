import Vue from 'vue'
import Router from 'vue-router'

// Fix for NavigationDuplicated error -> need to add catch to push promise.
const originalPush = Router.prototype.push
Router.prototype.push = function push(location) {
  return originalPush.call(this, location).catch((err) => err)
}

Vue.use(Router)

import publicRoutes from '@/router/routes/public.js'
import userRoutes from '@/router/routes/user.js'
import registerRoutes from '@/router/routes/register.js'
import dashboardRoutes from '@/router/routes/dashboard.js'

let routes = []

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: routes.concat(
    publicRoutes,
    userRoutes,
    registerRoutes,
    dashboardRoutes
  ),
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
})
