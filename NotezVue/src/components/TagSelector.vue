<template>
    <div class="tag-selector">
      <input 
        v-model="newTag"
        @keydown.enter="addTag"
        placeholder="Aggiungi tag"
      >
      <div class="selected-tags">
        <span v-for="(tag, index) in modelValue" :key="index" class="tag">
          {{ tag }}
          <button @click="removeTag(index)">×</button>
        </span>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const props = defineProps({
    modelValue: {
      type: Array,
      default: () => []
    }
  })
  
  const emit = defineEmits(['update:modelValue'])
  
  const newTag = ref('')
  
  const addTag = () => {
    if (newTag.value.trim() && !props.modelValue.includes(newTag.value.trim())) {
      emit('update:modelValue', [...props.modelValue, newTag.value.trim()])
      newTag.value = ''
    }
  }
  
  const removeTag = (index) => {
    const tags = [...props.modelValue]
    tags.splice(index, 1)
    emit('update:modelValue', tags)
  }
  </script>
  
  <style scoped>
  .tag {
    background: #eee;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    margin-right: 0.5rem;
    display: inline-block;
  }
  .selected-tags {
    margin-top: 0.5rem;
  }
  </style>