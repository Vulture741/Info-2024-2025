<template>
  <nav class="navbar">
    <router-link to="/home" class="logo">
      <img src="../logo/Logo.png" alt="Logo" class="logo-image" />
    </router-link>
    <div class="search-bar">
      <input type="text" v-model="searchQuery" placeholder="Cerca articoli..." />
      <span class="search-icon">🔍</span>
    </div>
    <div class="nav-right">
      <router-link v-if="user?.role === 'admin'" to="/management" class="nav-link">Gestione</router-link>
      <button @click="handleLogout" class="logout-btn">Esci</button>
    </div>
  </nav>
</template>

<script setup>
  import { computed } from 'vue';
  import { useRouter } from 'vue-router';
  import { logout, user, searchQuery } from '../composables/useAuth';
  
  const router = useRouter();
  
  function handleLogout() {
    logout();
    router.push('/login');
  }
</script>

<style scoped>
.navbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background-color: #131921;
  padding: 0.8rem 2rem;
  gap: 2rem;
  position: sticky;
  top: 0;
  z-index: 100;
}

.logo {
  text-decoration: none;
  min-width: 120px;
}

.logo-image {
  height: 40px;
  width: auto;
  object-fit: contain;
}

.search-bar {
  flex: 0 1 800px;
  position: relative;
  margin: 0 auto;
}

.search-bar input {
  width: 100%;
  padding: 0.8rem 2.5rem 0.8rem 1rem;
  border-radius: 4px;
  border: none;
  font-size: 1rem;
}

.search-icon {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
}

.nav-right {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-left: auto;
  white-space: nowrap;
}

.nav-link {
  color: white;
  text-decoration: none;
  font-weight: 500;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.nav-link:hover {
  background-color: #232f3e;
}

.logout-btn {
  background: #f08804;
  color: black;
  border: none;
  padding: 0.5rem 1.5rem;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.logout-btn:hover {
  background: #f4a259;
}

@media (max-width: 768px) {
  .navbar {
    padding: 0.8rem 1rem;
    gap: 1rem;
  }

  .nav-right {
    gap: 0.5rem;
  }

  .nav-link,
  .logout-btn {
    padding: 0.5rem 0.8rem;
  }
}
</style>