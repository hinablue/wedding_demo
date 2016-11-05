import Vue from 'vue'
import VueRouter from 'vue-router'
import swal from 'sweetalert2'
import App from './App.vue'
import Homepage from './components/Homepage.vue'
import Messages from './components/Messages.vue'
import Invitation from './components/Invitation.vue'

Vue.use(VueRouter)
Vue.prototype.$swal = swal

const router = new VueRouter({
  mode: 'history',
  linkActiveClass: 'is-active',
  routes: [
    {
      name: 'invitation',
      path: '/invitation',
      component: Invitation
    },
    {
      name: 'messages',
      path: '/messages',
      component: Messages
    },
    {
      name: 'homepage',
      path: '/',
      component: Homepage
    },
    {
      path: '*',
      component: Homepage
    }
  ]
})

/* eslint-disable no-new */
new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
