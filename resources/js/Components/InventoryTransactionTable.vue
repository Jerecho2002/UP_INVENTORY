<script setup>
import TableCell from './TableCell.vue';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';
import { Link, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    tableHeader: Array,
    accountableField: Array,
    itemSelectedField: Array,
    inputFields: Array,
    items: Object,
})

const form = useForm({
    inventory_item_id: [],
    accountable_persons_id: "",
    issued_by_id: "",
    created_by: "",
    par_date: "",
    remarks: "",
});

const search = ref(props.search || '');
const cost_range = ref(props.cost_range || '');
const status = ref(props.status || '');

const addModalRef = ref(null);
// const editModalRef = ref(null);

// function openEditModal(item, open) {
//     isEditing.value = true;
//     editModalRef.value = item;
//     form.id = item.id;
//     form.item_classification_id = item.item_classification_id;
//     form.supplier_id = item.supplier_id;
//     form.invoice = item.invoice;
//     form.fund_source = item.fund_source;
//     form.item_name = item.item_name;
//     form.description = item.description;
//     form.category = item.category;
//     form.quantity = item.quantity;
//     form.unit = item.unit;
//     form.unit_cost = item.unit_cost;
//     form.total_amount = item.total_amount;
//     form.property_number = item.property_number;
//     form.serial_number = item.serial_number;
//     form.pr_number = item.pr_number;
//     form.po_number = item.po_number;
//     form.remarks = item.remarks;
//     form.date_acquired = item.date_acquired ? item.date_acquired.split("T")[0] : "";
//     form.status = item.status;
//     open();
// }

