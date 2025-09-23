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
    <div class="py-7 text-lg font-semibold space-y-2 bg-white h-screen w-full sm:w-[16rem] md:w-[20rem] shadow-lg flex flex-col">
        <!-- Menu -->
        <ul class="flex-1">
            <li v-for="item in props.menuItems" :key="item.name" class="rounded-md">
                <Link :href="item.route"
                    class="flex items-center gap-3 text-[#3A3434] py-4 px-4 mx-2 sm:mx-3 rounded-md hover:bg-[#D9D9D9]">
                <i :class="item.icon"></i>
                <span>{{ item.name }}</span>
                </Link>
            </li>
        </ul>

        <!-- Footer -->
        <div class="mt-auto sm:mt-auto">
            <span class="block text-center sm:text-left text-xs sm:mx-[5rem] text-[#8C8C8C]">
                EasyLearning © UP Cebu.
            </span>
        </div>
    </div>


</template>
