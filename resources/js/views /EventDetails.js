<template>
  <div class="container mt-4" v-if="event">
    <h3 class="event-title">{{ event.name }}</h3>
    <img :src="event.image || placeholder" class="img-fluid mb-3 event-image">
    <p class="event-description">{{ event.description }}</p>
    <p><strong>Date:</strong> {{ event.date }}</p>
    <p><strong>Location:</strong> {{ event.location }}</p>

    <h5 class="mt-4">Tickets</h5>
    <div class="row">
      <div class="col-md-4 mb-3" v-for="ticket in event.tickets" :key="ticket.id">
        <div class="card h-100 ticket-card">
          <div class="card-body">
            <h6 class="card-title">{{ ticket.type }}</h6>
            <p class="card-text">Price: ₦{{ ticket.price }}</p>
            <p class="card-text">Available: {{ ticket.quantity }}</p>
            <button class="btn btn-success w-100 book-btn" @click="bookTicket(ticket)">
              Book Now
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div v-else class="text-center mt-5">
    <div class="spinner-border text-primary"></div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { EventService } from '@/services/eventService'

const route = useRoute()
const router = useRouter()
const event = ref(null)
const placeholder = 'https://via.placeholder.com/400x200?text=Event'

onMounted(async () => {
  const res = await EventService.getEvent(route.params.id)
  event.value = res.data
})

const bookTicket = (ticket) => {
  router.push(`/checkout?ticketId=${ticket.id}`)
}
</script>

<style scoped>
.event-title {
  font-weight: 600;
  margin-bottom: 15px;
}

.event-image {
  border-radius: 8px;
  max-height: 300px;
  object-fit: cover;
}

.event-description {
  font-size: 1rem;
  margin-bottom: 10px;
}

.ticket-card {
  transition: transform 0.2s, box-shadow 0.2s;
}

.ticket-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.book-btn {
  transition: background-color 0.2s, transform 0.2s;
}

.book-btn:hover {
  background-color: #28a745cc;
  transform: scale(1.02);
}
</style>