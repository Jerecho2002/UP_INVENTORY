<script setup>
const props = defineProps({
  title: String,
  supplierChart: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <div class="bg-white rounded-2xl shadow-lg p-4">
    <h2 class="font-bold mb-2 text-lg">{{ title }}</h2>

    <!-- CHART -->
    <div></div>

    <!-- SUPPLIED/DELIVERED/CANCELLED -->
    <div v-for="supplier in supplierChart" :key="supplier.name" class="flex items-center text-sm py-2 px-4 mx-5 sm:mx-10 gap-2">
      <i :class="supplier.icon"></i>
      <span class="text-[#827A7A] font-bold">{{ supplier.name }}</span>
    </div>
    <div>
      
    </div>
  </div>
</template>