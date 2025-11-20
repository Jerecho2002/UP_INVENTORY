<script setup>
import { router } from '@inertiajs/vue3';
import { watch, ref } from 'vue';
import { debounce } from 'lodash';

const props = defineProps({
    unitCostOptions: { type: Array, default: () => [] },
    filterStatus: { type: Array, default: () => [] },
    search: { type: String, default: ''},
    status: { type: String, default: ''},
    cost_range: { type: String, default: ''},
});

    
const search = ref(props.search || '');
const cost_range = ref(props.cost_range || '');
const status = ref(props.status || '');

const emit = defineEmits(['update:search', 'update:status', 'update:cost_range']);

const fetchItems = debounce((params) => {
    router.get('/inventory/items', params, {
        preserveState: true,
        preserveScroll: true,
        only: ['items'],
    });
}, 300);

watch(search, (value) => {
    fetchItems({ search: value, cost_range: cost_range.value, status: status.value });
    emit('update:search', value);
});

watch(status, (value) => {
    fetchItems({ search: search.value, cost_range: cost_range.value, status: value });
    emit('update:status', value);
});

watch(cost_range, (value) => {
    fetchItems({ search: search.value, cost_range: value, status: status.value });
    emit('update:cost_range', value);
});

</script>

<template>
    <!-- UNIT COST FILTER -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full sm:w-auto">
        <div class="flex flex-col w-full sm:w-auto">
            <label class="text-xs font-bold mb-1 sm:mb-0">Unit Cost</label>
            <select v-model="cost_range"
                class="h-8 sm:h-9 w-full sm:w-36 text-xs rounded-md text-gray-600 border focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                <option value="">Select All</option>
                <option v-for="(option, index) in unitCostOptions" :key="index" :value="option.value">
                    {{ option.label }}
                </option>
            </select>
        </div>
        <!-- STATUS FILTER -->
        <div class="flex flex-col w-full sm:w-auto">
            <label class="text-xs font-bold mb-1 sm:mb-0">Status</label>
            <select v-model="status"
                class="h-8 sm:h-9 w-full sm:w-36 text-xs rounded-md text-gray-600 border focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                <option value="">Select All</option>
                <option v-for="(option, index) in filterStatus" :key="index" :value="option.value">
                    {{ option.label }}
                </option>
            </select>
        </div>

        <div class="w-full sm:w-auto mt-3 relative">
            <!-- Search Icon -->
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <!-- Search Input -->
            <input v-model="search" type="search" placeholder="Search item" 
                class="w-full sm:w-64 md:w-96 h-9 sm:h-10 rounded-full pl-10 pr-3 border text-sm focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
        </div>
    </div>
</template>