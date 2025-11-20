<script setup>
const props = defineProps({
    addButton: Array,
})
</script>

<template>
        <!-- ADD BUTON -->
        <button @click="$emit('click')" v-for="btn in addButton" :key="btn.title"
             class="flex gap-2 bg-[#0E6021] rounded-md text-white px-3 py-3 text-xs sm:text-sm hover:bg-[#2a9754] w-full sm:w-auto items-center mt-2 ustify-center">
            <i :class="btn.icon + ' my-[4px]'"></i>
            <span class="font-bold">{{ btn.title }}</span>  
        </button>
</template>