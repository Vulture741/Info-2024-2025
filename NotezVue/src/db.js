import Dexie from 'dexie'

export const db = new Dexie('NotezDB')

db.version(1).stores({
  notes: '++id, title, content, tags, createdAt'
})