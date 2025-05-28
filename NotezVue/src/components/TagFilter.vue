<template>
    <div class="tag-filter">
      <select v-model="selectedTag" @change="emitFilter">
        <option value="">Tutte le note</option>
        <option v-for="tag in tags" :key="tag" :value="tag">
          {{ tag }}
        </option>
      </select>
    </div>
  </template>
  
  <script setup>
  import { ref, watch } from 'vue'
  import { useNotesStore } from '../stores/notes'
  
  const notesStore = useNotesStore()
  const tags = ref([])
  const selectedTag = ref('')
  
  // Carica i tag disponibili
  notesStore.getAllTags().then(data => tags.value = data)
  
  const emitFilter = () => {
    emit('filter', selectedTag.value)
  }
  
  defineEmits(['filter'])
  </script>