<template>
  <div class="checkout-container" v-if="ticket">
    <h3>Checkout: {{ ticket.type }} Ticket</h3>
    <p><strong>Event:</strong> {{ ticket.event.name }}</p>
    <p><strong>Price per ticket:</strong> ₦{{ ticket.price }}</p>

    <div class="mb-3">
      <label>Quantity</label>
      <input type="number" class="form-control" v-model.number="quantity" min="1" :max="ticket.quantity">
    </div>

    <p><strong>Total:</strong> ₦{{ total }}</p>

    <button class="btn btn-success w-100" @click="handlePayment" :disabled="loading">
      {{ loading ? 'Processing...' : 'Pay with Opay' }}
    </button>
  </div>

  <div v-else class="loading-container">
    <div class="spinner-border text-primary"></div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { BookingService } from '@/services/bookingService'

const route = useRoute()
const router = useRouter()
const ticket = ref(null)
const quantity = ref(1)
const loading = ref(false)

onMounted(async () => {
  const ticketId = route.query.ticketId
  const res = await BookingService.getTicket(ticketId)
  ticket.value = res.data
})

const total = computed(() => {
  return ticket.value ? ticket.value.price * quantity.value : 0
})

const handlePayment = async () => {
  if (quantity.value < 1) return alert('Quantity must be at least 1')
  if (quantity.value > ticket.value.quantity) return alert('Not enough tickets available')

  loading.value = true

  try {
    const res = await BookingService.bookTicket(ticket.value.id, quantity.value)
    
    if (res.payment_url) {
      window.location.href = res.payment_url
    } else {
      alert('Booking successful!')
      router.push('/booking-success')
    }
  } catch (err) {
    alert(err.message || 'Booking failed')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.checkout-container {
  max-width: 500px;
  margin: 2rem auto;
  padding: 2rem;
  background-color: #f8f9fa;
  border-radius: 0.5rem;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.checkout-container h3 {
  margin-bottom: 1.5rem;
  color: #343a40;
  text-align: center;
}

.checkout-container p {
  font-size: 1rem;
  margin-bottom: 1rem;
}

.loading-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 60vh;
}
</style>