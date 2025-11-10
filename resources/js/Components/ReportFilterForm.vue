<script setup>
import TableCell from './TableCell.vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { debounce } from 'lodash';

const props = defineProps({
    searchItem: String,
    columns: Array,
    rows: Object,
    selectedStatus: String,
});

const search = ref(props.searchItem || '');
const selectedStatus = ref(props.selectedStatus || '');
const page = usePage();
const fromDate = ref(page.props.fromDate || '');
const toDate = ref(page.props.toDate || '');

// Watchers
watch(
    search,
    debounce(() => {
        router.get(route('reports.index'), {
            search: search.value,
            status: selectedStatus.value,
            from: fromDate.value,
            to: toDate.value,
        }, {
            preserveState: true,
            replace: true,
            preserveScroll: true,
        });
    }, 300)
);

// Category (In Stock / Out of Stock)
watch(selectedStatus, (status) => {
    const quantityFilter = status === '1' ? 'greater_than_zero' : status === '0' ? 'less_than_or_equal_zero' : '';

    router.get(route('reports.index'), {
        search: search.value,
        quantity: quantityFilter,
        from: fromDate.value,
        to: toDate.value,
    }, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
});


// Date watchers
watch([fromDate, toDate], () => {
    router.get(route('reports.index'), {
        search: search.value,
        status: selectedStatus.value,
        from: fromDate.value,
        to: toDate.value,
    }, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
});

function getValue(obj, path) {
    return path.split('.').reduce((acc, key) => acc?.[key], obj) ?? 'N/A'
}
</script>

<template>

    <!-- FILTER FORM -->
    <div
        class="w-full bg-white p-4 rounded-lg shadow-md flex flex-col lg:flex-row flex-wrap items-start lg:items-end gap-4">
        <!-- FROM -->
        <div class="flex flex-col w-full sm:w-1/2 md:w-auto">
            <label class="text-sm font-bold mb-1">From</label>
            <input type="date" v-model="fromDate"
                class="h-[2.8rem] w-full sm:w-48 px-3 text-sm rounded-md text-black border cursor-pointer focus:ring-2 focus:ring-blue-400 focus:outline-none" />
        </div>

        <!-- TO -->
        <div class="flex flex-col w-full sm:w-1/2 md:w-auto">
            <label class="text-sm font-bold mb-1">To</label>
            <input type="date" v-model="toDate"
                class="h-[2.8rem] w-full sm:w-48 px-3 text-sm rounded-md text-black border cursor-pointer focus:ring-2 focus:ring-blue-400 focus:outline-none" />
        </div>

        <!-- CATEGORY -->
        <div class="flex flex-col w-full sm:w-1/2 md:w-auto">
            <label class="text-sm font-bold mb-1">Category</label>
            <select v-model="selectedStatus"
                class="h-[2.8rem] w-full sm:w-40 px-3 text-sm rounded-md text-black border cursor-pointer focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <option value="">All</option>
                <option value="1">In Stock</option>
                <option value="0">Out of Stock</option>
                <option value="2">Low Stock</option>
            </select>
        </div>

        <!-- SEARCH AND ACTION BUTTONS -->
        <div class="flex flex-col sm:flex-row flex-wrap w-full lg:w-auto gap-3 lg:ml-auto">
            <!-- PRINT -->
            <button
                class="flex items-center justify-center gap-2 bg-[#20C6D2] text-white font-semibold rounded-md h-[2.8rem] px-5 hover:bg-[#49dce6] transition duration-150 w-full sm:w-auto">
                <i class="fa-solid fa-print"></i>
                <span>Print</span>
            </button>

            <!-- EXPORT -->
            <button
                class="flex items-center justify-center gap-2 bg-[#20C6D2] text-white font-semibold rounded-md h-[2.8rem] px-5 hover:bg-[#49dce6] transition duration-150 w-full sm:w-auto">
                <i class="fa-solid fa-file-export"></i>
                <span>Export</span>
            </button>
            <!-- SEARCH -->
            <div class="relative w-full sm:w-64">
                <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="search" placeholder="Search stock item" v-model="search"
                    class="h-[2.8rem] w-full pl-10 pr-3 text-sm rounded-full text-black border focus:ring-2 focus:ring-blue-400 focus:outline-none" />
            </div>
        </div>
    </div>


    <!-- Table (horizontal scroll on small screens) -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse text-left mt-7 bg-white text-xs sm:text-sm">
            <thead class="bg-[#850038]">
                <tr class="text-white">
                    <th v-for="col in columns" :key="col.key"
                        class="p-2 sm:p-3 md:p-4 align-middle first:rounded-tl-lg last:rounded-tr-lg">
                        {{ col.label }}
                    </th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                <tr v-for="item in rows.data" :key="item.id" class="even:bg-gray-200">
                    <TableCell v-for="col in columns" :key="col.key">
                        <!-- For normal columns -->
                        <template v-if="col.key !== 'action'">
                            <span v-if="col.format" v-html="col.format(getValue(item, col.key))"></span>
                            <span v-else>{{ getValue(item, col.key) }}</span>
                        </template>

                        <!-- For Action column -->
                        <template v-else>
                            <button class="text-blue-600 mx-1" title="View" @click="viewItem(item)">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </template>
                    </TableCell>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-2 flex justify-end">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 
                bg-gray-100 border border-gray-300 rounded-md px-3 py-1">
            <p class="text-xs sm:text-sm border-r border-gray-300 px-3">
                {{ rows.from }}-{{ rows.to }} of
                {{ rows.total }}
            </p>
            <div>
                <span v-for="link in rows.links" :key="link.label">
                    <component :is="link.url ? Link : 'span'" :href="link.url || null" class="p-1 text-xs sm:text-sm"
                        :class="{
                            'text-gray-600 hover:underline': link.url,
                            'text-blue-600 font-bold': link.active,
                            'text-gray-300': !link.url
                        }">
                        <!-- Render label or icon -->
                        <i v-if="link.label.includes('Previous')" class="fa-solid fa-chevron-left"></i>
                        <i v-else-if="link.label.includes('Next')" class="fa-solid fa-chevron-right"></i>
                        <span class="px-1" v-else>{{ link.label }}</span>
                    </component>
                </span>
            </div>
        </div>
    </div>

</template>