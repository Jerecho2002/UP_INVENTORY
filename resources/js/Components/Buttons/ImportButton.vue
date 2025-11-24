<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const fileInput = ref(null);

function selectFile() {
    fileInput.value.click();
}

function handleFile(e) {
    const file = e.target.files[0];
    if (!file) return;

    const form = new FormData();
    form.append("file", file);

    router.post("/inventory/import", form, {
        forceFormData: true,
        onSuccess: () => alert("Import Successful!")
    });
}
</script>

<template>
    <!-- Hidden file input -->
    <input type="file" ref="fileInput" class="hidden" @change="handleFile" />

    <!-- Your button -->
    <button @click="selectFile"
        class="flex items-center justify-center gap-2 bg-[#850038] text-white font-semibold rounded-md h-[2.8rem] px-5 hover:bg-[#a60648] transition duration-150 w-full sm:w-auto">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M19 13v6H5v-6H3v6a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6h-2zM11 3v10.17l-3.59-3.58L6 11l6 6 6-6-1.41-1.41L13 13.17V3z" />
        </svg>
        <span>Import</span>
    </button>
</template>
