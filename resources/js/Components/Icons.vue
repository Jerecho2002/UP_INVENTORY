<script setup>
const props = defineProps({
  item: Object,
  actions: {
    type: Array,
    default: () => [
      { name: 'view', icon: 'fa-regular fa-eye'},
      { name: 'delete', icon: 'fa-solid fa-trash' },
      { name: 'edit', icon: 'fa-solid fa-pen-to-square'},
    ]
  }
})
</script>

<template>
  <div class="flex gap-2">
    <button 
      v-for="action in actions" :key="action.name" @click="action.handler(item.id)" class="hover:text-gray-500">
      <i 
        :class="[action.icon, {
          'text-red-500': action.name === 'delete',
          'text-blue-500': action.name === 'edit'
        }]"
      ></i>
    </button>
  </div>
</template>
