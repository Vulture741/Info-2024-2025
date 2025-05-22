<template>
  <nav class="navbar">
    <div class="navbar-left">
      <img src="/logo.png" alt="Logo" class="logo" />
    </div>

    <div class="navbar-center">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Cerca articoli..."
        class="search-bar"
      />
    </div>

    <div class="navbar-right">
      <router-link to="/home">Home</router-link>
      <router-link v-if="user?.role === 'admin'" to="/management">Gestione</router-link>
      <button @click="logout">Logout</button>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue';
import { useAuth } from '../composables/useAuth';
import { useRouter } from 'vue-router';

const { user, logoutUser, searchQuery } = useAuth();
const router = useRouter();

function logout() {
  fetch('/api/api/auth.php?action=logout');
  logoutUser();
  router.push('/login');
}
</script>

<style scoped>
.navbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: #232f3e;
  padding: 12px 20px;
  color: white;
  position: sticky;
  top: 0;
  z-index: 100;
}

.navbar-left .logo {
  height: 45px;
}

.navbar-center {
  flex: 1;
  margin: 0 20px;
  display: flex;
  justify-content: center;
}

.search-bar {
  width: 100%;
  max-width: 550px;
  padding: 10px 14px;
  border-radius: 4px;
  border: none;
  font-size: 16px;
  outline: none;
}

.navbar-right {
  display: flex;
  align-items: center;
  gap: 20px;
}

a, button {
  color: white;
  font-size: 16px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 6px 10px;
  transition: background 0.2s;
}

a:hover, button:hover {
  background-color: rgba(255, 255, 255, 0.15);
  border-radius: 4px;
}
</style>
