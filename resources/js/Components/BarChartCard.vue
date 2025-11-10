<script setup>
import { onMounted, ref } from "vue";
import { Chart, LineController, LineElement, PointElement, LinearScale, Title, CategoryScale } from 'chart.js/auto';


Chart.register(LineController, LineElement, PointElement, LinearScale, Title, CategoryScale)

const chartCanvasOrder = ref(null)
const chartCanvasRequest = ref(null)
const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];

onMounted(() => {
  if (chartCanvasOrder.value) {
    const ctx = chartCanvasOrder.value.getContext('2d')

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Order Status',
            data: [12, 19, 3, 5, 2, 3, 7],
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(255, 205, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(201, 203, 207, 0.2)',
            ],
            borderColor: [
              'rgb(255, 99, 132)',
              'rgb(255, 159, 64)',
              'rgb(255, 205, 86)',
              'rgb(75, 192, 192)',
              'rgb(54, 162, 235)',
              'rgb(153, 102, 255)',
              'rgb(201, 203, 207)',
            ],
            borderWidth: 2,
            fill: true,
            tension: 0.3, // smooth line curve
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { color: '#333' },
          },
          x: {
            ticks: { color: '#333' },
          },
        },
      },
    })
  }
})

onMounted(() => {
  if (chartCanvasRequest.value) {
    const ctx = chartCanvasRequest.value.getContext('2d')

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Request Status',
            data: [12, 19, 3, 5, 2, 3, 7],
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(255, 205, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(201, 203, 207, 0.2)',
            ],
            borderColor: [
              'rgb(255, 99, 132)',
              'rgb(255, 159, 64)',
              'rgb(255, 205, 86)',
              'rgb(75, 192, 192)',
              'rgb(54, 162, 235)',
              'rgb(153, 102, 255)',
              'rgb(201, 203, 207)',
            ],
            borderWidth: 2,
            fill: true,
            tension: 0.3, // smooth line curve
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          title: {
            display: true,
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { color: '#333' },
          },
          x: {
            ticks: { color: '#333' },
          },
        },
      },
    })
  }
})
</script>

<template>

  <div class="w-full max-w-[1400px] mx-auto flex flex-col lg:flex-row flex-wrap gap-4 sm:gap-5 h-auto">
    <!-- Orders Card -->
    <div class="flex-1 min-w-[300px] bg-white p-4 sm:p-5 md:p-6 rounded-lg shadow-lg w-full">
      <h1 class="font-bold text-lg sm:text-xl text-gray-800 text-center md:text-left">
        Orders
      </h1>
      <div class="relative w-full h-[220px] sm:h-[260px] md:h-[300px] lg:h-[320px] mt-4">
        <canvas ref="chartCanvasOrder" class="w-full h-full"></canvas>
      </div>
    </div>

    <!-- Request Card -->
    <div class="flex-1 min-w-[300px] bg-white p-4 sm:p-5 md:p-6 rounded-lg shadow-lg w-full">
      <h1 class="font-bold text-lg sm:text-xl text-gray-800 text-center md:text-left">
        Request
      </h1>
      <div class="relative w-full h-[220px] sm:h-[260px] md:h-[300px] lg:h-[320px] mt-4">
        <canvas ref="chartCanvasRequest" class="w-full h-full"></canvas>
      </div>
    </div>
  </div>

<<<<<<< HEAD
</template>
=======


</template>
>>>>>>> 67ea0a81637291d0f3f67f97d2ada11c4a6b4687
