
import { apiBase } from './api.js'

export const EventService = {
  async getEvents() {
    const res = await fetch(`${apiBase}/events`)
    return res.json()
  },

  async getEvent(id) {
    const res = await fetch(`${apiBase}/events/${id}`)
    return res.json()
  }
}
