<script setup>
import TableCell from './TableCell.vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { debounce } from 'lodash';

const props = defineProps({
    searchItem: String,
    columns: Array,
    rows: Object,
});

const search = ref(props.searchItem || '');

watch(
    search,
    debounce((output) => {
        router.get(route('dashboard.index'), { search: output }, {
            preserveState: true,
            replace: true,
            preserveScroll: true,
        });
    }, 300)
);


function getValue(obj, path) {
    return path.split('.').reduce((acc, key) => acc?.[key], obj) ?? 'N/A'
}

const goToPage = (url) => {
    if (!url) return;
    router.visit(url, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
};

</script>

<template>
    <div>

        <div class="sm:w-auto mt-3 mb-2 flex justify-end">
            <div class="relative w-full sm:w-52 md:w-96">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <!-- SEARCH -->
                <input type="search" placeholder="Search" v-model="search"
                    class="w-full h-9 sm:h-10 rounded-full pl-10 pr-3 border text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
            </div>
        </div>

        <!-- TABLE -->
        <div>
            <div class="overflow-x-auto shadow-lg">
                <table class="sm:w-full table-auto border-collapse text-left bg-white text-xs sm:text-sm ">
                    <thead class="bg-[#850038]">
                        <tr class="text-white">
                            <th v-for="col in columns" :key="col.key"
                                class="p-2 sm:p-3 md:p-4 align-middle first:rounded-tl-lg last:rounded-tr-lg whitespace-nowrap">
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

                                <!-- Action column -->
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
                            <span v-if="link.url" @click="goToPage(link.url)"
                                class="cursor-pointer p-1 text-xs sm:text-sm" :class="{
                                    'text-gray-600 hover:underline': link.url,
                                    'text-blue-600 font-bold': link.active
                                }">
                                <!-- Render label or icon -->
                                <i v-if="link.label.includes('Previous')" class="fa-solid fa-chevron-left"></i>
                                <i v-else-if="link.label.includes('Next')" class="fa-solid fa-chevron-right"></i>
                                <span class="px-1" v-else>{{ link.label }}</span>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
