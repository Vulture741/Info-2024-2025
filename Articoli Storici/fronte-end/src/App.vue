<template>
  <div>
    <NavBar v-if="user" />
    <router-view />
  </div>
</template>

<script setup>
import NavBar from './components/NavBar.vue';
import { useAuth } from './composables/useAuth';

const { user } = useAuth(); // 🔁 Rende la barra reattiva
</script>
