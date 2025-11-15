<script setup>
import { usePage, useForm, Link, router } from '@inertiajs/vue3';
import { reactive, computed, ref, watch } from 'vue';
import AddModal from './Modals/AddModal.vue';
import TableCell from './TableCell.vue';

const props = defineProps({
    accountableField: Array,
    secondDropdown: Array,
    inputFields: Array,
    itemSelectedField: Array,
    columns: Array,
    items: Array,
    rows: Array,
});

const addModalRef = ref(null);

function getValue(item, key) {
    if (!item || !key) return undefined;
    // if key is already a function (rare), call it with item
    if (typeof key === 'function') return key(item);

    return key.split('.').reduce((obj, part) => {
        return obj == null ? undefined : obj[part];
    }, item);
}

const list = (items) => {
    if (!items) return [];
    return Array.isArray(items) ? items : (items.data ?? []);
};

const emit = defineEmits(['update:selected', 'selection-changed']);

// SELECTION SET OF IDS
const selected = ref(new Set());

// CURRENT VISIBLE ROWS
const currentRows = computed(() => list(props.items));

// ALL SELECTED CHECK
const allSelected = computed(() => {
    const rows = currentRows.value;
    return rows.length > 0 && rows.every(r => selected.value.has(r.id));
});


const anySelected = computed(() => selected.value.size > 0);

//TOGGLE SINGLE ROW
function toggleRow(item) {
    if (!item || item.id == null) return;
    if (selected.value.has(item.id)) selected.value.delete(item.id);
    else selected.value.add(item.id);
    emit('update:selected', Array.from(selected.value));
    emit('selection-changed', Array.from(selected.value));
}

// TOGGLE ALL VISIBLE ROWS
function toggleAllVisible() {
    const rows = currentRows.value;
    if (allSelected.value) {
        rows.forEach(r => selected.value.delete(r.id));
    } else {
        rows.forEach(r => selected.value.add(r.id));
    }
    emit('update:selected', Array.from(selected.value));
    emit('selection-changed', Array.from(selected.value));
}

watch(currentRows, (newRows) => {
    const idsInPage = new Set(newRows.map(r => r.id));
    const newSel = new Set(Array.from(selected.value).filter(id => idsInPage.has(id)));
    selected.value = newSel;
    emit('update:selected', Array.from(selected.value));
    emit('selection-changed', Array.from(selected.value));
});
</script>

