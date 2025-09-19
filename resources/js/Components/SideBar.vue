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
    <div class="py-7 text-lg font-semibold space-y-2 bg-white h-screen w-[20rem] shadow-lg">
        <ul>
            <li v-for="item in props.menuItems" :key="item.name" class="rounded-md">
                <Link :href="item.route"
                    class="flex items-center gap-3 text-[#3A3434] py-4 px-4 mx-3 rounded-md hover:bg-[#D9D9D9]">
                <i :class="item.icon"></i>
                <span>{{ item.name }}</span>
                </Link>
            </li> 
        </ul>

        <div>
            <span class="text-xs mx-[3rem] text-[#8C8C8C]">EasyLearning © UP Cebu.</span>
        </div>
    </div>


</template>
` `