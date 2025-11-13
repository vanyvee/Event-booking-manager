import { defineStore } from 'pinia'
import { EventService } from '@/services/eventService'

export const useEventStore = defineStore('event', {
  state: () => ({
    events: [],
    selectedEvent: null,
    loading: false,
  }),

  actions: {
    async fetchEvents() {
      this.loading = true
      try {
        const res = await EventService.getEvents()
        this.events = res.data || []
      } catch (err) {
        console.error(err)
      } finally {
        this.loading = false
      }
    },

    selectEvent(event) {
      this.selectedEvent = event
    }
  }
})

