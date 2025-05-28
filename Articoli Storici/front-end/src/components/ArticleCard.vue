<template>
  <div class="card" @click="openArticle">
    <img :src="article.image_url" v-if="article.image_url" alt="Article image" />
    <div class="card-content">
      <h3>{{ article.title }}</h3>
      <div class="tags">
        <span v-for="tag in article.tags" :key="tag" class="tag">{{ tag }}</span>
      </div>
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

.tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-top: 0.8rem;
}

.tag {
  background-color: #f0f2f2;
  color: #007185;
  padding: 0.3rem 0.8rem;
  border-radius: 15px;
  font-size: 0.8rem;
  transition: all 0.2s ease;
}

.tag:hover {
  background-color: #e3e6e6;
}
</style>
  