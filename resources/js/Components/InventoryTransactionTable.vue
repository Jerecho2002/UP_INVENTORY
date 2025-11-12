<script setup>
import TableCell from './TableCell.vue';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    tableHeader: Array,
    items: Array,
})

const search = ref(props.search || '');
const cost_range = ref(props.cost_range || '');
const status = ref(props.status || '');

watch(
    search,
    debounce((output) => {
        router.get('/inventory/transactions', { search: output, cost_range: cost_range.value, status: status.value }, { preserveState: true, only: ['items'] });
    })
);

watch(
    status,
    debounce((isActive) => {
        router.get('/inventory/transactions', { search: search.value, cost_range: cost_range.value, status: isActive }, { preserveState: true });
    })
)

watch(
    cost_range,
    debounce((range) => {
        router.get('/inventory/transactions', { search: search.value, cost_range: range, status: status.value }, { preserveState: true });
    })
)
</script>

<template>
    <div>
        <div>
            <h1 class="flex p-4 text-[#850038] font-bold text-lg">Transaction History</h1>
        </div>

        <!-- FILTER DATE/COST RANGE/STATUS + SEARCH -->
        <div class="flex flex-col sm:flex-row justify-between mx-4">
            <!-- LEFT FILTERS -->
            <div class="flex flex-col md:flex-row gap-8">
                <div>
                    <input type="date"
                        class="h-[2.5rem] sm:h-[3rem] w-full sm:w-56 rounded-md cursor-pointer focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                </div>

                <div>
                    <select v-model="cost_range"
                        class="h-[2.5rem] sm:h-[3rem] w-full sm:w-48 rounded-md cursor-pointer focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                        <option value="">Cost Range</option>
                        <option value="0-50000">₱0 - ₱50,000</option>
                        <option value="50000-99999999">₱50,000 Above</option>
                    </select>
                </div>

                <div>
                    <select v-model="status"
                        class="h-[2.5rem] sm:h-[3rem] w-full sm:w-48 rounded-md cursor-pointer focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                        <option value="">Status</option>
                        <option value="1">Completed</option>
                        <option value="0">Pending</option>
                    </select>
                </div>
            </div>

            <!-- RIGHT SEARCH + EXPORT -->
            <div class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-3">
                <!-- Export button -->
                <button
                    class="flex items-center justify-center gap-2 bg-[#20C6D2] text-white font-semibold rounded-md h-[2.5rem] sm:h-[3rem] px-5 hover:bg-[#49dce6] transition duration-150">
                    <i class="fa-solid fa-file-export"></i>
                    <span>Export</span>
                </button>

                <!-- Search bar -->
                <div class="relative w-full sm:w-96">
                    <input type="text" placeholder="Search..." v-model="search"
                        class="w-full h-[2.5rem] sm:h-[3rem] rounded-full pl-10 pr-4 border border-gray-300 focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038] text-sm bg-[#F8F8F8]" />
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                </div>

            </div>

        </div>
        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class=" w-full table-auto border-collapse mt-8 text-left bg-white text-xs sm:text-sm">
                <thead class="bg-[#850038]">
                    <tr class="text-white">
                        <th v-for="tabh in tableHeader" :key="tabh.key"
                            class="p-2 sm:p-3 md:p-4 align-middle first:rounded-tl-lg last:rounded-tr-lg">
                            {{ tabh.label }}
                        </th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <tr v-for="item in items.data" :key="item.id" class="even:bg-gray-200">
                        <TableCell v-for="tabh in tableHeader" :key="tabh.key">
                            <!-- For normal items -->
                            <template v-if="tabh.key !== 'action'">
                                <span v-if="tabh.format" v-html="tabh.format(item[tabh.key])"></span>
                                <span v-else>{{ item[tabh.key] }}</span>
                            </template>

                            <!-- Action -->
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
        <div class="mt-2 flex justify-end p-2">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 
                bg-gray-100 border border-gray-300 rounded-md px-3 py-1">
                <p class="text-xs sm:text-sm border-r border-gray-300 px-3">
                    {{ items.from }}-{{ items.to }} of
                    {{ items.total }}
                </p>
                <div>
                    <span v-for="link in items.links" :key="link.label">
                        <component :is="link.url ? Link : 'span'" :href="link.url || null"
                            class="p-1 text-xs sm:text-sm" :class="{
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
    </div>


</template>