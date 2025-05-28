<template>
  <div class="container">
    <h2>Modifica Articolo</h2>

    <form @submit.prevent="updateArticle">
      <input v-model="title" placeholder="Titolo" />
      <input v-model="image_url" placeholder="URL Immagine" />
      <textarea v-model="content" placeholder="Contenuto"></textarea>
      
      <div class="tags-section">
        <h3>Tags</h3>
        <div class="tags-container">
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

      <button type="submit">Salva Modifiche</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const title = ref('');
const content = ref('');
const image_url = ref('');
const selectedTags = ref([]);
const availableTags = ['WW2', 'Historical Figure', 'Event'];

async function loadArticle() {
  const res = await fetch(`http://localhost/backend-app/api/articles.php?id=${route.params.id}`);
  const data = await res.json();
  title.value = data.title;
  content.value = data.content;
  image_url.value = data.image_url;
  selectedTags.value = data.tags || [];
}

function toggleTag(tag) {
  const index = selectedTags.value.indexOf(tag);
  if (index === -1) {
    selectedTags.value.push(tag);
  } else {
    selectedTags.value.splice(index, 1);
  }
}

async function updateArticle() {
  await fetch(`http://localhost/backend-app/api/articles.php?id=${route.params.id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      title: title.value,
      content: content.value,
      image_url: image_url.value,
      tags: selectedTags.value
    })
  });
  router.push('/management');
}

onMounted(loadArticle);
</script>

<style scoped>
.container {
  max-width: 800px;
  margin: auto;
  padding: 2rem;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

input, textarea {
  padding: 12px;
  font-size: 1rem;
  border: 1px solid #ccc;
  border-radius: 6px;
}

textarea {
  min-height: 400px;
  resize: vertical;
}

button {
  background-color: #28a745;
  color: white;
  font-weight: bold;
  padding: 12px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.2s;
}

button:hover {
  background-color: #218838;
}

.tags-section {
  margin: 1rem 0;
}

.tags-section h3 {
  margin-bottom: 0.5rem;
  color: #232f3e;
}

.tags-container {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
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
</style>
  