<template>
  <div class="card">
    <img v-if="article.image_url" :src="article.image_url" alt="img" />
    <h3>{{ article.title }}</h3>
    <p>{{ article.content }}</p>
  </div>
</template>

<script setup>
defineProps(['article']);
</script>
