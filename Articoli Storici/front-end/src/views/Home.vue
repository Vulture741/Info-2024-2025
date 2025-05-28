<template>
  <div class="container">
    <div class="tag-filters">
      <span 
        v-for="tag in availableTags" 
        :key="tag"
        :class="['filter-tag', { active: selectedTags.includes(tag) }]"
        @click="toggleTag(tag)"
      >
        {{ tag }}
      </span>
    </div>
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
const selectedTags = ref([]);
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

const filteredArticles = computed(() => {
  return articles.value.filter(a => {
    const matchesSearch = a.title.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesTags = selectedTags.value.length === 0 || 
      (a.tags && a.tags.some(tag => selectedTags.value.includes(tag)));
    return matchesSearch && matchesTags;
  });
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

.tag-filters {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  margin-bottom: 2rem;
}

.filter-tag {
  background-color: #f0f2f2;
  color: #232f3e;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.filter-tag:hover {
  background-color: #e3e6e6;
}

.filter-tag.active {
  background-color: #232f3e;
  color: white;
}
</style>

