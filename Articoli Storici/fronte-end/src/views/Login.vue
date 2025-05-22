<template>
  <div class="login-box">
    <h2>Login</h2>
    <input v-model="username" placeholder="Username" />
    <input v-model="password" type="password" placeholder="Password" />
    <button @click="login">Accedi</button>
    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../composables/useAuth';

const username = ref('');
const password = ref('');
const error = ref('');
const router = useRouter();
const { loginUser } = useAuth();

async function login() {
  const res = await fetch('/api/api/auth.php?action=login', {
    method: 'POST',
    body: JSON.stringify({
      username: username.value,
      password: password.value
    }),
    headers: {
      'Content-Type': 'application/json'
    }
  });

  const data = await res.json();

  if (res.ok) {
    loginUser({ username: username.value, role: data.role });
    router.push('/home');
  } else {
    error.value = data.error;
  }
}
</script>

<style scoped>
.login-box {
  max-width: 300px;
  margin: 80px auto;
  padding: 30px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
input {
  width: 100%;
  padding: 10px;
  margin: 8px 0;
}
button {
  width: 100%;
  padding: 10px;
  background: #007bff;
  border: none;
  color: white;
  font-weight: bold;
}
.error {
  color: red;
  margin-top: 10px;
}
</style>
