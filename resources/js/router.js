import Vue from 'vue'
import VueRouter from 'vue-router'
import store from './store'

/*
 |--------------------------------------------------------------------------
 | Admin Views
 |--------------------------------------------------------------------------|
 */

// Layouts
import LayoutLogin from './views/layouts/LayoutLogin.vue'
import LayoutDefault from './views/layouts/LayoutDefault.vue'

// Auth
import Login from './views/auth/Login.vue'
import Register from './views/auth/Register.vue'
import ForgotPassword from './views/auth/ForgotPassword.vue'
import ResetPassword from './views/auth/ResetPassword.vue'

import Dashboard from './views/Dashboard.vue'
import Activities from './views/Activities.vue'
import Activity from './views/activity/Activity.vue'
import FormActivity from './views/activity/FormActivity.vue'
import FormBudget from './views/activity/FormBudget.vue'
import Collaborators from './views/Collaborators.vue'
import FormInvite from './views/users/FormInvite.vue'
import NotFoundPage from './views/errors/404.vue'

import Settings from './views/users/Settings.vue'

/*
 |--------------------------------------------------------------------------
 | Admin Views
 |--------------------------------------------------------------------------|
 */

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    component: LayoutLogin,
    meta: {
        redirectIfAuthenticated: true
    },
    children: [
      {
        path: '/',
        component: Login,
      },
      {
        path: 'login',
        component: Login,
        name: 'login',
      },
      {
        path: 'register',
        component: Register,
        name: 'register',
      },
      {
        path: '/forgot-password',
        component: ForgotPassword,
        name: 'forgot-password',
      },
      {
        path: '/reset-password/:token',
        component: ResetPassword,
        name: 'reset-password',
      }
    ]
  },

  {
    path: '/admin',
    component: LayoutDefault,
    meta: {
        requiresAuth: true
    },
    children: [
        {
            path: '/admin',
            component: Dashboard,
            name: 'dashboard',
        },
        {
            path: '/activities',
            component: Activities,
            name: 'activities',
        },
        {
            path: '/activity/create',
            component: FormActivity,
            name: 'activity.create',
        },
        {
            path: '/activity/:id/edit',
            component: FormActivity,
            name: 'activity.edit',
        },
        {
          path: '/activity/:id',
          component: Activity,
          name: 'activity.view',
        },
        {
          path: '/activity/:id/budget/create',
          component: FormBudget,
          name: 'activity.budget.create',
        },
        {
          path: '/activity/:id/budget/:bid',
          component: FormBudget,
          name: 'activity.budget.edit',
        },
        {
            path: '/collaborators',
            component: Collaborators,
            name: 'collaborators',
        },
        {
          path: '/collaborators/invite',
          component: FormInvite,
          name: 'invite.collaborators',
        },
        {
          path: '/settings',
          component: Settings,
          name: 'settings',
      }
    ]
  },

  //  DEFAULT ROUTE
  { path: '*', component: NotFoundPage }
]

const router = new VueRouter({
  routes,
  mode: 'history',
  linkActiveClass: 'active'
})

router.beforeEach((to, from, next) => {
  //  Redirect if not authenticated on secured routes
  if (to.matched.some(m => m.meta.requiresAuth)) {
    if (!store.getters['auth/isAuthenticated']) {
      return next('/login')
    }
  }

  if (to.matched.some(m => m.meta.redirectIfAuthenticated) && store.getters['auth/isAuthenticated']) {
    return next('/admin')
  }

  return next()
})

export default router
