<template>
    <div class="container" v-if="article">
      <h1>{{ article.title }}</h1>
      <img v-if="article.image_url" :src="article.image_url" />
      <p>{{ article.content }}</p>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { useRoute } from 'vue-router';
  
  const article = ref(null);
  const route = useRoute();
  
  async function loadArticle() {
    const res = await fetch(`http://localhost/backend-app/api/articles.php?id=${route.params.id}`);
    article.value = await res.json();
  }
  
  onMounted(loadArticle);
  </script>
  
  <style scoped>
  .container {
    max-width: 720px;
    margin: auto;
    padding: 2rem;
  }
  img {
    max-width: 100%;
    margin-bottom: 1rem;
    border-radius: 6px;
  }
  p {
    line-height: 1.6;
  }
  </style>
  