<template>
  <div class="home-view">
    <div class="toolbar">
      <v-btn @click="showEditor = true" color="primary">
        Nuova Nota
      </v-btn>
      <tag-filter @filter="setFilterTag" />
    </div>

    <div class="notes-grid">
      <note-card 
        v-for="note in filteredNotes" 
        :key="note.id"
        :note="note"
        @edit="openEditor"
        @delete="deleteNote"
      />
    </div>

    <note-editor
      v-if="showEditor"
      :note="currentNote"
      @save="saveNote"
      @close="closeEditor"
    />
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { storeToRefs } from 'pinia';
import { useNotesStore } from '@/stores/notes';
import NoteCard from '@/components/NoteCard.vue';
import NoteEditor from '@/components/NoteEditor.vue';
import TagFilter from '@/components/TagFilter.vue';

const notesStore = useNotesStore();
const { getAllNotes, deleteNote: deleteStoreNote } = useNotesStore();

const { notes } = storeToRefs(notesStore);
const showEditor = ref(false);
const currentNote = ref(null);
const filterTag = ref(null);

const filteredNotes = computed(() => {
  if (!filterTag.value) return notes.value;
  return notes.value.filter(note => 
    note.tags?.includes(filterTag.value)
  );
});

const openEditor = (note) => {
  currentNote.value = { ...note };
  showEditor.value = true;
};

const closeEditor = () => {
  showEditor.value = false;
  currentNote.value = null;
};

const saveNote = async (note) => {
  if (note.id) {
    await notesStore.updateNote(note.id, note);
  } else {
    await notesStore.addNote({
      ...note,
      createdAt: new Date().toISOString()
    });
  }
  closeEditor();
};

const deleteNote = async (id) => {
  await deleteStoreNote(id);
};

const setFilterTag = (tag) => {
  filterTag.value = tag;
};

// Load initial data
notesStore.getAllNotes();
</script>

<style scoped>
.home-view {
  padding: 20px;
}

.toolbar {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}

.notes-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}
</style>