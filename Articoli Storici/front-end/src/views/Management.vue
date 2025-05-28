<template>
  <div class="management">
    <div class="header">
      <h1>Gestione Articoli</h1>
    </div>

    <div class="content">
      <form @submit.prevent="addArticle" class="form">
        <h2>Aggiungi Nuovo Articolo</h2>
        <div class="form-group">
          <input v-model="title" placeholder="Titolo" required />
          <input v-model="image_url" placeholder="URL Immagine" />
          <textarea v-model="content" placeholder="Contenuto" required></textarea>
          <div class="tags-input">
            <div class="available-tags">
              <span 
                v-for="tag in availableTags" 
                :key="tag"
                :class="['tag', { selected: selectedTags.includes(tag) }]"
                @click="toggleTag(tag)"
              >
                {{ tag }}
              </span>
            </div>
          </div>
          <button type="submit" class="submit-btn">Aggiungi Articolo</button>
        </div>
      </form>

      <div class="articles-grid">
        <div class="article-card" v-for="a in articles" :key="a.id">
          <img :src="a.image_url" v-if="a.image_url" alt="Article image" />
          <div class="article-content">
            <h3>{{ a.title }}</h3>
            <p>{{ a.content.substring(0, 100) }}...</p>
            <div class="article-tags">
              <span v-for="tag in a.tags" :key="tag" class="tag">{{ tag }}</span>
            </div>
            <div class="article-actions">
              <button @click="editArticle(a)" class="edit-btn">Modifica</button>
              <button @click="removeArticle(a.id)" class="delete-btn">Elimina</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';

const articles = ref([]);
const title = ref('');
const content = ref('');
const image_url = ref('');
const selectedTags = ref([]);
const router = useRouter();

const availableTags = ['WW2', 'Historical Figure', 'Event'];

async function loadArticles() {
  const res = await fetch('http://localhost/backend-app/api/articles.php');
  articles.value = await res.json();
}

function toggleTag(tag) {
  const index = selectedTags.value.indexOf(tag);
  if (index === -1) {
    selectedTags.value.push(tag);
  } else {
    selectedTags.value.splice(index, 1);
  }
}

async function addArticle() {
  await fetch('http://localhost/backend-app/api/articles.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      title: title.value,
      content: content.value,
      image_url: image_url.value,
      tags: selectedTags.value
    })
  });
  title.value = '';
  content.value = '';
  image_url.value = '';
  selectedTags.value = [];
  await loadArticles();
}

async function removeArticle(id) {
  if (confirm('Eliminare questo articolo?')) {
    await fetch(`http://localhost/backend-app/api/articles.php?id=${id}`, {
      method: 'DELETE'
    });
    await loadArticles();
  }
}

function editArticle(article) {
  router.push(`/edit/${article.id}`);
}

onMounted(loadArticles);
</script>

<style scoped>
.management {
  min-height: 100vh;
  background-color: #f3f3f3;
}

.header {
  background: white;
  padding: 1.5rem 2rem;
  border-bottom: 1px solid #ddd;
  margin-bottom: 2rem;
}

.header h1 {
  color: #131921;
  font-size: 1.8rem;
}

.content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
}

.form {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  margin-bottom: 2rem;
  box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}

.form h2 {
  margin-bottom: 1.5rem;
  color: #0F1111;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

input, textarea {
  padding: 0.8rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
}

textarea {
  min-height: 150px;
  resize: vertical;
}

.submit-btn {
  background: #f08804;
  color: black;
  padding: 0.8rem;
  border: none;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.submit-btn:hover {
  background: #f4a259;
}

.articles-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 2rem;
  margin-bottom: 2rem;
}

.article-card {
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}

.article-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.article-content {
  padding: 1.5rem;
}

.article-content h3 {
  margin: 0 0 1rem 0;
  color: #0F1111;
}

.article-content p {
  color: #565959;
  margin-bottom: 1rem;
  line-height: 1.5;
}

.article-actions {
  display: flex;
  gap: 1rem;
}

.edit-btn, .delete-btn {
  flex: 1;
  padding: 0.8rem;
  border: none;
  border-radius: 4px;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.2s;
}

.edit-btn {
  background: #FFD814;
  color: black;
}

.edit-btn:hover {
  background: #F7CA00;
}

.delete-btn {
  background: #c40000;
  color: white;
}

.delete-btn:hover {
  background: #d91313;
}
.tags-input {
  margin: 1rem 0;
}

.available-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.tag {
  background-color: #f0f2f2;
  color: #232f3e;
  padding: 0.4rem 1rem;
  border-radius: 15px;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.tag:hover {
  background-color: #e3e6e6;
}

.tag.selected {
  background-color: #232f3e;
  color: white;
}

.article-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin: 0.5rem 0;
}

.article-tags .tag {
  font-size: 0.8rem;
  padding: 0.3rem 0.8rem;
  cursor: default;
}
</style>