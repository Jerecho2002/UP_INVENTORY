<script setup>
import { onMounted, ref } from "vue";
import { Chart } from 'chart.js/auto';

const chartCanvasOrder = ref(null)
const chartCanvasRequest = ref(null)

onMounted(() => {
  new Chart(chartCanvasOrder.value, {
    type: 'line', // Change to 'line', 'pie', etc. if needed
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

onMounted(() => {
  new Chart(chartCanvasRequest.value, {
    type: 'line', // Change to 'line', 'pie', etc. if needed
    data: {
      labels: ['January', 'February', 'March', 'April'],
      datasets: [{
        label: 'Request Status',
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

<template>

  <div class="w-full sm:w-[90%] lg:w-[100%] mx-auto flex flex-col md:flex-row gap-4 h-auto sm:h-auto md:h-[24rem]">
    <!-- Orders Card -->
    <div class="flex-1 bg-white p-4 sm:p-5 md:p-6 rounded-lg shadow-lg w-full">
      <h1 class="font-bold text-lg sm:text-xl text-gray-800 text-center md:text-left">Orders</h1>
      <div class="my-4 sm:my-5 flex justify-center items-center">
        <canvas ref="chartCanvasOrder" class="w-full max-w-[600px] h-[200px] sm:h-[250px] md:h-[300px]"></canvas>
      </div>
    </div>

    <!-- Request Card -->
    <div class="flex-1 bg-white p-4 sm:p-5 md:p-6 rounded-lg shadow-lg w-full">
      <h1 class="font-bold text-lg sm:text-xl text-gray-800 text-center md:text-left">Request</h1>
      <div class="my-4 sm:my-5 flex justify-center items-center">
        <canvas ref="chartCanvasRequest" class="w-full max-w-[600px] h-[200px] sm:h-[250px] md:h-[300px]"></canvas>
      </div>
    </div>
  </div>


</template>