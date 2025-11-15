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
  <div>
    <!-- Edit Button Slot -->
    <slot name="EditItemButton" :open="openModal"></slot>
  </div>

  <!-- Modal -->
  <div v-if="isOpen" class="fixed inset-0 flex items-center  justify-center z-50">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black opacity-50 backdrop-blur-md"></div>

    <!-- Modal Content -->
    <div
      class="bg-white w-full max-w-6xl rounded-lg drop-shadow-2xl p-4 relative overflow-y-auto animate-pop-in max-h-[90vh]"
    >
      <!-- Body -->
      <slot name="EditInventory" :closeModal="closeModal"></slot>
    </div>
  </div>
</template>
