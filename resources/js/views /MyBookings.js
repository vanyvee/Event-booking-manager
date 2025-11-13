<template>
  <div class="container mt-4">
    <h3 class="mb-4">My Bookings</h3>

    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary"></div>
    </div>

    <div v-else>
      <div v-if="bookings.length === 0" class="alert alert-info">
        You have no bookings yet.
      </div>

      <div class="list-group">
        <div v-for="booking in bookings" :key="booking.id" class="list-group-item d-flex justify-content-between align-items-center">
          <div>
            <h5>{{ booking.ticket.event.name }}</h5>
            <p>
              <strong>Ticket:</strong> {{ booking.ticket.type }} <br>
              <strong>Quantity:</strong> {{ booking.quantity }} <br>
              <strong>Total:</strong> ₦{{ booking.total_price }} <br>
              <strong>Status:</strong> 
              <span :class="statusClass(booking.status)">{{ booking.status }}</span>
            </p>
          </div>
          <div>
            <button 
              v-if="booking.status === 'booked'" 
              class="btn btn-danger btn-sm" 
              @click="cancelBooking(booking.id)">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { BookingService } from '@/services/bookingService'

const bookings = ref([])
const loading = ref(false)

const fetchBookings = async () => {
  loading.value = true
  try {
    const res = await BookingService.getMyBookings()
    bookings.value = res.data || []
  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
}

onMounted(fetchBookings)

const cancelBooking = async (bookingId) => {
  if (!confirm('Are you sure you want to cancel this booking?')) return
  try {
    const res = await BookingService.cancelBooking(bookingId)
    alert(res.message)
    fetchBookings() // refresh list
  } catch (err) {
    alert(err.message || 'Cancellation failed')
  }
}

const statusClass = (status) => {
  switch(status) {
    case 'booked': return 'badge bg-success'
    case 'cancelled': return 'badge bg-secondary'
    case 'refunded': return 'badge bg-warning text-dark'
    default: return 'badge bg-light text-dark'
  }
}
</script>

<style scoped>
h3 {
  font-weight: 600;
}
.list-group-item {
  border-radius: 8px;
  margin-bottom: 10px;
  padding: 20px;
  background-color: #f8f9fa;
}
</style>

