<script setup>
import { usePage, useForm, Link, router } from '@inertiajs/vue3';
import { reactive, computed, ref, watch } from 'vue';
import AddModal from './Modals/AddModal.vue';
import TableCell from './TableCell.vue';

const props = defineProps({
    accountableField: Array,
    firstDropdown: Array,
    secondDropdown: Array,
    quantityCostFields: Array,
    inputFields: Array,
    invoicesFundFields: Array,
    requestFields: Array,
    columns: Array,
    items: Array,
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
</script>
<template>
    <!-- <pre>{{ items }}</pre> -->
    <div class="flex flex-col sm:flex-row items-end mb-4 mt-[3rem]">
        <AddModal ref="addModalRef">
            <template #AddAssignButton>
                <button @click="addModalRef.openModal()"
                    class="flex items-center gap-2 bg-[#0E6021] hover:bg-[#2a9754] text-white font-semibold px-4 py-2 rounded-md text-xs sm:text-sm transition duration-150 w-full sm:w-auto">
                    <i class="fa-solid fa-plus"></i>
                    <span>Assign</span>
                </button>
            </template>

            <template #AcknowledgementForm="{ closeModal }">
                <form @submit.prevent="handleAddItem(closeModal)" class="flex flex-col gap-3 sm:overflow-y-auto">
                    <pre></pre>
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

                                <!-- CATEGORIES/SUPPLIERS/LOCATIONS DROPDOWN -->
                                <div class="flex md:flex-row gap-4 mb-4">
                                    <div v-for="fdp in firstDropdown" :key="fdp.name" class="flex flex-col">
                                        <label class="block text-sm font-bold mb-1">{{ fdp.label }}</label>
                                        <select
                                            class="w-full sm:w-[10rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                            <option value="">Select</option>
                                            <option v-for="item in props[fdp.name]" :key="item.id" :value="item.id">
                                                {{ item[fdp.option] || 'N/A' }}
                                            </option>
                                        </select>
                                        <!-- <div v-if="form.errors[fdp.model]" class="text-red-500 text-sm">{{
                                                form.errors[fdp.model] }}</div> -->
                                    </div>
                                </div>

                                <!-- UNIT/STATUS/DATE ACQUIRED FIELDS  -->
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

                                <!-- Quantity + Unit Cost -->
                                <div class="flex md:flex-row gap-4 mb-8">
                                    <div v-for="qcf in quantityCostFields" :key="qcf.quantityCostFields"
                                        class="flex flex-col">
                                        <label class="block text-sm font-bold mb-1">{{ qcf.label }}</label>
                                        <input :placeholder="qcf.placeholder" step="any"
                                            class="w-full sm:w-[15.6rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm text-gray-800 focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038] transition duration-150" />
                                    </div>
                                </div>

                                <!-- SERIAL NUMBERS -->
                                <!-- <div v-if="form.quantity > 0" class="flex flex-col gap-2 mt-4">
                                        <label class="block text-sm font-bold mb-1">Serial Numbers</label>
                                        <div v-for="(sn, index) in form.serial_numbers" :key="index">
                                            <input v-model="form.serial_numbers[index]" type="text"
                                                :placeholder="`SER-${String(index + 1).padStart(3, '0')}`"
                                                class="w-full sm:w-[rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                        </div>
                                    </div> -->

                                <!-- ITEM NAME/PAR-ICS-NUMBER/PROPERTY NUMBER -->
                                <div v-for="ip in inputFields" :key="ip.model" class="flex flex-col">
                                    <label class="block text-sm font-bold mb-1">{{ ip.label }}</label>
                                    <input :placeholder="ip.placeholder"
                                        class="w-full sm:w-[32rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                    <!-- <div v-if="form.errors[ip.model]" class="text-red-500 text-sm">{{
                                            form.errors[ip.model] }}</div> -->
                                </div>

                            </div>
                        </div>

                        <!-- RIGHT -->
                        <div class="space-y-4">
                            <!-- INVOICES & FUND SOURCES FIELDS -->
                            <div class="flex flex-col md:flex-row gap-4 mb-4">
                                <div v-for="inv in invoicesFundFields" :key="inv.model">
                                    <label class="block text-sm font-bold mb-1">{{ inv.label }}</label>
                                    <input :placeholder="inv.placeholder"
                                        class="w-full sm:w-[16.5rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                    <!-- <div v-if="form.errors[inv.model]" class="text-red-500 text-sm">{{
                                        form.errors[inv.model] }}</div> -->
                                </div>
                            </div>

                            <!-- PR/PO/REMARKS FIELDS -->
                            <div class="space-y-4">
                                <div v-for="rf in requestFields" :key="rf.model" class="flex flex-col">
                                    <label class="block text-sm font-bold mb-1 ">{{ rf.label }}</label>
                                    <input :placeholder="rf.placeholder"
                                        class="w-full sm:w-[34rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                    <!-- <div v-if="form.errors[rf.model]" class="text-red-500 text-sm">{{
                                        form.errors[rf.model] }}</div> -->
                                </div>
                            </div>

                            <!-- DESCRIPTION -->
                            <!-- <div>
                                <label class="block text-md font-semibold mb-1">Description</label>
                                <textarea v-model="form.description" placeholder="Input a description"
                                    class="w-full h-32 rounded-md border border-gray-300 px-3 py-2 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]"></textarea>
                                <div v-if="form.errors.description" class="text-red-500 text-sm">{{
                                    form.errors.description }}</div>
                            </div> -->

                            <!-- TOTAL AMOUNT -->
                            <!-- <div class="flex flex-col md:flex-row sm:items-center md:items-center text-sm font-semibold mt-8">
                                <label class="block text-base font-bold">Total Amount: ₱</label>
                                <input v-model="form.total_amount" readonly placeholder="0.00"
                                    class="block text-lg font-semibold text-gray-700 border border-none pointer-events-none" />
                            </div> -->
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
                        {{ col.label }}
                    </th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <tr v-for="item in list(items)" :key="item.id" class="even:bg-gray-200">
                    <TableCell v-for="col in columns" :key="col.key">
                        <template v-if="col.key !== 'action'">
                            <!-- If there's a format function: call it with (value, item).
               The format should return HTML string if you want v-html, or plain string. -->
                            <span v-if="col.format" v-html="col.format(getValue(item, col.key), item)"></span>

                            <!-- Otherwise show the resolved value -->
                            <span v-else>{{ getValue(item, col.key) ?? 'N/A' }}</span>
                        </template>

                        <!-- action column will be handled elsewhere -->
                    </TableCell>
                </tr>
            </tbody>
        </table>
    </div>

</template>