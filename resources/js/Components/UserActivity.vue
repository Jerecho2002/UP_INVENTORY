<script setup>
    const props = defineProps({
        title: String,
    });
</script>

<template>
    <div class="bg-white rounded-xl shadow-lg p-4">
        <div>
            <h2 class="font-bold text-lg">{{ title }}</h2>
        </div>
    </div>
</template>