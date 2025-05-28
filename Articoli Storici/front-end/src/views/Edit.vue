<template>
  <div class="container">
    <h2>Modifica Articolo</h2>

    <form @submit.prevent="updateArticle">
      <input v-model="title" placeholder="Titolo" />
      <input v-model="image_url" placeholder="URL Immagine" />
      <textarea v-model="content" placeholder="Contenuto"></textarea>
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

  async function loadArticle() {
    const res = await fetch(`http://localhost/backend-app/api/articles.php?id=${route.params.id}`);
    const data = await res.json();
    title.value = data.title;
    content.value = data.content;
    image_url.value = data.image_url;
  }

  async function updateArticle() {
    await fetch(`http://localhost/backend-app/api/articles.php?id=${route.params.id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        title: title.value,
        content: content.value,
        image_url: image_url.value
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
</style>
  