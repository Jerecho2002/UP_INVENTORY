<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, watchEffect } from "vue";

const menuItems = [
    { name: "Dashboard", icon: "fa-solid fa-table-cells-large", route: "dashboard.index" },
    {
        name: "Inventory",
        icon: "fa-solid fa-boxes-packing",
        children: [
            { name: "Items", route: "inventory.items" },
            // { name: "Offices", route: "inventory.items" },
            // { name: "Categories", route: "inventory.items" },
            // { name: "Locations", route: "inventory.items" },
            { name: "Acknowledgements", route: "inventory.acknowledgements" },
            { name: "Transactions", route: "inventory.transactions" },
        ],
    },
    { name: "Reports", icon: "fa-solid fa-file-export", route: "reports.index" },
    // { name: "Offices & Types", icon: "fa-solid fa-network-wired", route: "reports.index" },
    { name: "Suppliers", icon: "fa-solid fa-handshake", route: "suppliers.index" },
    { name: "Item Archiving", icon: "fa-solid fa-recycle", route: "item_archiving.index" },
];

const openDropdown = ref(null);

// Toggle dropdown open/close
const toggleDropdown = (name) => {
    openDropdown.value = openDropdown.value === name ? null : name;
};

// Keep parent dropdown open if current route is a child
watchEffect(() => {
    menuItems.forEach((item) => {
        if (item.children) {
            const isChildActive = item.children.some((child) =>
                route().current(child.route)
            );
            if (isChildActive) {
                openDropdown.value = item.name;
            }
        }
    });
});
</script>

<template>
    <div
        class="py-7 text-lg font-semibold space-y-2 bg-white h-full w-full sm:w-[16rem] md:w-[15rem] shadow-lg flex flex-col">
        <!-- Menu -->
        <ul class="flex-1">
            <li v-for="item in menuItems" :key="item.name" class="rounded-md">
                <!-- If item has children -->
                <div v-if="item.children" @click="toggleDropdown(item.name)"
                    class="flex items-center gap-3 justify-between py-4 px-4 mx-2 sm:mx-3 rounded-md cursor-pointer transition-all duration-300"
                    :class="[
                        item.children.some((child) => route().current(child.route))
                            ? 'bg-[#D9D9D9] font-semibold text-[#850038]'
                            : 'text-[#3A3434] hover:bg-[#D9D9D9]'
                    ]">
                    <div class="flex items-center gap-3">
                        <i :class="item.icon"></i>
                        <span>{{ item.name }}</span>
                    </div>
                    <i class="fa-solid transform transition-transform duration-300"
                        :class="openDropdown === item.name ? 'fa-chevron-up rotate-360' : 'fa-chevron-down'"></i>
                </div>

                <!-- Link if no children -->
                <Link v-else :href="route(item.route)"
                    class="flex items-center gap-3 text-[#3A3434] py-4 px-4 mx-2 sm:mx-3 rounded-md hover:bg-[#D9D9D9] transition-all duration-300"
                    :class="{
                        'bg-[#D9D9D9] font-semibold text-[#850038]': route().current(item.route),
                    }">
                <i :class="item.icon"></i>
                <span>{{ item.name }}</span>
                </Link>

                <!-- Dropdown children-->
                <div v-if="item.children" class="overflow-hidden transition-all duration-500 ease-in-out"
                    :class="openDropdown === item.name ? 'max-h-60 opacity-100 mt-2' : 'max-h-0 opacity-0'">
                    <ul class="ml-10 mt-1 space-y-1 text-[15px] text-gray-600">
                        <li v-for="child in item.children" :key="child.name">
                            <Link :href="route(child.route)"
                                class="block py-2 px-2 mx-3 rounded-md hover:bg-[#EFEFEF] transition-all duration-300"
                                :class="{
                                    'font-semibold text-[#850038]': route().current(child.route),
                                    'text-gray-600': !route().current(child.route),
                                }">
                            {{ child.name }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>

        <!-- Footer -->
        <div class="mt-auto py-3">
            <span class="block text-center text-xs text-gray-500">
                EasyLearning © UP Cebu.
            </span>
        </div>
    </div>
</template>
