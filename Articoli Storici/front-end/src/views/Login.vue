<template>
  <div class="login-container">
    <div class="login-box">
      <h1 class="logo">Archivio Storico</h1>
      <div class="login-form">
        <h2>Accedi</h2>
        <input v-model="username" placeholder="Username" />
        <input v-model="password" type="password" placeholder="Password" />
        <button @click="login" class="login-btn">Accedi</button>
        <p v-if="error" class="error">{{ error }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  import { user } from '../composables/useAuth';
  
  const username = ref('');
  const password = ref('');
  const error = ref('');
  const router = useRouter();
  
  async function login() {
    try {
      const res = await fetch('http://localhost/backend-app/api/auth.php?action=login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ username: username.value, password: password.value })
      });
  
      const text = await res.text();
      const data = JSON.parse(text);
      console.log('LOGIN RAW:', text);
  
      if (res.ok) {
        sessionStorage.setItem('user', JSON.stringify(data));
        user.value = data;
        router.push('/home');
      } else {
        error.value = data.error || 'Errore login';
      }
    } catch (e) {
      error.value = '❌ Errore di rete o JSON';
    }
  }
</script>

<style scoped>
.login-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f3f3f3;
  padding: 2rem;
}

.login-box {
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

.login-form h2 {
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

.login-btn {
  width: 100%;
  padding: 0.8rem;
  background: #f08804;
  color: black;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.login-btn:hover {
  background: #f4a259;
}

.error {
  color: #c40000;
  margin-top: 1rem;
  text-align: center;
}
</style>
  