<template>
  <div class="container">
    <h1>Area Admin: Gestione Articoli</h1>

    <form @submit.prevent="addArticle" class="form">
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
        <button @click="editArticle(a)">Modifica</button>
        <button @click="confirmDelete(a.id)">Elimina</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
const router = useRouter();
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
    body: JSON.stringify({
      title: title.value,
      content: content.value,
      image_url: image_url.value
    }),
    headers: { 'Content-Type': 'application/json' }
  });
  await loadArticles();
  title.value = '';
  content.value = '';
  image_url.value = '';
}

function editArticle(article) {
  router.push(`/edit/${article.id}`);
}

function confirmDelete(id) {
  if (confirm('Sei sicuro di voler eliminare questo articolo?')) {
    fetch(`/api/api/articles.php?id=${id}`, {
      method: 'DELETE'
    }).then(() => loadArticles());
  }
}

onMounted(loadArticles);
</script>

.form {
  display: flex;
  flex-direction: column;
  gap: 12px;
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  margin-bottom: 2rem;
}

input, textarea {
  padding: 10px 14px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 6px;
  outline: none;
}

textarea {
  resize: vertical;
  min-height: 100px;
}

button {
  background-color: #ff9900;
  color: #111;
  font-weight: bold;
  border: none;
  padding: 12px;
  border-radius: 6px;
  cursor: pointer;
}

button:hover {
  background-color: #e68a00;
}


