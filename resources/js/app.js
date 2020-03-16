import router from './router.js'
import store from './store'

require('./bootstrap');

const app = new Vue({
    router,
    store,
    methods: {}
}).$mount('#app')
