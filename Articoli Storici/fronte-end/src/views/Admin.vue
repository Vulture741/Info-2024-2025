<template>
  <div class="container">
    <h1>Gestione Articoli</h1>

    <form @submit.prevent="addArticle">
      <input v-model="title" placeholder="Titolo" required />
      <input v-model="image_url" placeholder="URL Immagine" />
      <textarea v-model="content" placeholder="Contenuto" required></textarea>
      <button type="submit">Aggiungi Articolo</button>
    </form>

    <div class="grid">
      <div class="card" v-for="a in articles" :key="a.id">
        <h3>{{ a.title }}</h3>
        <img :src="a.image_url" v-if="a.image_url" />
        <p>{{ a.content }}</p>
        <button @click="removeArticle(a.id)">Elimina</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const title = ref('');
const content = ref('');
const image_url = ref('');
const articles = ref([]);

async function loadArticles() {
  const res = await fetch('/api/api/articles.php');
  articles.value = await res.json();
}

async function addArticle() {
  await fetch('/api/api/articles.php', {
    method: 'POST',
    body: JSON.stringify({ title: title.value, content: content.value, image_url: image_url.value }),
    headers: { 'Content-Type': 'application/json' }
  });
  await loadArticles();
}

async function removeArticle(id) {
  await fetch(`/api/api/articles.php?id=${id}`, { method: 'DELETE' });
  await loadArticles();
}

onMounted(loadArticles);
</script>
