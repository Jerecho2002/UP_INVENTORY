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