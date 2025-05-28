<template>
  <div class="editor-modal">
    <div class="editor-content">
      <h2>{{ note ? 'Modifica Nota' : 'Nuova Nota' }}</h2>
      
      <input v-model="form.title" placeholder="Titolo">
      
      <textarea 
        v-model="form.content" 
        placeholder="Scrivi qui..."
        rows="5"
      ></textarea>
      
      <TagSelector v-model="form.tags" />
      
      <div class="editor-actions">
        <button @click="$emit('close')">Annulla</button>
        <button @click="save">Salva</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import TagSelector from './TagSelector.vue'

const props = defineProps({
  note: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['save', 'close'])

const form = ref({
  title: '',
  content: '',
  tags: []
})

watch(() => props.note, (newVal) => {
  if (newVal) {
    form.value = { ...newVal }
  } else {
    form.value = { title: '', content: '', tags: [] }
  }
}, { immediate: true })

const save = () => {
  emit('save', form.value)
}
</script>

<style scoped>
.editor-modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}
.editor-content {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  width: 100%;
  max-width: 500px;
}
textarea {
  width: 100%;
  margin: 1rem 0;
}
.editor-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}
</style>