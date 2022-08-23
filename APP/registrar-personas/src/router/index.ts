import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';

const routes: Array<RouteRecordRaw> = [
  {
    path: '',
    redirect: '/person'
  },
  {
    path: '/person',
    component: () => import ('../views/AllPersons.vue')
  },
  {
    path: '/person/:id',
    component: () => import ('../views/OnePerson.vue')
  },
  {
    path: '/person/new',
    component: () => import ('../views/NewPerson.vue')
  },
  {
    path: '/person/search/email',
    component: () => import ('../views/SearchPerson.vue')
  },
  {
    path: '/person/search/phone',
    component: () => import ('../views/SearchPerson.vue')
  },
  {
    path: '/person/search/email/:email',
    component: () => import ('../views/SearchPerson.vue')
  },
  {
    path: '/person/search/phone/:phone',
    component: () => import ('../views/SearchPerson.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
