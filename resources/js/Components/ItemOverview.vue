<script setup>
defineProps({
    title: String,
    icon: String,
    bgColor: { type: String, default: 'bg-[#06B6D4]' },
    textColor: { type: String, default: 'text-[#06B6D4]' }
});
</script>

<template>

    <div class="bg-white w-full sm:w-[16rem] rounded-lg shadow-lg">
        <!-- Header -->
        <div :class="['p-4 rounded-tl-lg rounded-tr-lg', bgColor]">
            <h1 class="text-white text-base sm:text-lg md:text-xl font-bold">
                {{ title }}
            </h1>
        </div>
        <!-- Icon -->
        <div class="p-6 text-2xl sm:text-3xl flex sm:justify-start" :class="textColor">
            <i :class="icon"></i>
        </div>
    </div>

</template>