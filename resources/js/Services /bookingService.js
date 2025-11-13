import { apiBase } from './api.js'
import { useUserStore } from '@/stores/userStore'

export const BookingService = {
  async getTicket(ticketId) {
    const res = await fetch(`${apiBase}/tickets/${ticketId}`, {
      headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
    })
    return res.json()
  },
async getMyBookings() {
  const res = await fetch(`${apiBase}/bookings/my`, {
    headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
  })
  return res.json()
},

async cancelBooking(bookingId) {
  const res = await fetch(`${apiBase}/bookings/cancel/${bookingId}`, {
    method: 'POST',
    headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
  })
  return res.json()
},
  async bookTicket(ticketId, quantity) {
    const res = await fetch(`${apiBase}/bookings`, {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify({ ticket_id: ticketId, quantity })
    })
    return res.json()
  }
}

