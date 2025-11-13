
import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from '@/stores/userStore'
import RefundRequests from '@/pages/RefundRequests.vue'
import RevenueReport from '@/pages/RevenueReport.vue'
import { requireAdmin, requireOrganizer } from './authGuard'


import EventDetail from '@/pages/EventDetail.vue'
import MyBookings from '@/pages/MyBookings.vue'

import CheckoutPage from '@/pages/CheckoutPage.vue'

import LoginPage from '@/pages/auth/LoginPage.vue'
import RegisterPage from '@/pages/auth/RegisterPage.vue'
import HomePage from '@/pages/HomePage.vue'
import DashboardPage from '@/pages/DashboardPage.vue'

import { requireAuth, requireGuest } from './authGuard'

const routes = [
  { path: '/', component: HomePage, beforeEnter: requireAuth },
  { path: '/login', component: LoginPage, beforeEnter: requireGuest },
  { path: '/register', component: RegisterPage, beforeEnter: requireGuest },
  { path: '/admin', component: AdminDashboard, beforeEnter: requireAuth }, // only logged-in admin/organizer

  { path: '/', component: HomePage, beforeEnter: requireAuth },
  { path: '/events/:id', component: EventDetail, beforeEnter: requireAuth },
  { path: '/login', component: LoginPage, beforeEnter: requireGuest },
  { path: '/register', component: RegisterPage, beforeEnter: requireGuest },


  { path: '/', component: HomePage, beforeEnter: requireAuth },
  { path: '/events/:id', component: EventDetail, beforeEnter: requireAuth },
  { path: '/checkout', component: CheckoutPage, beforeEnter: requireAuth },
  { path: '/login', component: LoginPage, beforeEnter: requireGuest },
  { path: '/register', component: RegisterPage, beforeEnter: requireGuest },


  { path: '/', component: HomePage, beforeEnter: requireAuth },
  { path: '/events/:id', component: EventDetail, beforeEnter: requireAuth },
  { path: '/checkout', component: CheckoutPage, beforeEnter: requireAuth },
  { path: '/my-bookings', component: MyBookings, beforeEnter: requireAuth },
  { path: '/login', component: LoginPage, beforeEnter: requireGuest },
  { path: '/register', component: RegisterPage, beforeEnter: requireGuest },



  { path: '/admin/refunds', component: RefundRequests, beforeEnter: requireAdmin },
  { path: '/admin/revenue', component: RevenueReport, beforeEnter: requireOrganizer },



]

const router = createRouter({
  history: createWebHistory(),
  routes
})


rrouter.beforeEach((to, from, next) => {
  const store = useUserStore()

  if (to.meta.requiresAuth && !store.token) {
    return next('/login')
  }

  if ((to.path === '/login' || to.path === '/register') && store.token) {
    return next('/dashboard')
  }

  next()
})

export default router