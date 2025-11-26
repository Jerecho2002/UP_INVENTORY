<script setup>
import axios from 'axios';
import { ref } from 'vue';

// Get CSRF token from meta tag in app.blade.php
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Reference to hidden file input
const fileInput = ref(null);

// Trigger the hidden file input when button is clicked
function triggerFileSelect() {
  fileInput.value.click();
}

// Handle file selection and upload
async function submitFile(event) {
  const file = event.target.files[0];
  if (!file) return;

  const formData = new FormData();
  formData.append('_token', csrf);
  formData.append('file', file);

  try {
    const response = await axios.post('/convert-excel-to-csv', formData, {
      responseType: 'blob', // important for file download
    });

    // Create temporary URL for download
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.download = 'converted.csv';
    link.click();
    window.URL.revokeObjectURL(url);

    // Reset input so user can upload again
    event.target.value = null;
  } catch (error) {
    console.error('Upload failed', error);
    alert('File conversion failed. Please try again.');
  }
}
</script>

<template>
  <!-- Hidden file input -->
  <input
    type="file"
    ref="fileInput"
    class="hidden"
    accept=".xlsx,.xls"
    @change="submitFile"
  />

  <!-- Styled button -->
  <button
    type="button"
    @click="triggerFileSelect"
    class="flex items-center justify-center gap-2 bg-[#850038] text-white font-semibold rounded-md h-[2.8rem] px-5 hover:bg-[#a60648] transition duration-150 w-full sm:w-auto"
  >
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
      <path d="M14 2H6a2 2 0 0 0-2 2v7h2V4h8v4h4v4h2V8l-6-6zm-1 12h-3l3-3-1.41-1.41L7 14l4.59 4.41L13 17l-3-3h3a5 5 0 0 1 5 5h2a7 7 0 0 0-7-7z" />
    </svg>
    <span>File Convert</span>
  </button>
</template>
