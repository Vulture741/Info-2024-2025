import { defineStore } from 'pinia'
import { useNotes } from '../composables/useNotes'

export const useNotesStore = defineStore('notes', () => {
  const { 
    getAllNotes, 
    addNote, 
    updateNote, 
    deleteNote, 
    getNotesByTag,
    getAllTags 
  } = useNotes()

  return { 
    getAllNotes, 
    addNote, 
    updateNote, 
    deleteNote,
    getNotesByTag,
    getAllTags
  }
})