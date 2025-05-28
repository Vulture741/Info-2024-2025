<template>
  <div>
    <NavBar v-if="!isLoginPage" />
    <router-view />
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import NavBar from './components/NavBar.vue';

const route = useRoute();
const isLoginPage = computed(() => route.path === '/login' || route.path === '/register');
</script>
