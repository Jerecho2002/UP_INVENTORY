<script setup>
const props = defineProps({
  itemOverview: Array,

});
</script>

<template>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5 w-full mx-auto">
    <div v-for="overview in itemOverview" :key="overview.title"
      class="bg-white rounded-xl shadow-md flex flex-col justify-between hover:shadow-xl transition-shadow duration-300">
      <!-- Header -->
      <div :class="['p-4 sm:p-5 rounded-t-lg text-center sm:text-left', overview.bgColor]">
        <h1 class="text-white text-sm sm:text-base md:text-lg font-semibold truncate">
          {{ overview.title }}
        </h1>
      </div>

      <!-- Icon + Value -->
      <div class="flex items-center sm:justify-start gap-3 px-5 py-6 text-2xl sm:text-3xl md:text-4xl"
        :class="overview.textColor">
        <i :class="overview.icon"></i>
        <span class="font-bold text-gray-800 text-lg sm:text-xl md:text-2xl">{{ overview.value }}</span>
      </div>
    </div>
  </div>


</template>