const handleUpdateItem = (id, closeModal) => {
    form.put(route("items.update", id), {
        onSuccess: () => {
            closeModal(),
                form.reset();
            isEditing.value = false;
            showEditSuccess.value = true;
        }
    });
};

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
    <!-- <pre>{{ items }}</pre> -->
    <div class="bg-white rounded-lg mt-8 ">
        <div>
            <h1 class="flex p-4 text-[#850038] font-bold text-xl">Transaction History</h1>
        </div>

        <!-- IMPORT AND EXPORT -->
        <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-3 justify-end mx-4">
            <div class="flex gap-2">
                <button
                    class="flex items-center justify-center gap-2 bg-[#20C6D2] text-white font-semibold rounded-md h-[2.5rem] sm:h-[3rem] px-5 hover:bg-[#49dce6] transition duration-150">
                    <i class="fa-solid fa-file-export"></i>
                    <span>Import</span>
                </button>
                <!-- Export button -->
                <button
                    class="flex items-center justify-center gap-2 bg-[#20C6D2] text-white font-semibold rounded-md h-[2.5rem] sm:h-[3rem] px-5 hover:bg-[#49dce6] transition duration-150">
                    <i class="fa-solid fa-file-export"></i>
                    <span>Export</span>
                </button>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-end justify-between mx-4 gap-4">
            <!-- RE-ASSIGN / UPDATE -->
            <div class="mt-6 flex w-full">
                <AddModal ref="addModalRef">
                    <template #ReAssignButton>
                        <button @click="addModalRef.openModal()"
                            class="flex items-center gap-2 bg-[#5A0F43] hover:bg-[#781b5c] text-white font-semibold px-4 py-3 rounded-md text-xs sm:text-sm transition duration-150 w-full sm:w-auto">
                            <i class="fa-solid fa-right-left"></i>
                            <span>Re-Assign</span>
                        </button>
                    </template>

                    <template #ReAssignForm="{ closeModal }">
                        <form @submit.prevent="handleAddItem(closeModal)"
                            class="flex flex-col gap-3 sm:overflow-y-auto ">
                            <h2 class="text-2xl font-bold text-[#850038] mb-6">Re-Assign</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <!-- LEFT -->
                                <div class="space-y-4 col-span-1 md:col-span-1">
                                    <div class="space-y-4">
                                        <!-- ACCOUNTABLE/ISSUED/CREATED DROPDOWN -->
                                        <div class="flex flex-col md:flex-row gap-4">
                                            <div v-for="accf in accountableField" :key="accf.name"
                                                class="flex flex-col">
                                                <label class="block text-sm font-bold mb-1">{{ accf.label }}</label>
                                                <select v-model="form[accf.model]" :key="accf.model"
                                                    class="w-full sm:w-[11rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                                    <option value="">Select</option>
                                                    <option v-for="item in props[accf.name]" :key="item.id"
                                                        :value="item[accf.value]">
                                                        {{ item[accf.option] || 'N/A' }}
                                                    </option>
                                                </select>
                                                <div v-if="form.errors[accf.model]" class="text-red-500 text-sm">{{
                                                    form.errors[accf.model] }}</div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold mb-1">Par Date</label>
                                                <input v-model="form.par_date"
                                                    class="rounded-md w-full  border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038']"
                                                    type="date" />
                                                <div v-if="form.errors.par_date" class="text-red-500 text-sm">
                                                    {{ form.errors.par_date }}
                                                </div>
                                            </div>
                                        </div>


                                        <!-- ROOMS -->
                                        <div v-for="ip in inputFields" :key="ip.model" class="flex flex-col">
                                            <label class="block text-sm font-bold mb-1">{{ ip.label }}</label>
                                            <input :placeholder="ip.placeholder"
                                                class="w-full sm:w-[33.4rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                            <div v-if="form.errors[ip.model]" class="text-red-500 text-sm">{{
                                                form.errors[ip.model] }}</div>
                                        </div>

                                        <!-- REMARKS -->
                                        <div>
                                            <label class="block text-md font-semibold mb-1">Remarks</label>
                                            <textarea v-model="form.remarks" placeholder="Input a remarks"
                                                class="w-full sm:w-[33.4rem] h-48 rounded-md border border-gray-300 px-3 py-2 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]"></textarea>
                                            <div v-if="form.errors.remarks" class="text-red-500 text-sm">{{
                                                form.errors.remarks }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- RIGHT -->
                                
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

                <EditModal ref="editModalRef">
                    <template #UpdateAssignButton>
                        <button @click="() => openEditModal(item, open)"
                            class="flex items-center gap-2 bg-[#54B3AB] ml-3 hover:bg-[#60bdb5] text-white font-semibold px-4 py-3 rounded-md text-xs sm:text-sm transition duration-150 w-full sm:w-auto">
                            <i class="fa-solid fa-rotate"></i>
                            <span>Update</span>
                        </button>
                    </template>

                    <template #UpdateReAssignForm="{ closeModal }">
                        <form @submit.prevent="handleUpdateItem(form.id, closeModal)"
                            class="flex flex-col gap-3 sm:overflow-y-auto ">
                            <h2 class="text-2xl font-bold text-[#850038] mb-6">Re-Assign</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="space-y-4 col-span-1 md:col-span-1">
                                    <div class="space-y-4">
                                        <!-- ACCOUNTABLE/ISSUED DROPDOWN -->
                                        <div class="flex flex-col md:flex-row gap-4">
                                            <div v-for="accf in accountableField" :key="accf.name"
                                                class="flex flex-col">
                                                <label class="block text-sm font-bold mb-1">{{ accf.label }}</label>
                                                <select v-model="form[accf.model]" :key="accf.model"
                                                    class="w-full sm:w-[11rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                                    <option value="">Select</option>
                                                    <option v-for="item in props[accf.name]" :key="item.id"
                                                        :value="item[accf.value]">
                                                        {{ item[accf.option] || 'N/A' }}
                                                    </option>
                                                </select>
                                                <div v-if="form.errors[accf.model]" class="text-red-500 text-sm">{{
                                                    form.errors[accf.model] }}</div>
                                            </div>
                                        
                                        <!-- PAR DATE -->
                                            <div>
                                                <label class="block text-sm font-semibold mb-1">Par Date</label>
                                                <input v-model="form.par_date"
                                                    class="rounded-md w-full  border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038']"
                                                    type="date" />
                                                <div v-if="form.errors.par_date" class="text-red-500 text-sm">
                                                    {{ form.errors.par_date }}
                                                </div>
                                            </div>
                                        </div>


                                        <!-- ROOMS -->
                                        <div v-for="ip in inputFields" :key="ip.model" class="flex flex-col">
                                            <label class="block text-sm font-bold mb-1">{{ ip.label }}</label>
                                            <input :placeholder="ip.placeholder"
                                                class="w-full sm:w-[33.4rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                            <div v-if="form.errors[ip.model]" class="text-red-500 text-sm">{{
                                                form.errors[ip.model] }}</div>
                                        </div>

                                        <!-- REMARKS -->
                                        <div>
                                            <label class="block text-md font-semibold mb-1">Remarks</label>
                                            <textarea v-model="form.remarks" placeholder="Input a remarks"
                                                class="w-full sm:w-[33.4rem] h-48 rounded-md border border-gray-300 px-3 py-2 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]"></textarea>
                                            <div v-if="form.errors.remarks" class="text-red-500 text-sm">{{
                                                form.errors.remarks }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- RIGHT -->
                                
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
                </EditModal>
            </div>

            <!-- RIGHT FILTERS -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 w-full">
                <!-- DATE -->
                <div class="w-full sm:w-56">
                    <input type="date" aria-label="Date"
                        class="w-full rounded-md cursor-pointer focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038] px-3" />
                </div>
                <!-- COST RANGE -->
                <div class="w-full sm:w-48">
                    <select v-model="cost_range" aria-label="Cost range"
                        class="w-full rounded-md cursor-pointer focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038] px-3">
                        <option value="">Cost Range</option>
                        <option value="0-50000">₱0 - ₱50,000</option>
                        <option value="50000-99999999">₱50,000 Above</option>
                    </select>
                </div>
                <!-- STATUS -->
                <div class="w-full sm:w-48">
                    <select v-model="status" aria-label="Status"
                        class="w-full rounded-md cursor-pointer focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038] px-3">
                        <option value="">Status</option>
                        <option value="1">Completed</option>
                        <option value="0">Pending</option>
                    </select>
                </div>
                <!-- SEARCH -->
                <div class="relative w-full sm:w-96">
                    <input type="text" placeholder="Search..." v-model="search" aria-label="Search"
                        class="w-full rounded-full pl-10 pr-4 border border-gray-300 focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038] text-sm bg-[#F8F8F8]" />
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">
            <table class=" w-full table-auto border-collapse mt-5 text-left bg-white text-xs sm:text-sm">
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