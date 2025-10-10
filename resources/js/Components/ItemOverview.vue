<script setup>
const props = defineProps({
    itemOverview: Array,

});
</script>

<template>

   <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
    <div
      v-for="overview in itemOverview"
      :key="overview.title"
      class="bg-white rounded-lg shadow-lg flex flex-col justify-between"
    >
      <!-- Header -->
      <div :class="['p-4 rounded-t-lg', overview.bgColor]">
        <h1 class="text-white text-base sm:text-lg font-bold">
          {{ overview.title }}
        </h1>
      </div>

      <!-- Icon -->
      <div class="p-6 text-3xl flex sm:justify-start" :class="overview.textColor">
        <i :class="overview.icon"></i>
      </div>
    </div>
  </div>

</template>