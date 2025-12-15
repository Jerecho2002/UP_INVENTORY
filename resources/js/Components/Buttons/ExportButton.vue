<script setup>
function exportCsv() {
  window.location.href = '/export-csv';
}
</script>

<template>
    <button @click="exportCsv"
        class="flex items-center justify-center gap-2 bg-[#850038] text-white font-semibold rounded-md h-[2.8rem] px-5 hover:bg-[#a60648] transition duration-150 w-full sm:w-auto">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M19 13v6H5v-6H3v6a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-6h-2zM12 3l-4.29 4.29 1.42 1.42L11 7.41V15h2V7.41l1.87 1.3 1.42-1.42L12 3z" />
        </svg>
        <span>Export</span>
    </button>   
</template>
