<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { defineProps, computed, ref } from 'vue';
import TableCell from './TableCell.vue';
import PrintButton from "@/Components/Buttons/PrintButton.vue";


const props = defineProps({
    // rooms: Array, // -> InventoryTable
    columns: Array, // -> InventoryTable
    rows: Object, // -> InventoryTable
    users: Array,
    unitCostOptions: Array, // InventoryFilter
    Status: Array, // -> InventoryFilter
    itemClass: Array, // InventoryForm
    suppliers: Object, // InventoryForm
    viewItems: Array, // InventoryForm
    inputFields: Array, // InventoryForm
    inputFieldsEdit: Array, // InventoryForm
    quantityCostFields: Array, // InventoryForm
    firstDropdown: Array, // InventoryForm
    secondDropdown: Array, // InventoryForm
    requestFields: Array, // InventoryForm
    invoicesFundFields: Array, // InventoryForm
    search: String, //InventoryFilter
    cost_range: String, //InventoryFilter
    status: String, // InventoryFilter
});

const emit = defineEmits(['view', 'edit', 'delete', 'update:selected', 'selection-changed']);


const page = usePage();
const user = computed(() => page.props.auth.user);
const userRole = computed(() => user.value?.role);

const canDelete = computed(() => ['admin'].includes(userRole.value));
const canEdit = computed(() => ['admin'].includes(userRole.value));

// PAGINATE
const goToPage = (url) => {
    if (!url) return;
    router.visit(url, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
};

function getValue(obj, path) {
    return path.split('.').reduce((acc, key) => acc?.[key], obj) ?? 'N/A'
}

// SELECTABLE ROWS/IDS
const selectedIDs = ref([]);

const allSelected = computed(() => {
    return props.rows?.data?.length > 0 &&
        props.rows.data.every(i => selectedIDs.value.includes(i.id));
});

function toggleSelectAll() {
    if (allSelected.value) {
        selectedIDs.value = [];
    } else {
        selectedIDs.value = props.rows.data.map(i => i.id);
    }

    emit("update:selected", selectedIDs.value);
    emit("selection-changed", selectedIDs.value);
}
function toggleCheck(item) {
    const id = item.id;

    if (selectedIDs.value.includes(id)) {
        selectedIDs.value = selectedIDs.value.filter(x => x !== id);
    } else {
        selectedIDs.value.push(id);
    }

    emit("update:selected", selectedIDs.value);
    emit("selection-changed", selectedIDs.value);
}


</script>

<template>
    <!-- <pre>{{ rows }}</pre> -->
    <!-- <div v-for="item in rows.data" :key="item.id">
      <pre>{{ item.acknowledgement_receipts }}</pre>
    </div> -->

    <!-- Table (horizontal scroll on small screens) -->
    <div class="overflow-x-auto mt-3">
        <table class="w-full table-auto border-collapse text-left bg-white text-xs sm:text-sm">
            <thead class="bg-[#850038]">
                <tr class="text-white">
                    <th v-for="col in props.columns" :key="col.key"
                        class="p-2 sm:p-3 md:p-4 first:rounded-tl-lg last:rounded-tr-lg">

                        <!-- SELECT ALL CHECKBOX -->
                        <template v-if="col.key === 'select_all'">
                            <input type="checkbox" class="w-4 h-4" :checked="allSelected" @change="toggleSelectAll" />
                        </template>

                        <!-- NORMAL HEADER -->
                        <template v-else>
                            {{ col.label }}
                        </template>
                    </th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                <tr v-for="item in props.rows.data" :key="item.id" class="even:bg-gray-200">
                    <TableCell v-for="col in props.columns" :key="col.key">

                        <!-- ROW CHECKBOX -->
                        <template v-if="col.key === 'select_all'">
                            <input type="checkbox" class="w-4 h-4 text-[#0E6021]"
                                :checked="selectedIDs.includes(item.id)" @click="toggleCheck(item)" />
                        </template>

                        <!-- NORMAL CELLS -->
                        <template v-else-if="col.key !== 'action'">
                            <span v-if="col.format" v-html="col.format(getValue(item, col.key))"></span>
                            <span v-else>{{ getValue(item, col.key) ?? 'N/A' }}</span>
                        </template>

                        <!-- ACTION BUTTONS -->
                        <template v-else>
                            <div class="flex items-center gap-2">
                                <PrintButton :item="item" @print="$emit('print', $event)" />

                                <button @click="$emit('view', item)" class="text-[#3F3F3F] hover:text-[#191818]"
                                    title="View">
                                    <i class="fa-solid fa-eye"></i>
                                </button>

                                <button @click="$emit('edit', item)" class="text-[#54B3AB] hover:text-[#38a69d]"
                                    title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <button @click="$emit('delete', item)" class="text-[#D71D1D] hover:text-[#c50e0e]"
                                    title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </template>
                    </TableCell>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-2 flex justify-end mx-2">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 
                bg-gray-100 border border-gray-300 rounded-md px-3 py-1">
            <p class="text-xs sm:text-sm border-r border-gray-300 px-3">
                {{ rows.from }}-{{ rows.to }} of
                {{ rows.total }}
            </p>
            <div>
                <span v-for="link in rows.links" :key="link.label">
                    <span v-if="link.url" @click="goToPage(link.url)" class="cursor-pointer p-1 text-xs sm:text-sm"
                        :class="{
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
</template>