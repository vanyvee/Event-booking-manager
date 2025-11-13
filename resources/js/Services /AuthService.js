// src/services/authService.js
import { apiBase } from './api.js'

export const AuthService = {
  async register(payload) {
    const res = await fetch(`${apiBase}/register`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })
    return res.json()
  },

  async login(payload) {
    const res = await fetch(`${apiBase}/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })
    return res.json()
  },

  async logout() {
    const res = await fetch(`${apiBase}/logout`, {
      method: 'POST',
      credentials: 'include'
    })
    return res.json()
  },

  async me() {
    const res = await fetch(`${apiBase}/me`, {
      headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
    })
    return res.json()
  }
}

