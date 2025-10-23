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
  <div class="bg-white sm:h-[27.5rem] rounded-xl shadow-lg p-4">
    <h2 class="font-bold mb-2 text-lg">{{ title }}</h2>

    <!-- CHART -->
    <div></div>

    <!-- SUPPLIED/DELIVERED/CANCELLED -->
    <div v-for="supplier in supplierChart" :key="supplier.name"
      class="flex items-center text-sm py-2 px-4 mx-5 sm:mx-10 gap-2">
      <i :class="supplier.icon"></i>
      <span class="text-[#827A7A] font-bold">{{ supplier.name }}</span>
    </div>

    <!-- DROPDOWN SUPPLIER LIST -->
     <div class="flex justify-center">
        <div v-for="supplier in dropdownSupplierList" :key="supplier.model">
          <select v-model="itemForm.supplier[supplier.model]">
            <option disabled value="">Select</option>
            <option v-for="option in supplier.options" :key="option" :value="option">{{ option }}</option>
          </select>
          <canvas ref="chartCanvasStatistics"></canvas>
        </div>

        
     </div>
  </div>
</template>