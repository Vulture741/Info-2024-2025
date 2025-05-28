<template>
  <div class="home">
    <h1>Notez</h1>
    <button @click="openEditor(null)">Nuova Nota</button>
    
    <TagFilter @filter="setFilterTag" />
    
    <div class="notes-grid">
      <NoteCard 
        v-for="note in filteredNotes" 
        :key="note.id"
        :note="note"
        @edit="openEditor"
        @delete="handleDelete"
      />
    </div>

    <NoteEditor
      v-if="showEditor"
      :note="currentNote"
      @save="saveNote"
      @close="closeEditor"
    />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useNotesStore } from '../stores/notes'
import NoteCard from '../components/NoteCard.vue'
import NoteEditor from '../components/NoteEditor.vue'
import TagFilter from '../components/TagFilter.vue'

const notesStore = useNotesStore()
const notes = ref([])
const showEditor = ref(false)
const currentNote = ref(null)
const filterTag = ref(null)

// Carica le note iniziali
notesStore.getAllNotes().then(data => notes.value = data)

const filteredNotes = computed(() => {
  if (!filterTag.value) return notes.value
  return notes.value.filter(note => 
    note.tags?.includes(filterTag.value)
})

const openEditor = (note) => {
  currentNote.value = note ? { ...note } : null
  showEditor.value = true
}

const closeEditor = () => {
  showEditor.value = false
}

const saveNote = async (noteData) => {
  if (noteData.id) {
    await notesStore.updateNote(noteData.id, noteData)
  } else {
    await notesStore.addNote(noteData)
  }
  notes.value = await notesStore.getAllNotes()
  closeEditor()
}

const handleDelete = async (id) => {
  await notesStore.deleteNote(id)
  notes.value = await notesStore.getAllNotes()
}

const setFilterTag = (tag) => {
  filterTag.value = tag
}
</script>

<style scoped>
.notes-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1rem;
  margin-top: 1rem;
}
</style>