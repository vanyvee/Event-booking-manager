<template>
  <div class="container mt-4">
    <h3 class="mb-4">Refund Requests</h3>

    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary"></div>
    </div>

    <div v-else>
      <div v-if="refunds.length === 0" class="alert alert-info">
        No refund requests at the moment.
      </div>

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Booking ID</th>
            <th>User</th>
            <th>Event</th>
            <th>Ticket</th>
            <th>Quantity</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="refund in refunds" :key="refund.id">
            <td>{{ refund.booking_id }}</td>
            <td>{{ refund.user.name }}</td>
            <td>{{ refund.booking.ticket.event.name }}</td>
            <td>{{ refund.booking.ticket.type }}</td>
            <td>{{ refund.booking.quantity }}</td>
            <td>₦{{ refund.amount }}</td>
            <td>
              <span :class="statusClass(refund.status)">{{ refund.status }}</span>
            </td>
            <td>
              <button 
                v-if="refund.status === 'pending'" 
                class="btn btn-success btn-sm me-2" 
                @click="approveRefund(refund.id)">
                Approve
              </button>
              <button 
                v-if="refund.status === 'pending'" 
                class="btn btn-danger btn-sm" 
                @click="rejectRefund(refund.id)">
                Reject
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { AdminService } from '@/services/adminService'

const refunds = ref([])
const loading = ref(false)

const fetchRefunds = async () => {
  loading.value = true
  try {
    const res = await AdminService.getRefunds()
    refunds.value = res.data || []
  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
}

const approveRefund = async (id) => {
  if (!confirm('Approve this refund?')) return
  const res = await AdminService.processRefund(id, 'approved')
  alert(res.message)
  fetchRefunds()
}

const rejectRefund = async (id) => {
  if (!confirm('Reject this refund?')) return
  const res = await AdminService.processRefund(id, 'rejected')
  alert(res.message)
  fetchRefunds()
}

const statusClass = (status) => {
  switch(status) {
    case 'pending': return 'badge bg-warning text-dark'
    case 'approved': return 'badge bg-success'
    case 'rejected': return 'badge bg-danger'
    default: return 'badge bg-light text-dark'
  }
}

onMounted(fetchRefunds)
</script>

<style scoped>
h3 {
  font-weight: 600;
}
.table {
  background-color: #f8f9fa;
}
</style>

