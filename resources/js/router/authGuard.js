
import { useUserStore } from '@/stores/userStore'

export function requireAuth(to, from, next) {
  const store = useUserStore()
  if (!store.token) {
    next('/login')
  } else {
    next()
  }
}

export function requireGuest(to, from, next) {
  const store = useUserStore()
  if (store.token) {
    next('/') // already logged in, redirect home
  } else {
    next()
  }
}