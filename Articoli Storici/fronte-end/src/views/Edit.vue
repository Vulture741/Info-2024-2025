<template>
  <div class="container">
    <h2>Modifica Articolo</h2>

    <form v-if="loaded" @submit.prevent="updateArticle" class="form">
      <input v-model="title" placeholder="Titolo" required />
      <input v-model="image_url" placeholder="URL Immagine" />
      <textarea v-model="content" placeholder="Contenuto" required></textarea>
      <button type="submit">Salva Modifiche</button>
    </form>

    <div v-else class="loading">Caricamento articolo...</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const id = route.params.id;

const title = ref('');
const content = ref('');
const image_url = ref('');
const loaded = ref(false);

onMounted(async () => {
  try {
    const res = await fetch(`/api/api/articles.php?id=${id}`);
    const data = await res.json();
    title.value = data.title;
    content.value = data.content;
    image_url.value = data.image_url;
    loaded.value = true;
  } catch (err) {
    alert('Errore nel caricamento dell’articolo');
  }
});

async function updateArticle() {
  const res = await fetch(`/api/api/articles.php?id=${id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      title: title.value,
      content: content.value,
      image_url: image_url.value
    })
  });

  if (res.ok) {
    alert('Articolo modificato con successo!');
    router.push('/management');
  } else {
    alert('Errore nel salvataggio.');
  }
}
</script>

<style scoped>
.container {
  max-width: 600px;
  margin: auto;
  padding: 2rem;
  background: white;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

input, textarea {
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 16px;
}

textarea {
  min-height: 100px;
}

button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 12px;
  font-weight: bold;
  border-radius: 6px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}

.loading {
  text-align: center;
  font-size: 1.1rem;
  color: #555;
}
</style>
