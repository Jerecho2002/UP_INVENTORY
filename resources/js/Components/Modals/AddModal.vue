<script setup>
import { ref } from "vue";

const isOpen = ref(false);

function openModal() {
  isOpen.value = true;
}

function closeModal() {
  isOpen.value = false;
}

function submitItem() {
  // handle form submission (emit event or save to backend)
  alert("Item added successfully!");
  closeModal();
}

defineExpose({ openModal, closeModal });  
</script>

<template>
    <div>
    <!-- Add Item Button -->
        <slot name="AddItemButton"></slot>
        
    </div>
    <!-- Modal -->
    <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-4xl rounded-lg drop-shadow-2xl p-6 relative animate-fadeIn">
            <!-- <button @click="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button> -->
              

                <!-- Body -->
                <slot name="InventoryForm"></slot>
        </div>
    </div>
</template>