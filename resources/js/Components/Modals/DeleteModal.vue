<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  deleteUrl: {
    type: String,
    required: true,
  },
  message: {
    type: String,
    default: "Are you sure you want to delete this item?",
  },
});

const isOpen = ref(false);

function openModal() {
  isOpen.value = true;
}

function closeModal() {
  isOpen.value = false;
}

// ✅ Delete function using Inertia
function confirmDelete() {
  router.delete(props.deleteUrl, {
    preserveScroll: true,
    onSuccess: () => {
      closeModal();
      console.log("Item deleted successfully!");
    },
  });
}

defineExpose({ openModal, closeModal });
</script>

<template>
    <!-- Trigger Button -->
    <slot name="DeleteItemButton" :open="openModal"></slot>

    <!-- Modal -->
    <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white w-[90%] max-w-md rounded-lg shadow-2xl p-6 relative animate-fadeIn">
            <!-- Close (X) -->
            <button @click="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <!-- Slot for content -->
            <slot name="DeleteItem" :close="closeModal" :confirm="confirmDelete" :message="props.message"></slot>
        </div>
    </div>
</template>