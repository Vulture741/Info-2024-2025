<!-- src/components/NoteCard.vue -->
<template>
  <v-card class="note-card">
    <v-card-title>{{ note.title }}</v-card-title>
    <v-card-text>
      <p class="note-content">{{ note.content }}</p>
      <div v-if="note.tags?.length" class="tags">
        <v-chip
          v-for="tag in note.tags"
          :key="tag"
          small
          class="mr-1 mb-1"
        >
          {{ tag }}
        </v-chip>
      </div>
    </v-card-text>
    <v-card-actions>
      <v-btn icon @click="$emit('edit', note)">
        <v-icon>mdi-pencil</v-icon>
      </v-btn>
      <v-btn icon @click="$emit('delete', note.id)">
        <v-icon>mdi-delete</v-icon>
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script setup>
defineProps({
  note: {
    type: Object,
    required: true
  }
});

defineEmits(['edit', 'delete']);
</script>

<style scoped>
.note-card {
  height: 100%;
  display: flex;
  flex-direction: column;
}

.note-content {
  white-space: pre-line;
}

.tags {
  margin-top: 10px;
}
</style> 