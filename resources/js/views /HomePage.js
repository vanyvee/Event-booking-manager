<template>
  <div class="container mt-4">
    <h3 class="mb-4">Upcoming Events</h3>

    <div v-if="loading" class="text-center">
      <div class="spinner-border text-primary"></div>
    </div>

    <div class="row">
      <div class="col-md-4 mb-3" v-for="event in events" :key="event.id">
        <div class="card h-100 event-card">
          <img :src="event.image || placeholder" class="card-img-top" alt="Event Image">
          <div class="card-body">
            <h5 class="card-title">{{ event.name }}</h5>
            <p class="card-text">{{ event.description.substring(0, 80) }}...</p>
            <p class="card-text"><small class="text-muted">{{ event.date }}</small></p>
            <button class="btn btn-primary w-100" @click="viewEvent(event)">View Details</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useEventStore } from '@/stores/eventStore'
import { useRouter } from 'vue-router'

const eventStore = useEventStore()
const router = useRouter()
const loading = ref(false)
const placeholder = 'https://via.placeholder.com/400x200?text=Event'

onMounted(async () => {
  loading.value = true
  await eventStore.fetchEvents()
  loading.value = false
})

const events = eventStore.events

const viewEvent = (event) => {
  eventStore.selectEvent(event)
  router.push(`/events/${event.id}`)
}
</script>

<style scoped>
h3 {
  font-weight: 600;
  color: #343a40;
}

.event-card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  cursor: pointer;
}

.event-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.card-body h5 {
  font-size: 1.1rem;
  font-weight: 500;
  color: #212529;
}

.card-text {
  font-size: 0.9rem;
  color: #495057;
}

.btn-primary {
  background-color: #0d6efd;
  border-color: #0d6efd;
}

.btn-primary:hover {
  background-color: #0b5ed7;
  border-color: #0a58ca;
}
</style>