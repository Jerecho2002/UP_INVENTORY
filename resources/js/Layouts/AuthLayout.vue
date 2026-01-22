<script setup>
import Logo from "@/Components/Logo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
  <div class="flex flex-col md:flex-row min-h-screen">
    <div class="w-full md:w-1/2">
      <div class="flex flex-col items-center justify-center h-full p-6 md:p-12">
        <Logo />
        <h1 class="mt-6 text-3xl font-bold text-[#857F7F] text-center">
          Welcome to UP Inventory
        </h1>
        <p class="mt-4 text-[#A6A4A4] text-center">
          Manage your inventory efficiently and effectively with our
          user-friendly platform.
        </p>
      </div>
    </div>
    <div
      class="bg-[#850038] w-full md:w-1/2 flex items-center justify-center p-6 md:p-12"
    >
      <div
        class="w-full sm:w-4/5 md:w-3/4 lg:w-2/3 xl:w-1/2 max-w-lg bg-white px-6 py-6 shadow-md rounded-lg"
      >
        <slot />
      </div>
    </div>
  </div>
</template>
