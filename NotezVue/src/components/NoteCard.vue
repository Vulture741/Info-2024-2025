<template>
  <div class="note-card">
    <h3>{{ note.title }}</h3>
    <p>{{ note.content }}</p>
    <div v-if="note.tags?.length" class="tags">
      <span v-for="tag in note.tags" :key="tag" class="tag">
        {{ tag }}
      </span>
    </div>
    <div class="actions">
      <button @click="$emit('edit', note)">✏️</button>
      <button @click="$emit('delete', note.id)">🗑️</button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  note: {
    type: Object,
    required: true
  }
})

defineEmits(['edit', 'delete'])
</script>

<style scoped>
.note-card {
  border: 1px solid #ddd;
  padding: 1rem;
  border-radius: 8px;
}
.tags {
  margin-top: 0.5rem;
}
.tag {
  background: #eee;
  padding: 0.2rem 0.5rem;
  border-radius: 4px;
  margin-right: 0.5rem;
  font-size: 0.8rem;
}
.actions {
  margin-top: 1rem;
}
</style>