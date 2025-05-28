<template>
  <div class="register-container">
    <div class="register-box">
      <h1 class="logo">Archivio Storico</h1>
      <div class="register-form">
        <h2>Registrazione</h2>
        <input v-model="username" placeholder="Username" />
        <input v-model="password" type="password" placeholder="Password" />
        <input v-model="confirmPassword" type="password" placeholder="Conferma Password" />
        <button @click="register" class="register-btn" :disabled="!isValid">Registrati</button>
        <p v-if="error" class="error">{{ error }}</p>
        <router-link to="/login" class="login-link">Hai già un account? Accedi</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';

const username = ref('');
const password = ref('');
const confirmPassword = ref('');
const error = ref('');
const router = useRouter();

const isValid = computed(() => 
  username.value.length >= 3 && 
  password.value.length >= 6 && 
  password.value === confirmPassword.value
);

async function register() {
  if (!isValid.value) return;

  try {
    const res = await fetch('http://localhost/backend-app/api/auth.php?action=register', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ 
        username: username.value, 
        password: password.value 
      })
    });

    const data = await res.json();

    if (res.ok) {
      router.push('/login');
    } else {
      error.value = data.error || 'Errore durante la registrazione';
    }
  } catch (e) {
    error.value = '❌ Errore di rete';
  }
}
</script>

<style scoped>
.register-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f3f3f3;
  padding: 2rem;
}

.register-box {
  width: 100%;
  max-width: 400px;
  background: white;
  border-radius: 8px;
  padding: 2rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.logo {
  text-align: center;
  font-size: 2rem;
  margin-bottom: 2rem;
  color: #131921;
}

.register-form h2 {
  margin-bottom: 1.5rem;
  color: #0F1111;
}

input {
  width: 100%;
  padding: 0.8rem;
  margin-bottom: 1rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

input:focus {
  border-color: #f08804;
  outline: none;
}

.register-btn {
  width: 100%;
  padding: 0.8rem;
  background: #f08804;
  color: black;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s;
}

.register-btn:hover:not(:disabled) {
  background: #f4a460;
}

.register-btn:disabled {
  background: #ddd;
  cursor: not-allowed;
}

.error {
  color: #d00;
  margin-top: 1rem;
  text-align: center;
}

.login-link {
  display: block;
  text-align: center;
  margin-top: 1rem;
  color: #007185;
  text-decoration: none;
}

.login-link:hover {
  text-decoration: underline;
}
</style>