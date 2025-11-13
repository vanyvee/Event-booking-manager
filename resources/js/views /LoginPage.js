<template>
  <div class="auth-wrapper">
    <div class="auth-card shadow-sm">
      <h4 class="text-center mb-3 fw-bold">Login</h4>

      <form @submit.prevent="handleLogin">
        <div class="mb-3">
          <label>Email</label>
          <input type="email" v-model="email" class="form-control" required>
        </div>

        <div class="mb-3">
          <label>Password</label>
          <input type="password" v-model="password" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100" :disabled="loading">
          {{ loading ? 'Logging in...' : 'Login' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useUserStore } from '@/stores/userStore'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const loading = ref(false)
const store = useUserStore()
const router = useRouter()

const handleLogin = async () => {
  loading.value = true
  const res = await store.login({ email: email.value, password: password.value })
  loading.value = false
  if (res.token) router.push('/')
  else alert(res.message || 'Invalid login')
}
</script>

<style scoped>
.auth-wrapper {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 15px;
  background: #f6f7fb;
}

.auth-card {
  width: 100%;
  max-width: 400px;
  padding: 30px;
  border-radius: 12px;
  background: #ffffff;
}

label {
  font-weight: 600;
  margin-bottom: 5px;
}

button {
  padding: 10px;
  font-size: 16px;
  font-weight: 600;
}
</style>