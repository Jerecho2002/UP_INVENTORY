<script setup>
const props = defineProps({
  title: String,
  chartId: String,
});
</script>

<template>
  <div class="bg-white rounded-lg shadow p-4 m-2">
    <h2 class="font-bold mb-2 text-lg">{{ title }}</h2>

  </div>
</template>
