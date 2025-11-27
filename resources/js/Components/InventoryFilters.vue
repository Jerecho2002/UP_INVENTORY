<script setup>
import { router } from '@inertiajs/vue3';
import { watch, ref } from 'vue';
import { debounce } from 'lodash';

const props = defineProps({
    unitCostOptions: { type: Array, default: () => [] },
    filterStatus: { type: Array, default: () => [] },
    search: { type: String, default: '' },
    status: { type: String, default: '' },
    cost_range: { type: String, default: '' },
    mode: { type: String, default: 'inventory' },
});


const search = ref(props.search || '');
const cost_range = ref(props.cost_range || '');
const status = ref(props.status || '');

const emit = defineEmits(['update:search', 'update:status', 'update:cost_range']);

// INVENTORY FETCH
function fetchInventory(params = {}) {
    router.get('/inventory/items', params, {
        preserveState: true,
        preserveScroll: true,
        only: ['items'],
    });
}

const debouncedFetchInventory = debounce(fetchInventory, 300);

//DASHBOARD FETCH
function fetchDashboardSearch(value) {
    router.get('/dashboard', { search: value }, {
        preserveState: true,
        replace: true,  
        preserveScroll: true,
    });
};

const debouncedFetchDashboard = debounce(fetchDashboardSearch, 300);

//ACKNOWLEDGEMENT FETCH
function fetchAcknowledgmentSearch(value) {
    router.get('/inventory/acknowledgements', { search: value }, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
};

const debouncedFetchAcknowledgement = debounce(fetchAcknowledgmentSearch, 300);

//TRANSACTION FETCH
function fetchTransactionSearch(value) {
    router.get('/inventory/transactions', value, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
};

const debouncedFetchTransaction = debounce(fetchTransactionSearch, 300);

// WATCHERS
watch(search, (value) => {
    if (props.mode === "inventory") {
        debouncedFetchInventory({
            search: value,
            cost_range: cost_range.value,
            status: status.value
        });
    } else if (props.mode === "dashboard") {
        debouncedFetchDashboard(value);
    } else if (props.mode === "acknowledgements") {
        debouncedFetchAcknowledgement(value);
    } else if (props.mode === "transactions") {
        debouncedFetchTransaction({
            search: value,
            cost_range: cost_range.value,
            status: status.value
        });
    }
    emit("update:search", value);
});

watch(status, (value) => {
    if (props.mode === "inventory") {
        debouncedFetchInventory({
            search: search.value,
            cost_range: cost_range.value,
            status: value
        });
    } else if (props.mode === "transactions") {
        debouncedFetchTransaction({
            search: search.value,
            cost_range: cost_range.value,
            status: value
        });
    } else if (props.mode === "acknowledgements") {
        debouncedFetchAcknowledgement({
            search: search.value,
            cost_range: cost_range.value,
            status: value
        });
    }

    emit("update:status", value);
});

watch(cost_range, (value) => {
    if (props.mode === "inventory") {
        debouncedFetchInventory({
            search: search.value,
            cost_range: value,
            status: status.value
        });
    } else if (props.mode === "transactions") {
        debouncedFetchTransaction({
            search: search.value,
            cost_range: value,
            status: status.value
        });
    }

    emit("update:cost_range", value);
});

</script>

<template>
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-end gap-4 w-full sm:w-auto">
        <!-- UNIT COST -->
        <div class="flex flex-col w-full sm:w-auto" v-for="(group, gIndex) in unitCostOptions" :key="gIndex">
            <label class="text-xs font-bold mb-1 sm:mb-0">{{ group.label }}</label>
            <select v-model="cost_range"
                class="h-8 sm:h-9 w-full sm:w-36 text-xs rounded-md text-gray-600 border focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                <option value="">Select All</option>
                <option v-for="(option, uIndex) in group.options" :key="uIndex" :value="option.value">
                    {{ option.label }}
                </option>
            </select>
        </div>

        <!-- STATUS -->
        <div class="flex flex-col w-full sm:w-auto" v-for="(stats, gIndex) in filterStatus" :key="gIndex">
            <label class="text-xs font-bold mb-1 sm:mb-0"> {{ stats.label }}</label>
            <select v-model="status"
                class="h-8 sm:h-9 w-full sm:w-36 text-xs rounded-md text-gray-600 border focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                <option value="">Select All</option>
                <option v-for="(option, sIndex) in stats.options" :key="sIndex" :value="option.value">
                    {{ option.label }}
                </option>
            </select>
        </div>

        <!-- SEARCH BAR -->
        <div class="w-full sm:w-auto mt-3 relative">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input v-model="search" type="search" placeholder="Search item"
                class="w-full sm:w-64 md:w-96 h-9 sm:h-10 rounded-full pl-10 pr-3 border text-sm focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
        </div>
    </div>
</template>