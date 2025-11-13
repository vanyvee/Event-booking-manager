<template>
  <div class="container mt-4">
    <h3 class="mb-4">Revenue Report</h3>

    <div class="row mb-3">
      <div class="col-md-3">
        <input type="date" v-model="startDate" class="form-control">
      </div>
      <div class="col-md-3">
        <input type="date" v-model="endDate" class="form-control">
      </div>
      <div class="col-md-3">
        <button class="btn btn-primary" @click="fetchRevenue">Filter</button>
      </div>
    </div>

    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary"></div>
    </div>

    <div v-else>
      <div class="alert alert-info" v-if="!revenue">
        No revenue data available.
      </div>

      <div v-else>
        <p><strong>Total Revenue:</strong> ₦{{ revenue.total }}</p>
        <p><strong>Total Refunds:</strong> ₦{{ revenue.refunds }}</p>
        <p><strong>Net Revenue:</strong> ₦{{ revenue.net }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { AdminService } from '@/services/adminService'

const startDate = ref('')
const endDate = ref('')
const revenue = ref(null)
const loading = ref(false)

const fetchRevenue = async () => {
  loading.value = true
  try {
    const res = await AdminService.getRevenue(startDate.value, endDate.value)
    revenue.value = res.data
  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
}

onMounted(fetchRevenue)
</script>

<style scoped>
h3 {
  font-weight: 600;
}
</style>

