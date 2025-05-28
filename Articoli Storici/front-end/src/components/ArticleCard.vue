<template>
  <div class="card" @click="openArticle">
    <img :src="article.image_url" v-if="article.image_url" alt="Article image" />
    <div class="card-content">
      <h3>{{ article.title }}</h3>
    </div>
  </div>
</template>

<script setup>
  import { useRouter } from 'vue-router';
  
  //Assegna i props a una variabile
  const props = defineProps(['article']);
  const router = useRouter();
  
  function openArticle() {
    router.push(`/article/${props.article.id}`);
  }
</script>

<style scoped>
.card {
  background: linear-gradient(to bottom, #ffffff 0%, #f8f9fa 100%);
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  border: 1px solid rgba(0, 0, 0, 0.08);
  cursor: pointer;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  border-color: rgba(0, 113, 133, 0.2);
  background: linear-gradient(to bottom, #ffffff 0%, #e6f3f7 100%);
}

img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.card-content {
  padding: 1.5rem;
  background: linear-gradient(to bottom, transparent 0%, rgba(0, 113, 133, 0.02) 100%);
}

h3 {
  margin: 0;
  color: #16222b;
  font-size: 1.1rem;
  font-weight: 500;
  line-height: 1.4;
}

.card:hover h3 {
  color: #007185;
}
</style>
  