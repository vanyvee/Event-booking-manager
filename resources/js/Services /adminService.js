import { apiBase } from './api.js'

export const AdminService = {
  async getRefunds() {
    const res = await fetch(`${apiBase}/refunds`, {
      headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
    })
    return res.json()
  },

  async processRefund(refundId, action) {
    const res = await fetch(`${apiBase}/refunds/${refundId}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify({ action })
    })
    return res.json()
  },

  async getRevenue(startDate = null, endDate = null) {
    const query = startDate && endDate ? `?start=${startDate}&end=${endDate}` : ''
    const res = await fetch(`${apiBase}/organizer/revenue-summary${query}`, {
      headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
    })
    return res.json()
  }
}