<template>
    <!-- <pre>{{ items }}</pre> -->
    <div class="flex flex-col sm:flex-row items-end mb-4 mt-[3rem]">
        <AddModal ref="addModalRef">
            <template #AddAssignButton>
                <button @click="addModalRef.openModal()"
                    class="flex items-center gap-2 bg-[#0E6021]  hover:bg-[#2a9754] text-white font-semibold px-4 py-2 rounded-md text-xs sm:text-sm transition duration-150 w-full sm:w-auto">
                    <i class="fa-solid fa-user-check"></i>
                    <span>Assign</span>
                </button>
            </template>

            <template #AcknowledgementForm="{ closeModal }">
                <form @submit.prevent="handleAddItem(closeModal)" class="flex flex-col gap-3 sm:overflow-y-auto ">
                    <h2 class="text-2xl font-bold text-[#850038] mb-6">Assign</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- LEFT -->
                        <div class="space-y-4 col-span-1 md:col-span-1">
                            <div class="space-y-4">
                                <!-- ACCOUNTABLE/ISSUED/CREATED DROPDOWN -->
                                <div class="flex flex-col md:flex-row gap-4">
                                    <div v-for="acF in props.accountableField" :key="acF.name" class="flex-col">
                                        <label class="block text-sm font-bold mb-1">{{ acF.label }}</label>
                                        <select
                                            class="w-full sm:w-[10rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                            <option value="">Select</option>
                                            <option>
                                                {{ acF.option }}
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <!-- STATUS/DATE ACQUIRED FIELDS  -->
                                <div class="flex md:flex-row gap-4 mb-8">
                                    <div v-for="sdf in secondDropdown" :key="sdf.label" class="flex gap-3">
                                        <div>
                                            <label class="block text-sm font-bold mb-1">{{ sdf.label }}</label>
                                            <select
                                                class="w-full sm:w-[10rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                                <option value="">Select</option>
                                                <option v-for="op in sdf.options" :key="op.value" :value="op.value">
                                                    {{
                                                        op.label }}</option>
                                            </select>
                                            <!-- <div v-if="form.errors[sdf.model]" class="text-red-500 text-sm">{{
                                                    form.errors[sdf.model] }}</div> -->
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold mb-1">Date Acquired</label>
                                        <input
                                            class="rounded-md w-full sm:w-[10rem] border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]"
                                            type="date" />
                                    </div>
                                </div>

                                <!-- ROOMS -->
                                <div v-for="ip in inputFields" :key="ip.model" class="flex flex-col">
                                    <label class="block text-sm font-bold mb-1">{{ ip.label }}</label>
                                    <input :placeholder="ip.placeholder"
                                        class="w-full sm:w-[32rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                    <!-- <div v-if="form.errors[ip.model]" class="text-red-500 text-sm">{{
                                            form.errors[ip.model] }}</div> -->
                                </div>

                                <!-- REMARKS -->
                                <div>
                                    <label class="block text-md font-semibold mb-1">Remarks</label>
                                    <textarea placeholder="Input a remarks"
                                        class="w-full sm:w-[32rem] h-32 rounded-md border border-gray-300 px-3 py-2 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]"></textarea>
                                    <!-- <div v-if="form.errors.description" class="text-red-500 text-sm">{{
                                    form.errors.description }}</div> -->
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT -->
                        <div class="space-y-4">
                            <!-- ITEM SELECTED -->
                            <div v-for="select in itemSelectedField" :key="select.model">
                                <label class="block text-sm font-bold mb-1">{{ select.label }}</label>
                                <div class="bg-[#F8F8F8] border border-gray-300 rounded-md h-[24.6rem]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end items-center gap-4">
                        <button type="button" @click="closeModal"
                            class="border border-gray-400 px-6 py-4 rounded-full text-sm font-semibold hover:bg-gray-100">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-[#0E6021] text-white px-8 py-4 rounded-full text-sm font-semibold hover:bg-green-800">
                            Assign
                        </button>
                    </div>
                </form>
            </template>
        </AddModal>

        <!-- SEARCH INPUT -->
        <div class="relative ml-auto w-full sm:w-72 md:w-96 mt-3">
            <!-- Icon -->
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <!-- Input -->
            <input type="search" placeholder="Search item" v-model="search"
                class="w-full h-10 rounded-full pl-10 pr-4 text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
        </div>
    </div>

    <!-- Inventory Acknowledgements Table Component -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse text-left bg-white text-xs sm:text-sm">
            <thead class="bg-[#850038]">
                <tr class="text-white">
                    <th v-for="col in columns" :key="col.key"
                        class="p-2 sm:p-3 md:p-4 align-middle first:rounded-tl-lg last:rounded-tr-lg">
                        <!-- If this is the select column, show select-all checkbox -->
                        <template v-if="col.key === 'select'">
                            <input type="checkbox" class="w-4 h-4 text-[#0E6021]     rounded" :checked="allSelected"
                                @change="toggleAllVisible" aria-label="Select all" />
                        </template>

                        <!-- Otherwise show normal header label -->
                        <template v-else>
                            {{ col.label }}
                        </template>
                    </th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                <tr v-for="item in currentRows" :key="item.id" class="even:bg-gray-200">
                    <TableCell v-for="col in columns" :key="col.key" class="align-middle">
                        <!-- Checkbox column -->
                        <template v-if="col.key === 'select'">
                            <input type="checkbox" class="w-4 h-4 text-[#0E6021] rounded"
                                :checked="selected.has(item.id)" @change.prevent="toggleRow(item)"
                                :aria-label="`Select item ${item.id}`" />
                        </template>

                        <!-- Normal cells -->
                        <template v-else-if="col.key !== 'action'">
                            <span v-if="col.format" v-html="col.format(getValue(item, col.key), item)"></span>
                            <span v-else>{{ getValue(item, col.key) ?? 'N/A' }}</span>
                        </template>

                    </TableCell>
                </tr>
            </tbody>
        </table>
    </div>

</template>