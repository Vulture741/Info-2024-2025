<template>
  <div class="container">
    <div class="grid">
      <div
        class="card"
        v-for="a in filteredArticles"
        :key="a.id"
      >
        <h3>{{ a.title }}</h3>
        <img :src="a.image_url" v-if="a.image_url" />
        <p>{{ a.content }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useAuth } from '../composables/useAuth';

const articles = ref([]);
const { searchQuery } = useAuth();

async function loadArticles() {
  const res = await fetch('/api/api/articles.php');
  articles.value = await res.json();
}

const filteredArticles = computed(() => {
  const query = searchQuery.value.toLowerCase();
  return articles.value.filter(article =>
    article.title.toLowerCase().includes(query) ||
    article.content.toLowerCase().includes(query)
  );
});

onMounted(loadArticles);
</script>

<style scoped>
.container {
  max-width: 1280px;
  margin: auto;
  padding: 2rem;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1.5rem;
}

.card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  padding: 20px;
  transition: transform 0.2s ease;
}

.card:hover {
  transform: translateY(-3px);
}

.card h3 {
  font-size: 1.2rem;
  margin-bottom: 10px;
}

.card img {
  width: 100%;
  max-height: 200px;
  object-fit: cover;
  border-radius: 6px;
  margin-bottom: 10px;
}

.card p {
  font-size: 0.95rem;
  line-height: 1.4;
}
</style>

