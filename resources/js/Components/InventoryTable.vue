<script setup>
import { usePage, Link, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { debounce } from 'lodash';

const props = defineProps({
    searchItem: String,
    tableHeaders: {
        type: Array,
        required: true,
    },
});

const search = ref(props.searchItem || '');
watch(
    search,
    debounce((output) => {
        router.get('/', { search: output }, { preserveState: true });
    }, 300)
);

function deleteItem(id) {
    if (confirm('Are you sure you want to delete this item?')) {
        router.delete(`/items/${id}`, { preserveScroll: true });
    }
}

const page = usePage();
const items = computed(() => page.props.items);
const user = computed(() => page.props.auth.user);
const userRole = computed(() => user.value?.role);
const canDelete = computed(() => ['admin'].includes(userRole.value));
const statusMap = {
    0: { label: 'Inactive', class: 'bg-red-100 text-red-700' },
    1: { label: 'Active', class: 'bg-green-100 text-green-700' },
    2: { label: 'Pending', class: 'bg-yellow-100 text-yellow-700' },
};
</script>

<template>
    <div class="flex flex-col mx-2 sm:mx-2 md:mx-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <h1 class="font-bold text-lg sm:text-xl md:text-2xl text-[#1b1b1b]">INVENTORY</h1>
            <p class="text-gray-500 font-semibold text-xs sm:text-sm">
                UPCEBU
                <span>
                    <i class="fa-solid fa-chevron-right mx-1 sm:mx-2 text-xs sm:text-sm"></i>
                </span>
                Inventory
            </p>
        </div>
    </div>

    <!-- Add button + filters + search -->
    <div class="flex flex-col sm:flex-row justify-between items-end gap-4 mb-4 mt-[2rem]">
        <button
            class="flex gap-2 bg-[#3FA165] rounded-md text-white px-3 py-2 text-xs sm:text-sm hover:bg-[#2a9754] w-full sm:w-auto justify-center">
            <i class="fa-solid fa-plus"></i>
            <span class="font-bold">Add Item</span>
        </button>

        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full sm:w-auto">
            <div class="flex flex-col w-full sm:w-auto">
                <label class="text-xs font-bold mb-1 sm:mb-0">Unit Cost</label>
                <select class="h-8 sm:h-9 w-full sm:w-28 text-xs rounded-md text-gray-600 border">
                    <option value="">Select</option>
                    <option value="">₱0 - ₱50,000</option>
                    <option value="">₱50,000 Above</option>
                </select>
            </div>

            <div class="w-full sm:w-auto mt-3">
                <input type="search" placeholder="Search" v-model="search"
                    class="w-full sm:w-64 md:w-96 h-9 sm:h-10 rounded-full px-3 border text-sm" />
            </div>
        </div>
    </div>
    <div>

        <!-- Table (horizontal scroll on small screens) -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse text-left bg-white text-xs sm:text-sm">
                <thead class="bg-[#850038]">
                    <tr class="text-white">
                        <th v-for="table in props.tableHeaders" :key="table.name"
                            class="p-2 sm:p-3 md:p-4 align-middle first:rounded-tl-lg last:rounded-tr-lg">
                            {{ table.name }}
                        </th>
                    </tr>
                </thead>

                <tbody class="text-gray-700">
                    <tr v-for="item in items.data" :key="item.id" class="even:bg-gray-200">
                        <td class="p-2 sm:p-3 md:p-4 align-middle break-words">
                            {{ item.category }}</td>
                        <td :class="{
                            'text-red-500': item.status === 0,
                            'text-green-500': item.status === 1,
                            'text-yellow-500': item.status === 2,
                        }" class="p-2 sm:p-3 md:p-4 align-middle break-words">{{ item.property.property_number }}</td>
                        <td class="p-2 sm:p-3 md:p-4 align-middle break-words">{{ item.item_name }}</td>
                        <td class="p-2 sm:p-3 md:p-4 align-middle">{{ item.unit ?? 'N/A' }}</td>
                        <td class="p-2 sm:p-3 md:p-4 align-middle">{{ item.unit_cost ? `₱${item.unit_cost}` : 'N/A' }}
                        </td>
                        <td class="p-2 sm:p-3 md:p-4 align-middle whitespace-nowrap">{{ `${item.quantity} pcs.` }}</td>
                        <td class="p-2 sm:p-3 md:p-4 align-middle whitespace-nowrap">
                            <span
                                :class="[statusMap[item.status].class, 'px-2 py-1 rounded-full text-xs font-semibold']">
                                {{ statusMap[item.status].label }}
                            </span>
                        </td>

                        <td class="p-2 sm:p-3 md:p-4 align-middle">
                            <button v-if="canDelete" @click="deleteItem(item.id)"
                                class="text-red-600 hover:underline text-xs sm:text-sm">
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 justify-between   ">
            <p class="text-xs sm:text-sm">
                From Page {{ items.from }} to Page {{ items.to }} total of
                {{ items.total }}
            </p>
            <div>
                <span v-for="link in items.links" :key="link.label">
                    <Link v-if="link.url" :href="link.url" class="p-1 mx-1 text-xs sm:text-sm" :class="{
                        'text-gray-600 hover:underline': link.url,
                        'text-blue-600 font-bold': link.active,
                    }">
                    <!-- Custom labels -->
                    <template v-if="link.label.includes('Previous')">
                        <i class="fa-solid fa-chevron-left"></i>
                    </template>
                    <template v-else-if="link.label.includes('Next')">
                        <i class="fa-solid fa-chevron-right"></i>
                    </template>
                    <template v-else>
                        {{ link.label }}
                    </template>
                    </Link>

                    <!-- Disabled state -->
                    <span v-else class="text-gray-300 text-xs sm:text-sm p-1 mx-1">
                        <template v-if="link.label.includes('Previous')">
                            <i class="fa-solid fa-chevron-left"></i>
                        </template>
                        <template v-else-if="link.label.includes('Next')">
                            <i class="fa-solid fa-chevron-right"></i>
                        </template>
                        <template v-else>
                            {{ link.label }}
                        </template>
                    </span>
                </span>
            </div>
        </div>
    </div>
</template>
