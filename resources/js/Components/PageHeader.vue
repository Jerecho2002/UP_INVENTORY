<script setup>
    defineProps({ title: String })
</script>

<template>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 m-3">
        <h1 class="font-bold text-lg sm:text-xl md:text-2xl text-[#1b1b1b] uppercase">{{ title }}</h1>
        <p class="text-gray-500 font-semibold text-xs sm:text-sm">
            UPCEBU <i class="fa-solid fa-chevron-right mx-2"></i> {{ title }}
        </p>
    </div>
</template>