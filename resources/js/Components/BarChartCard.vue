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
    <div class="flex flex-col md:flex-row gap-4 w-full h-full sm:h-[18rem]">
        <div class="flex-1 bg-white p-4 rounded-lg shadow-lg">
            <canvas ref="chartCanvasOrder"></canvas>
        </div>
        <div class="flex-1 bg-white p-4 rounded-lg shadow-lg">
            <canvas ref="chartCanvasRequest"></canvas>
        </div>
    </div>
</template>