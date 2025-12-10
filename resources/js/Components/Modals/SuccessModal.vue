<script setup>
    
defineProps({
  title: { type: String, default: "Success" },
  message: { type: String, default: "Operation completed successfully!" }
});

defineEmits(['close']);

</script>
<template>
  <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-[350px] text-center shadow-lg animate-fade-in">
      
      <!-- Icon -->
      <div class="text-green-600 text-5xl mb-3">
        <i class="fa-solid fa-circle-check"></i>
      </div>

      <!-- Title -->
      <h2 class="text-xl font-bold mb-2">{{ title }}</h2>

      <!-- Message -->
      <p class="text-gray-700 mb-5">{{ message }}</p>

      <!-- Button -->
      <button 
        @click="$emit('close')" 
        class="bg-[#0E6021] text-white px-6 py-2 rounded-full font-semibold hover:bg-green-800">
        OK
      </button>
    </div>
  </div>
</template>