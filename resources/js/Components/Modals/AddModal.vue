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
    <slot name="AddItemButton"></slot>
  </div>

  <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-4xl rounded-lg drop-shadow-2xl p-6 relative animate-fadeIn">
      <!-- Wrap the slot content in a form event listener -->
      <slot name="InventoryForm"></slot>
    </div>
  </div>
</template>
