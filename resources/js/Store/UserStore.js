// src/stores/userStore.js
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { AuthService } from '@/services/authService'

export const useUserStore = defineStore('user', () => {
  // State
  const user = ref(null)
  const token = ref(localStorage.getItem('token') || null)

  // Getters (optional)
  const isAuthenticated = computed(() => !!token.value)

  // Actions
  const initUser = async () => {
    if (token.value) {
      const res = await AuthService.me()
      if (res.user) user.value = res.user
    }
  }

  const register = async (data) => {
    const res = await AuthService.register(data)
    if (res.token) {
      token.value = res.token
      localStorage.setItem('token', res.token)
      user.value = res.user
    }
    return res
  }
async me() {
  const res = await AuthService.me()
  if (res.user) this.user = res.user
}
  
  const login = async (data) => {
    const res = await AuthService.login(data)
    if (res.token) {
      token.value = res.token
      localStorage.setItem('token', res.token)
      user.value = res.user
    }
    return res
  }

  const logout = () => {
    user.value = null
    token.value = null
    localStorage.removeItem('token')
  }

  return {
    user,
    token,
    isAuthenticated,
    initUser,
    register,
    login,
    logout,
  }
})