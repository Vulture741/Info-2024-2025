import { liveQuery } from 'dexie'
import { db } from '../db'

export const useNotes = () => {
  const getAllNotes = () => {
    return liveQuery(() => db.notes.toArray())
  }

  const getNotesByTag = (tag) => {
    return liveQuery(() => 
      db.notes.where('tags').equals(tag).toArray()
    )
  }

  const addNote = async (note) => {
    await db.notes.add({
      ...note,
      createdAt: new Date().toISOString()
    })
  }

  const updateNote = async (id, updates) => {
    await db.notes.update(id, updates)
  }

  const deleteNote = async (id) => {
    await db.notes.delete(id)
  }

  const getAllTags = () => {
    return liveQuery(async () => {
      const notes = await db.notes.toArray()
      const tags = new Set()
      notes.forEach(note => {
        note.tags?.forEach(tag => tags.add(tag))
      })
      return Array.from(tags)
    })
  }

  return {
    getAllNotes,
    getNotesByTag,
    addNote,
    updateNote,
    deleteNote,
    getAllTags
  }
}