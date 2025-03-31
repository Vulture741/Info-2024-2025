// src/stores/notes.js
import { defineStore } from 'pinia';
import { useNotes } from '@/composables/useNotes';

export const useNotesStore = defineStore('notes', () => {
  const { 
    getAllNotes, 
    getNotesByTag,
    addNote,
    updateNote,
    deleteNote,
    getAllTags
  } = useNotes();

  return {
    getAllNotes,
    getNotesByTag,
    addNote,
    updateNote,
    deleteNote,
    getAllTags
  };
});