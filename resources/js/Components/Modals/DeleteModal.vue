<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  deleteUrl: String,
  message: {
    type: String,
    default: "Are you sure you want to delete this item?",
  },
});

const isOpen = ref(false);
const showSuccess = ref(false);

function openModal() {
  isOpen.value = true;
}

function closeModal() {
  isOpen.value = false;
}

function confirmDelete() {
  closeModal();
  showSuccess.value = true;
  setTimeout(() => {
    router.delete(props.deleteUrl, {
      preserveScroll: true,
    });
  }, 1500);
}

defineExpose({ openModal, closeModal });
</script>

<template>
  <!-- Trigger Button -->
  <slot name="DeleteItemButton" :open="openModal"></slot>

  <!-- Delete Confirmation Modal -->
  <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="absolute inset-0 bg-black opacity-50 backdrop-blur-md"></div>
    <div class="bg-white w-[60rem] max-w-md rounded-lg shadow-2xl p-6 relative animate-fadeIn">
      <slot name="DeleteItem" :close="closeModal" :confirm="confirmDelete" :message="props.message"></slot>
    </div>
  </div>

  <!-- ✅ SUCCESS DELETE MODAL -->
  <transition name="fade">
    <div v-if="showSuccess"
      class="fixed inset-0 flex justify-center items-center z-50 bg-black bg-opacity-40 backdrop-blur-sm">
      <div class="bg-white rounded-2xl shadow-xl p-8 w-[90%] sm:w-[30rem] text-center animate-fade-in">
        <div class="mx-auto mb-3 flex items-center justify-center w-16 h-16 rounded-full bg-[#C8EFD4]">
          <svg class="h-24 w-24" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="50" height="50" rx="25" fill="#C8EFD4" />
            <path
              d="M39.4346 21.7708C38.8917 21.2034 38.3301 20.6188 38.1184 20.1047C37.9226 19.6338 37.9111 18.8533 37.8995 18.0973C37.8779 16.6919 37.8549 15.0992 36.7475 13.9918C35.6402 12.8844 34.0475 12.8614 32.642 12.8398C31.886 12.8283 31.1055 12.8168 30.6346 12.6209C30.122 12.4092 29.5359 11.8476 28.9685 11.3047C27.9749 10.35 26.846 9.26855 25.3426 9.26855C23.8392 9.26855 22.7117 10.35 21.7166 11.3047C21.1492 11.8476 20.5646 12.4092 20.0505 12.6209C19.5825 12.8168 18.7991 12.8283 18.0431 12.8398C16.6377 12.8614 15.045 12.8844 13.9376 13.9918C12.8302 15.0992 12.8144 16.6919 12.7856 18.0973C12.7741 18.8533 12.7626 19.6338 12.5667 20.1047C12.355 20.6173 11.7934 21.2034 11.2505 21.7708C10.2958 22.7644 9.21436 23.8934 9.21436 25.3968C9.21436 26.9002 10.2958 28.0277 11.2505 29.0227C11.7934 29.5901 12.355 30.1748 12.5667 30.6888C12.7626 31.1597 12.7741 31.9402 12.7856 32.6962C12.8072 34.1017 12.8302 35.6944 13.9376 36.8017C15.045 37.9091 16.6377 37.9321 18.0431 37.9537C18.7991 37.9653 19.5796 37.9768 20.0505 38.1726C20.5632 38.3843 21.1492 38.9459 21.7166 39.4888C22.7102 40.4435 23.8392 41.525 25.3426 41.525C26.846 41.525 27.9735 40.4435 28.9685 39.4888C29.5359 38.9459 30.1206 38.3843 30.6346 38.1726C31.1055 37.9768 31.886 37.9653 32.642 37.9537C34.0475 37.9321 35.6402 37.9091 36.7475 36.8017C37.8549 35.6944 37.8779 34.1017 37.8995 32.6962C37.9111 31.9402 37.9226 31.1597 38.1184 30.6888C38.3301 30.1762 38.8917 29.5901 39.4346 29.0227C40.3893 28.0291 41.4708 26.9002 41.4708 25.3968C41.4708 23.8934 40.3893 22.7659 39.4346 21.7708ZM31.9177 22.7558L23.8536 30.8199C23.7466 30.927 23.6195 31.012 23.4797 31.0699C23.3398 31.1279 23.1899 31.1578 23.0385 31.1578C22.8872 31.1578 22.7372 31.1279 22.5974 31.0699C22.4575 31.012 22.3305 30.927 22.2235 30.8199L18.7674 27.3638C18.6604 27.2568 18.5755 27.1297 18.5176 26.9899C18.4597 26.85 18.4298 26.7002 18.4298 26.5488C18.4298 26.3974 18.4597 26.2475 18.5176 26.1077C18.5755 25.9678 18.6604 25.8408 18.7674 25.7337C18.9836 25.5176 19.2768 25.3961 19.5825 25.3961C19.7339 25.3961 19.8838 25.4259 20.0236 25.4839C20.1634 25.5418 20.2905 25.6267 20.3975 25.7337L23.0385 28.3762L30.2876 21.1257C30.3946 21.0186 30.5217 20.9337 30.6616 20.8758C30.8014 20.8179 30.9513 20.7881 31.1027 20.7881C31.254 20.7881 31.4039 20.8179 31.5438 20.8758C31.6836 20.9337 31.8107 21.0186 31.9177 21.1257C32.0247 21.2327 32.1096 21.3598 32.1676 21.4996C32.2255 21.6395 32.2553 21.7894 32.2553 21.9407C32.2553 22.0921 32.2255 22.242 32.1676 22.3818C32.1096 22.5217 32.0247 22.6487 31.9177 22.7558Z"
              fill="#41BD66" />
          </svg>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-1">Deleted Successfully</h2>
        <p class="text-gray-500 text-base mb-6">
          Item Deleted Successfully!
        </p>

        <button @click="showSuccess = false"
          class="bg-[#41BD66] text-white px-6 py-3 rounded-md font-semibold hover:bg-green-700 transition">
          Confirm
        </button>
      </div>
    </div>
  </transition>
</template>
