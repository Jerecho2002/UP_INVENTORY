<script setup>
import { reactive, onMounted, ref } from 'vue';
import { Chart } from 'chart.js/auto';

const props = defineProps({
  title: String,
  supplierChart: Array,
  dropdownSupplierList: Array,
});

const itemForm = reactive({
  supplier: "",
});

const chartCanvasStatistics = ref(null)

onMounted(() => {
  new Chart(chartCanvasStatistics.value, {
    type: 'pie', // Change to 'line', 'pie', etc. if needed
    data: {
      labels: ['January', 'February', 'March', 'April'],
      datasets: [{
        label: 'Order Status',
        data: [12, 19, 3, 5],
        backgroundColor: ['#2E7D32'],
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true },
      }
    }
  })
})
</script>
<style scoped>
/* You can style the canvas here */
canvas {
  max-width: 100%;
  height: auto;
}
</style>

<template>
  <div class="bg-white w-full h-auto rounded-xl shadow-lg p-2 sm:p-4 md:p-6">
    <h2 class="font-bold mb-2 text-lg text-center sm:text-left">{{ title }}</h2>

    <!-- CHART -->
    <div class="flex justify-center items-center w-full">
      <canvas ref="chartCanvasStatistics" class="w-full max-w-[800px] h-[200px] sm:h-[280px] md:h-[340px]"></canvas>
    </div>

    <!-- SUPPLIED/DELIVERED/CANCELLED -->
    <div v-for="supplier in supplierChart" :key="supplier.name"
      class="flex flex-wrap items-center justify-center sm:justify-start text-xs sm:text-sm md:text-base py-2 px-2 sm:px-4 mx-2 sm:mx-5 md:mx-10 gap-2">
      <i :class="supplier.icon"></i>
      <span class="text-[#827A7A] font-bold">{{ supplier.name }}</span>
    </div>

    <!-- DROPDOWN SUPPLIER LIST -->
    <div class="flex flex-wrap justify-center gap-3 sm:gap-4 md:gap-6 my-4">
      <div v-for="supplier in dropdownSupplierList" :key="supplier.model"
        class="flex flex-col items-center sm:items-start">
        <select v-model="itemForm.supplier[supplier.model]"
          class="border border-gray-300 rounded-md px-3 py-2 w-36 sm:w-44 md:w-52 text-xs sm:text-sm md:text-base focus:ring-2 focus:ring-[#850038] outline-none transition-all">
          <option disabled value="">Select</option>
          <option v-for="option in supplier.options" :key="option" :value="option">{{ option }}</option>
        </select>
      </div>
    </div>
  </div>

</template>