<script setup>
import { ref } from "vue";

const isOpen = ref(false);

function openModal() {
  isOpen.value = true;
}

function closeModal() {
  isOpen.value = false;
}

defineExpose({ openModal, closeModal });
</script>

<template>
  <!-- Pass openModal to the slot -->
 <div>
   <slot name="ViewItemButton" :open="openModal"></slot>
 </div>
 <div>
    <slot name="ViewAckButton" :open="openModal"></slot>
 </div>

  <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="absolute inset-0 bg-black opacity-50 backdrop-blur-md"></div>
    <div class="bg-white w-[90%] max-w-md rounded-lg shadow-2xl  p-6 relative animate-fadeIn">
      <button @click="closeModal" class="absolute top-4 right-4 flex items-center justify-center 
         w-7 h-7 rounded-full bg-black text-white 
         hover:bg-gray-800 transition duration-200 focus:outline-none">
        <i class="fa-solid fa-xmark text-base"></i>
      </button>

      <!-- BODY CONTENT MODAL -->
      <slot name="ViewBodyContent" :close="closeModal"></slot>
    </div>
  </div>
</template>