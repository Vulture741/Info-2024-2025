<template>
  <div class="container" v-if="article">
    <h1>{{ article.title }}</h1>
    <div class="tags">
      <span v-for="tag in article.tags" :key="tag" class="tag">{{ tag }}</span>
    </div>
    <img v-if="article.image_url" :src="article.image_url" />
    <p class="article-content">{{ article.content }}</p>
    
    <div class="comments-section">
      <h2>Commenti</h2>
      
      <div class="comment-form" v-if="user">
        <textarea 
          v-model="newComment" 
          placeholder="Scrivi un commento..."
          rows="3"
        ></textarea>
        <button @click="addComment" :disabled="!newComment.trim()">Pubblica</button>
      </div>
      
      <div v-else class="login-prompt">
        <router-link to="/login">Accedi per commentare</router-link>
      </div>

      <div class="comments-list">
        <div v-for="comment in comments" :key="comment.id" class="comment">
          <div class="comment-header">
            <span class="username">{{ comment.username }}</span>
            <span class="date">{{ formatDate(comment.created_at) }}</span>
          </div>
          <p class="comment-content">{{ comment.content }}</p>
          <button 
            v-if="user && (user.id === comment.user_id || user.role === 'admin')"
            @click="deleteComment(comment.id)"
            class="delete-btn"
          >
            Elimina
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { user } from '../composables/useAuth';

const article = ref(null);
const comments = ref([]);
const newComment = ref('');
const route = useRoute();

async function loadArticle() {
  const res = await fetch(`http://localhost/backend-app/api/articles.php?id=${route.params.id}`);
  article.value = await res.json();
  await loadComments();
}

async function loadComments() {
  const res = await fetch(`http://localhost/backend-app/api/comments.php?article_id=${route.params.id}`);
  comments.value = await res.json();
}

async function addComment() {
  if (!newComment.value.trim()) return;

  await fetch('http://localhost/backend-app/api/comments.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      article_id: route.params.id,
      user_id: user.value.id,
      username: user.value.username,
      content: newComment.value
    })
  });

  newComment.value = '';
  await loadComments();
}

async function deleteComment(commentId) {
  if (!confirm('Sei sicuro di voler eliminare questo commento?')) return;

  await fetch(`http://localhost/backend-app/api/comments.php?id=${commentId}`, {
    method: 'DELETE'
  });

  await loadComments();
}

function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString('it-IT', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
}

onMounted(loadArticle);
</script>

<style scoped>
.container {
  max-width: 800px;
  margin: auto;
  padding: 2rem;
}

.article-content {
  line-height: 1.6;
  margin-bottom: 3rem;
}

img {
  max-width: 100%;
  margin-bottom: 1rem;
  border-radius: 6px;
}

.comments-section {
  margin-top: 3rem;
  border-top: 1px solid #eee;
  padding-top: 2rem;
}

.comments-section h2 {
  margin-bottom: 1.5rem;
  color: #333;
}

.comment-form {
  margin-bottom: 2rem;
}

textarea {
  width: 100%;
  padding: 0.8rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-bottom: 1rem;
  font-size: 1rem;
  resize: vertical;
}

button {
  background: #f08804;
  color: black;
  border: none;
  padding: 0.8rem 1.5rem;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
}

button:disabled {
  background: #ddd;
  cursor: not-allowed;
}

.login-prompt {
  text-align: center;
  margin: 2rem 0;
}

.login-prompt a {
  color: #007185;
  text-decoration: none;
}

.login-prompt a:hover {
  text-decoration: underline;
}

.comment {
  background: #f9f9f9;
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 1rem;
  position: relative;
}

.comment-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.5rem;
  color: #666;
}

.username {
  font-weight: 500;
  color: #333;
}

.date {
  font-size: 0.9rem;
}

.comment-content {
  line-height: 1.4;
}

.delete-btn {
  position: absolute;
  right: 1rem;
  bottom: 1rem;
  background: #dc3545;
  color: white;
  padding: 0.4rem 0.8rem;
  font-size: 0.9rem;
}

.delete-btn:hover {
  background: #c82333;
}
</style>

<style scoped>
.tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin: 1rem 0;
}

.tag {
  background-color: #f0f2f2;
  color: #007185;
  padding: 0.4rem 1rem;
  border-radius: 20px;
  font-size: 0.9rem;
}
</style>
  