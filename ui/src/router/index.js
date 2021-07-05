import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Songs from '../components/Songs.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/songs',
    name: 'songs',
    component: Songs
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
