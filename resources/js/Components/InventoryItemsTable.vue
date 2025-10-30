<script setup>
import TableCell from './TableCell.vue';
import { usePage, useForm, Link, router } from '@inertiajs/vue3';
import { reactive, computed, ref, watch } from 'vue';
import { debounce } from 'lodash';
import AddModal from "./Modals/AddModal.vue";
import ViewModal from './Modals/ViewModal.vue';
import EditModal from './Modals/EditModal.vue';
import DeleteModal from './Modals/DeleteModal.vue';

const props = defineProps({
    searchItem: String,
    selectedCostRange: String,
    columns: Array,
    rows: Array,
    itemClass: Array,
    suppliers: Array,
    locations: Array,
    invoices: Array,
    fundSources: Array,
    viewItems: Array,
    inputFields: Array,
    quantityCostFields: Array,
    firstDropdown: Array,
    secondDropdown: Array,
    requestFields: Array,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const userRole = computed(() => user.value?.role);

const canDelete = computed(() => ['admin'].includes(userRole.value));
const canEdit = computed(() => ['admin'].includes(userRole.value));

const addModalRef = ref(null);
const editModalRef = ref(null);
const selectedViewItem = ref(null);

const form = useForm({
    item_classification_id: "",
    supplier_id: "",
    location_id: "",
    invoice_id: "",
    fund_source_id: "",
    item_name: "",
    description: "",
    category: "",
    quantity: "",
    unit: "",
    unit_cost: "",
    total_amount: "",
    property_number: "",
    serial_numbers: [],
    pr_number: "",
    po_number: "",
    remarks: "",
    date_acquired: "",
    status: "",
});

const search = ref(props.searchItem || '');
const selectedCostRange = ref(props.selectedCostRange || '');
const calculateTotalAmount = () => {
    form.total_amount = form.quantity * form.unit_cost;
};

watch(
    () => [form.quantity, form.unit_cost],
    calculateTotalAmount
);

watch(
    search,
    debounce((output) => {
        router.get('/inventory/items', { search: output, cost_range: selectedCostRange.value }, { preserveState: true, only: ['items'] });
    })
);

watch(
    selectedCostRange,
    debounce((range) => {
        router.get('/inventory/items', { search: search.value, cost_range: range }, { preserveState: true });
    })
)

watch(() => form.item_classification_id, (newVal) => {
    if (!newVal) return;

    // Find the selected classification object
    const selectedClass = props.itemClass.find(c => c.id === newVal);

    if (selectedClass) {
        const code = selectedClass.classification_code;

        // Get current year and month (with leading zero for month)
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, "0");

        // Update the input fields (PAR/ICS and Property Number)
        if (code) {
            const formatted = `${code}-${year}-${month}-`;
            form.category = formatted;
            form.property_number = `${code}-`;
        } else {
            form.category = "";
            form.property_number = "";
        }
    }
});

watch(() => form.quantity, (newVal) => {
    const qty = parseInt(newVal);

    if (!qty || qty <= 0) {
        form.serial_numbers = [];
        return;
    }

    // Adjust number of fields based on quantity
    form.serial_numbers = Array.from({ length: qty }, (_, i) => form.serial_numbers[i] || "");
});

function openViewModal(item, open) {
    selectedViewItem.value = item; // store clicked row’s data
    open(); // call the modal’s exposed openModal()
}

function openEditModal(item, open) {
    editModalRef.value = item;
    form.id = item.id;
    form.item_classification_id = item.item_classification_id;
    form.supplier_id = item.supplier_id;
    form.location_id = item.location_id;
    form.invoice_id = item.invoice_id;
    form.fund_source_id = item.fund_source_id;
    form.item_name = item.item_name;
    form.description = item.description;
    form.category = item.category;
    form.quantity = item.quantity;
    form.unit = item.unit;
    form.unit_cost = item.unit_cost;
    form.total_amount = item.total_amount;
    form.property_number = item.property_number;
    form.serial_number = item.serial_number;
    form.pr_number = item.pr_number;
    form.po_number = item.po_number;
    form.remarks = item.remarks;
    form.date_acquired = item.date_acquired ? item.date_acquired.split("T")[0] : "";
    form.status = item.status;
    open();
}

const showSuccess = ref(false);

const handleAddItem = (closeModal) => {
    form.post(route("items.store"), {
        onSuccess: () => {
            closeModal();     // close the Add Item form modal
            form.reset();     // reset form data
            showSuccess.value = true;

            // Optional auto-close after 2 seconds
            // setTimeout(() => {
            //     showSuccess.value = false;
            // }, 2000);
        },
    });
};

const handleUpdateItem = (id, closeModal) => {
    form.put(route("items.update", id), {
        onSuccess: () => {
            closeModal(),
                form.reset();
        }
    });
};

const handleCloseModal = (closeModal) => {
    form.reset();
    closeModal();

};

const unitCostOptions = [
    { label: "₱0 - ₱50,000", value: "0-50000" },
    { label: "₱50,000 Above", value: "50000-100000" },
];

function getValue(obj, path) {
    return path.split('.').reduce((acc, key) => acc?.[key], obj) ?? 'N/A'
}
</script>

<template>
    <!-- Add button + filters + search -->
    <div class="flex flex-col sm:flex-row justify-between items-end gap-4 mb-4 mt-[5rem]">
        <AddModal ref="addModalRef">
            <!-- Add Item Button -->
            <template #AddItemButton>
                <button @click="addModalRef.openModal()"
                    class="flex gap-2 bg-[#0E6021] rounded-md text-white px-3 py-2 text-xs sm:text-sm hover:bg-[#2a9754] w-full sm:w-auto items-center justify-center">
                    <i class="fa-solid fa-plus my-[3px]"></i>
                    <span class="font-bold">Add Item</span>
                </button>
            </template>

            <!-- ADD FORM CONTENT -->
            <template #InventoryForm="{ closeModal }">
                <form @submit.prevent="handleAddItem(closeModal)" class="flex flex-col gap-3 sm:overflow-y-auto">
                    <!-- Header -->
                    <h2 class="text-2xl font-bold text-[#850038] mb-6">Add Item</h2>

                    <!-- Form Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Left Side -->
                        <div class="space-y-6 col-span-1 md:col-span-1">
                            <!-- Input Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-6">
                                <div class="space-y-4">
                                    <div v-for="ip in inputFields" :key="ip.model" class="flex flex-col">
                                        <label class="block text-sm font-bold mb-1">{{ ip.label }}</label>
                                        <input v-model="form[ip.model]" :key="ip.model" type="text"
                                            :placeholder="ip.placeholder"
                                            class="w-full sm:w-[30rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                        <div v-if="form.errors[ip.model]" class="text-red-500 text-sm">{{
                                            form.errors[ip.model] }}</div>
                                    </div>

                                    <!-- Quantity + Serial Numbers -->
                                    <div v-if="form.quantity > 0" class="flex flex-col gap-2 mt-4">
                                        <label class="block text-sm font-bold mb-1">Serial Numbers</label>
                                        <div v-for="(sn, index) in form.serial_numbers" :key="index">
                                            <input v-model="form.serial_numbers[index]" type="text"
                                                :placeholder="`SER-${String(index + 1).padStart(3, '0')}`"
                                                class="w-full sm:w-[30rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                        </div>
                                    </div>

                                    <!-- Quantity + Unit Cost -->
                                    <div class="flex gap-4 sm:gap-6 w-full">
                                        <div v-for="qcf in quantityCostFields" :key="qcf.quantityCostFields"
                                            class="flex flex-col flex-1 min-w-[8rem] sm:min-w-[10rem] md:min-w-[15rem] lg:min-w-[14.3rem]">
                                            <label class="block text-sm font-bold mb-1">{{ qcf.label }}</label>
                                            <input v-model="form[qcf.model]" :key="qcf.model" :type="qcf.type"
                                                :placeholder="qcf.placeholder" step="any"
                                                class="w-full rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm text-gray-800 focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038] transition duration-150" />
                                            <div v-if="form.errors[qcf.model]" class="text-red-500 text-sm">{{
                                                form.errors[qcf.model] }}</div>
                                        </div>
                                    </div>

                                    <!-- Request Fields -->
                                    <div class="space-y-4">
                                        <div v-for="rf in requestFields" :key="rf.model" class="flex flex-col">
                                            <label class="block text-sm font-bold mb-1 ">{{ rf.label }}</label>
                                            <input v-model="form[rf.model]" :key="rf.model" type="text"
                                                :placeholder="rf.placeholder"
                                                class="w-full sm:w-[30rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                            <div v-if="form.errors[rf.model]" class="text-red-500 text-sm">{{
                                                form.errors[rf.model] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div>
                            <!-- First Dropdown -->
                            <div class="flex flex-col md:flex-row gap-4 mb-8">
                                <div v-for="fdp in firstDropdown" :key="fdp.name" class="flex flex-col">
                                    <label class="block text-sm font-bold mb-1">{{ fdp.label }}</label>
                                    <select v-model="form[fdp.model]" :key="fdp.model"
                                        class="w-full sm:w-[6rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                        <option value="">Select</option>
                                        <option v-for="item in props[fdp.name]" :key="item.id" :value="item.id">
                                            {{ item[fdp.option] || 'N/A' }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors[fdp.model]" class="text-red-500 text-sm">{{
                                        form.errors[fdp.model] }}</div>
                                </div>
                            </div>

                            <!-- Second Dropdown -->
                            <div class="flex md:flex-row gap-3 mb-8">
                                <div v-for="sdf in secondDropdown" :key="sdf.label" class="flex gap-3">
                                    <div>
                                        <label class="block text-sm font-bold mb-1">{{ sdf.label }}</label>
                                        <select v-model="form[sdf.model]"
                                            class="w-full sm:w-[6.7rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                            <option value="">Select</option>
                                            <option v-for="op in sdf.options" :key="op.value" :value="op.value">{{
                                                op.label }}</option>
                                        </select>
                                        <div v-if="form.errors[sdf.model]" class="text-red-500 text-sm">{{
                                            form.errors[sdf.model] }}</div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold mb-1">Date Acquired</label>
                                    <input v-model="form.date_acquired"
                                        class="rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]"
                                        type="date" />
                                </div>
                            </div>

                            <div>
                                <label class="block text-md font-semibold mb-1">Description</label>
                                <textarea v-model="form.description" placeholder="Input a description"
                                    class="w-full h-36 rounded-md border border-gray-300 px-3 py-2 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]"></textarea>
                                <div v-if="form.errors.description" class="text-red-500 text-sm">{{
                                    form.errors.description }}</div>
                            </div>

                            <div class="flex flex-col md:flex-row md:items-center text-sm font-semibold mt-8">
                                <label class="block text-base font-bold">Total Amount:</label>
                                <input v-model="form.total_amount" readonly placeholder="₱0.00"
                                    class="block text-lg font-semibold text-gray-700 border border-none pointer-events-none" />
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
                            Add
                        </button>
                    </div>
                </form>
            </template>
        </AddModal>

        <!--Item Add Success Modal -->
        <transition name="fade">
            <div v-if="showSuccess" class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
                <div class="bg-white m-5 rounded-2xl shadow-lg p-8 w-[100%] sm:w-[35rem] text-center animate-fade-in">
                    <!-- Success Icon -->
                    <div class="mx-auto mb-2 flex items-center justify-center w-16 h-16 rounded-full bg-[#C8EFD4]">
                        <svg class="h-10 w-10" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M26.25 8.75H3.75V3.75H26.25V8.75ZM16.25 23.75C16.25 24.625 16.4125 25.4625 16.6875 26.25H5V10H25V16.3625C24.5875 16.3 24.175 16.25 23.75 16.25C19.6125 16.25 16.25 19.6125 16.25 23.75ZM18.75 16.25V14.375C18.75 14.025 18.475 13.75 18.125 13.75H11.875C11.525 13.75 11.25 14.025 11.25 14.375V16.25H18.75ZM25 22.5V18.75H22.5V22.5H18.75V25H22.5V28.75H25V25H28.75V22.5H25Z"
                                fill="#41BD66" />
                        </svg>

                    </div>

                    <!-- Message -->
                    <h2 class="text-2xl font-bold text-[#363232] mb-1">Added Success</h2>
                    <p class="text-[#888484] text-lg mb-6">
                        Item added successfully!
                    </p>

                    <div class="flex justify-center gap-3">
                        <button
                            class="flex bg-[#54B3AB] text-white px-6 py-3 items-center gap-2 rounded-md font-semibold hover:bg-[#6cc6bf] transition">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_2174_1494)">
                                    <path
                                        d="M11.9931 11.4467C14.5005 11.4467 16.5331 9.41407 16.5331 6.9067C16.5331 4.39933 14.5005 2.3667 11.9931 2.3667C9.48575 2.3667 7.45312 4.39933 7.45312 6.9067C7.45312 9.41407 9.48575 11.4467 11.9931 11.4467Z"
                                        fill="white" />
                                    <path
                                        d="M7.9999 17.7667C8.01376 17.3979 8.13666 17.0414 8.35308 16.7424C8.56949 16.4433 8.8697 16.2152 9.21576 16.0868C9.56182 15.9583 9.93818 15.9354 10.2973 16.0208C10.6564 16.1062 10.9821 16.2962 11.2332 16.5667L13.8066 19.3334L18.3666 14.2467C16.4831 12.9911 14.2633 12.3356 11.9999 12.3667C10.4298 12.327 8.8701 12.6317 7.4304 13.2594C5.9907 13.8872 4.70598 14.8226 3.66657 16.0001C3.57864 16.1173 3.53178 16.2602 3.53324 16.4067V20.0001C3.53308 20.3468 3.66805 20.6801 3.90951 20.929C4.15098 21.1779 4.47993 21.323 4.82657 21.3334H10.5399L8.49324 19.1334C8.32382 18.9501 8.19287 18.7348 8.10814 18.5C8.0234 18.2653 7.9866 18.0159 7.9999 17.7667Z"
                                        fill="white" />
                                    <path
                                        d="M19.1735 21.3332C19.5202 21.3228 19.8491 21.1777 20.0906 20.9288C20.3321 20.6798 20.467 20.3466 20.4669 19.9998V17.4932L17.0469 21.3332H19.1735Z"
                                        fill="white" />
                                    <path
                                        d="M22.5135 12.4132C22.4481 12.3541 22.3716 12.3086 22.2884 12.2792C22.2052 12.2499 22.1171 12.2373 22.029 12.2423C21.941 12.2472 21.8548 12.2696 21.7755 12.3081C21.6961 12.3466 21.6252 12.4005 21.5668 12.4666L13.8202 21.1332L10.3535 17.4066C10.2966 17.34 10.2272 17.2854 10.1491 17.2457C10.0711 17.206 9.98598 17.182 9.8987 17.1752C9.81141 17.1684 9.72363 17.1789 9.64038 17.206C9.55713 17.2331 9.48004 17.2763 9.41351 17.3332C9.35102 17.3952 9.30143 17.469 9.26758 17.5502C9.23373 17.6314 9.21631 17.7186 9.21631 17.8066C9.21631 17.8946 9.23373 17.9817 9.26758 18.063C9.30143 18.1442 9.35102 18.2179 9.41351 18.2799L13.8668 23.0799L22.5602 13.3332C22.6723 13.2034 22.73 13.0352 22.7213 12.8639C22.7126 12.6925 22.6382 12.5311 22.5135 12.4132Z"
                                        fill="white" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_2174_1494">
                                        <rect width="24" height="24" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                            <span>Assign</span>
                        </button>
                        <!-- Close Button -->
                        <button @click="showSuccess = false"
                            class="bg-[#41BD66] text-white px-6 py-3 rounded-md font-semibold hover:bg-green-800 transition">
                            <span>Confirm</span>
                        </button>
                    </div>
                </div>
            </div>
        </transition>


        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full sm:w-auto">
            <!-- UNIT COST FILTER -->
            <div class="flex flex-col w-full sm:w-auto">
                <label class="text-xs font-bold mb-1 sm:mb-0">Unit Cost</label>
                <select v-model="selectedCostRange"
                    class="h-8 sm:h-9 w-full sm:w-36 text-xs rounded-md text-gray-600 border focus:ring-2 focus:ring-[#850038] outline-none">
                    <option value="">Select All</option>
                    <option v-for="(option, index) in unitCostOptions" :key="index" :value="option.value">
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
                <input type="search" placeholder="Search item" v-model="search"
                    class="w-full sm:w-64 md:w-96 h-9 sm:h-10 rounded-full pl-10 pr-3 border text-sm focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
            </div>
        </div>
    </div>

    <div>
        <!-- Table (horizontal scroll on small screens) -->
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
                    <tr v-for="item in rows.data" :key="item.id" class="even:bg-gray-200">
                        <TableCell v-for="col in columns" :key="col.key">
                            <!-- For normal columns -->
                            <template v-if="col.key !== 'action'">
                                <span v-if="col.format" v-html="col.format(getValue(item, col.key))"></span>
                                <span v-else>{{ getValue(item, col.key) }}</span>
                            </template>

                            <!-- For Action column -->
                            <template v-else>
                                <div class="flex items-center gap-1">

                                    <!-- VIEW MODAL -->
                                    <ViewModal>
                                        <!-- BUTTON -->
                                        <template #ViewItemButton="{ open }">
                                            <button type="button" class="text-blue-600 mx-1" title="View"
                                                @click="() => openViewModal(item, open)">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </template>

                                        <!-- BODY CONTENT -->
                                        <template #ViewBodyContent>
                                            <div v-if="selectedViewItem" class="font-bold text-2xl mb-4 text-[#850038]">
                                                <h2>Item Details</h2>
                                            </div>

                                            <!-- DETAILS GRID -->
                                            <div v-if="selectedViewItem" class="grid grid-cols-1 md:grid-cols-1 gap-2">
                                                <div class="space-y-3 mt-5">
                                                    <div v-for="col in viewItems" :key="col.label"
                                                        class="flex md:items-start gap-3">
                                                        <!-- LABEL -->
                                                        <label
                                                            class="w-40 shrink-0 font-semibold text-gray-700 text-left">
                                                            {{ col.label }}
                                                        </label>

                                                        <!-- VALUE (plain or formatted) -->
                                                        <p v-if="!col.format"
                                                            class="text-gray-800 leading-snug break-words whitespace-normal max-w-[calc(100%-10rem)]">
                                                            {{ getValue(selectedViewItem, col.key) ?? 'N/A' }}
                                                        </p>

                                                        <div v-else
                                                            v-html="col.format(getValue(selectedViewItem, col.key))"
                                                            class="text-gray-800 leading-snug break-words whitespace-normal max-w-[calc(100%-10rem)]">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </ViewModal>


                                    <!-- EDIT MODAL -->
                                    <EditModal ref="editModalRef">
                                        <template #EditItemButton="{ open }">
                                            <button type="button" class="text-[#54B3AB] hover:text[#54B3AB] mx-1"
                                                title="Edit" @click="() => openEditModal(item, open)">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                        </template>

                                        <template #EditInventory="{ closeModal }">
                                            <form @submit.prevent="handleUpdateItem(form.id, closeModal)"
                                                class="flex flex-col gap-3 sm:overflow-y-auto">
                                                <h2 class="text-2xl font-bold text-[#850038] mb-6">Edit Item</h2>

                                                <!-- Form Grid -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    <!-- left side -->
                                                    <div class="space-y-6 col-span-1 md:col-span-1">
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-6">
                                                            <div class="space-y-5">
                                                                <div v-for="ip in inputFields" :key="ip.model"
                                                                    class="flex flex-col">
                                                                    <label class="block text-sm font-bold mb-1">{{
                                                                        ip.label }}</label>
                                                                    <input v-model="form[ip.model]"
                                                                        :placeholder="ip.placeholder" type="text" class="w-full sm:w-[30rem] rounded-md border border-gray-300 px-3 py-3
                          bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038]
                          focus:outline-none focus:border-[#850038]" />
                                                                    <div v-if="form.errors[ip.model]"
                                                                        class="text-red-500 text-sm">
                                                                        {{ form.errors[ip.model] }}
                                                                    </div>
                                                                </div>

                                                                <!-- Quantity + Cost -->
                                                                <div class="flex gap-4 sm:gap-6 w-full">
                                                                    <div v-for="qcf in quantityCostFields"
                                                                        :key="qcf.model"
                                                                        class="flex flex-col flex-1 min-w-[8rem] sm:min-w-[10rem] md:min-w-[15rem] lg:min-w-[14.3rem]">
                                                                        <label class="block text-sm font-bold mb-1">{{
                                                                            qcf.label }}</label>
                                                                        <input v-model="form[qcf.model]"
                                                                            :type="qcf.type"
                                                                            :placeholder="qcf.placeholder" step="any"
                                                                            class="w-full rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8]
                            text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none
                            focus:border-[#850038]" />
                                                                        <div v-if="form.errors[qcf.model]"
                                                                            class="text-red-500 text-sm">
                                                                            {{ form.errors[qcf.model] }}
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Request Fields -->
                                                                <div v-for="rf in requestFields" :key="rf.model"
                                                                    class="flex flex-col">
                                                                    <label class="block text-sm font-bold mb-1">{{
                                                                        rf.label }}</label>
                                                                    <input v-model="form[rf.model]" type="text"
                                                                        :placeholder="rf.placeholder" class="w-full sm:w-[30rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8]
                          text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- right side -->
                                                    <div class="flex flex-col">
                                                        <!-- firstDropdown -->
                                                        <div class="flex flex-col md:flex-row gap-3 mb-8">
                                                            <div v-for="fdp in firstDropdown" :key="fdp.name"
                                                                class="flex flex-col">
                                                                <label class="block text-sm font-bold mb-1">{{ fdp.label
                                                                    }}</label>
                                                                <select v-model="form[fdp.model]" class="w-full sm:w-[6rem] rounded-md border border-gray-300 px-3 py-3
                         bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038]
                         focus:outline-none focus:border-[#850038]">
                                                                    <option value="">Select</option>
                                                                    <option v-for="item in props[fdp.name]"
                                                                        :key="item.id" :value="item.id">
                                                                        {{ item[fdp.option] || 'N/A' }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <!-- secondDropdown -->
                                                        <div class="flex flex-col md:flex-row gap-3 mb-3">
                                                            <div v-for="sdf in secondDropdown" :key="sdf.label"
                                                                class="flex gap-3">
                                                                <div>
                                                                    <label class="block text-sm font-bold mb-1">{{
                                                                        sdf.label }}</label>
                                                                    <select v-model="form[sdf.model]" class="w-full sm:w-[6.7rem] rounded-md border border-gray-300 px-3 py-3
                           bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038]
                           focus:outline-none focus:border-[#850038]">
                                                                        <option value="">Select</option>
                                                                        <option v-for="op in sdf.options"
                                                                            :key="op.value" :value="op.value">
                                                                            {{ op.label }}
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-bold mb-1">Date
                                                                    Acquired</label>
                                                                <input v-model="form.date_acquired" type="date" class="rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8]
                        text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none
                        focus:border-[#850038]" />
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <label
                                                                class="block text-md font-semibold mt-4">Description</label>
                                                            <textarea v-model="form.description" class="w-full h-36 rounded-md border border-gray-300 px-3 py-2 bg-[#F8F8F8]
                         text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none
                         focus:border-[#850038]"></textarea>
                                                        </div>

                                                        <div
                                                            class="flex flex-col md:flex-row items-center text-sm font-semibold mt-8">
                                                            <label class="block text-base font-bold">Total
                                                                Amount:</label>
                                                            <input v-model="form.total_amount" readonly
                                                                placeholder="₱0.00"
                                                                class="block text-lg font-semibold text-gray-700 border border-none pointer-events-none" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Buttons -->
                                                <div class="flex justify-end items-center gap-4">
                                                    <button type="button" @click="handleCloseModal(closeModal)"
                                                        class="border border-gray-400 px-6 py-4 rounded-full text-sm font-semibold hover:bg-gray-100">
                                                        Cancel
                                                    </button>
                                                    <button type="submit"
                                                        class="bg-[#0E6021] text-white px-8 py-4 rounded-full text-sm font-semibold hover:bg-green-800">
                                                        Update
                                                    </button>
                                                </div>
                                            </form>

                                        </template>
                                    </EditModal>


                                    <!-- DELETE MODAL -->
                                    <DeleteModal :delete-url="`/items/${item.id}`">
                                        <template #DeleteItemButton="{ open }">
                                            <button type="button" class="text-[#F03636] mx-1 hover:text-[#e36565]"
                                                title="Delete" @click="open">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </template>

                                        <template #DeleteItem="{ close, confirm, message }">
                                            <div class="text-center">
                                                <div
                                                    class="text-4xl  text-[#FF0000] bg-[#F5CCCC] w-16 h-16 mx-auto flex items-center justify-center rounded-full">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                </div>
                                                <h1 class="font-bold text-2xl">Delete</h1>
                                                <p class="text-gray-600 mb-6 text-lg mt-2">{{ message }}</p>

                                                <div class="flex justify-center gap-3">
                                                    <button @click="close"
                                                        class="px-8 py-4 rounded-md items-center text-base bg-gray-200 hover:bg-gray-300 font-bold">
                                                        Cancel
                                                    </button>
                                                    <button @click="confirm"
                                                        class="px-8 py-4 rounded-md items-center flex gap-2 text-base bg-red-600 hover:bg-red-700 text-white font-bold">
                                                        <i class="fa-solid fa-trash"></i>
                                                        <h2>Delete</h2>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </DeleteModal>
                                </div>
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