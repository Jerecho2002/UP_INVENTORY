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
  <slot name="ViewItemButton" :open="openModal"></slot>

  <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="bg-white w-[90%] max-w-md rounded-lg shadow-2xl  p-6 relative animate-fadeIn">
      <button @click="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-lg">
        <i class="fa-solid fa-xmark"></i>
      </button>

      <h2 class="text-lg font-bold text-gray-800 mb-4">Item Details</h2>

      <p class="text-sm text-gray-600">
        This is your item view modal content.
      </p>
    </div>
  </div>
</template>