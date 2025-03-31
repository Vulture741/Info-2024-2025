<!-- src/components/NoteEditor.vue -->
<template>
  <v-dialog v-model="show" max-width="600px" persistent>
    <v-card>
      <v-card-title>
        {{ note.id ? 'Modifica Nota' : 'Nuova Nota' }}
      </v-card-title>
      <v-card-text>
        <v-text-field
          v-model="editableNote.title"
          label="Titolo"
          required
        ></v-text-field>
        <v-textarea
          v-model="editableNote.content"
          label="Contenuto"
          rows="5"
          required
        ></v-textarea>
        <tag-selector v-model="editableNote.tags" />
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close">
          Annulla
        </v-btn>
        <v-btn color="blue darken-1" text @click="save">
          Salva
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { ref, watch } from 'vue';
import TagSelector from './TagSelector.vue';

const props = defineProps({
  note: {
    type: Object,
    default: () => ({
      title: '',
      content: '',
      tags: []
    })
  },
  show: {
    type: Boolean,
    required: true
  }
});

const emit = defineEmits(['save', 'close']);

const editableNote = ref({ ...props.note });

watch(() => props.note, (newVal) => {
  editableNote.value = { ...newVal };
});

const save = () => {
  emit('save', editableNote.value);
};

const close = () => {
  emit('close');
};
</script>