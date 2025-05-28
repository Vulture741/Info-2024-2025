<template>
  <div class="container">
    <div class="grid">
      <ArticleCard v-for="a in filteredArticles" :key="a.id" :article="a" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { searchQuery } from '../composables/useAuth';
import ArticleCard from '../components/ArticleCard.vue';

const articles = ref([]);

async function loadArticles() {
  const res = await fetch('http://localhost/backend-app/api/articles.php');
  articles.value = await res.json();
}

const filteredArticles = computed(() => {
  return articles.value.filter(a =>
    a.title.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

onMounted(loadArticles);
</script>

<style scoped>
.container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 2.5rem 1.5rem;
  min-height: 100vh;
  background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
  animation: fadeIn 0.6s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 768px) {
  .container {
    padding: 1.5rem 1rem;
  }
  
  .grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
}
</style>

