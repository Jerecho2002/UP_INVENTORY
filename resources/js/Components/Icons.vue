<script setup>
const props = defineProps({
  item: Object,
  actions: {
    type: Array,
    default: () => [
      { name: 'view', icon: 'fa-regular fa-eye'},
      { name: 'edit', icon: 'fa-solid fa-pen-to-square'},
      { name: 'delete', icon: 'fa-solid fa-trash' },
    ]
  }
})
</script>

<template>
  <div class="flex gap-2">
    <button 
      v-for="action in actions" :key="action.name" @click="action.handler(item.id)" class="hover:text-gray-500 text-[20px]">
      <i 
        :class="[action.icon, {
          'text-red-500': action.name === 'delete',
          'text-[#54B3AB]': action.name === 'edit'
        }]"
      ></i>
    </button>
  </div>
</template>
