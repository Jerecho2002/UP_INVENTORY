<script setup>
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

// accept the array from parent
const props = defineProps({
  menuItems: {
    type: Array,
    required: true,
    // [{ name, icon, route }]
  }
});


</script>

<template>
  <ul class="py-7 text-l font-semibold space-y-2 bg-white h-full w-[15rem] shadow-lg">
    <li v-for="item in props.menuItems" :key="item.name" class="rounded-md">
      <Link :href="item.route" class="flex items-center gap-3 text-[#3A3434] py-4 px-4 mx-3 rounded-md hover:bg-[#D9D9D9]">
        <i :class="item.icon"></i>
        <span>{{ item.name }}</span>
      </Link>
    </li>
  </ul>
</template>
`       `