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
       <div class="absolute inset-0 bg-black opacity-50 backdrop-blur-md"></div>
        <div class="bg-white w-[60rem] max-w-md rounded-lg shadow-2xl p-6 relative animate-fadeIn">
            <slot name="DeleteItem" :close="closeModal" :confirm="confirmDelete" :message="props.message"></slot>
        </div>
    </div>

</template>