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
      labels: ['Supplied', 'Delivered', 'Cancelled'],
      datasets: [{
        label: 'Count',
        data: [12, 19, 3],
        backgroundColor: ['#7E19FA', '#19FA21', '#FA2C19'], 
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
<template>
  <div class="bg-white w-full h-auto sm:h-[38rem] rounded-xl shadow-lg p-2 sm:p-2 md:p-4">
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
    <div class="flex flex-wrap justify-center gap-3 sm:gap-4 md:gap-6 my-3">
      <div v-for="supplier in dropdownSupplierList" :key="supplier.model"
        class="flex flex-col items-center sm:items-start">
        <select v-model="itemForm.supplier[supplier.model]"
          class="border border-gray-300 rounded-md px-3 py-2 w-36 sm:w-44 md:w-52 text-xs sm:text-sm md:text-base focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038] outline-none transition-all">
          <option disabled value="">Select</option>
          <option v-for="option in supplier.options" :key="option" :value="option">
            {{ option }}
          </option>
        </select>
      </div>
    </div>
  </div>

</template>