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
    editItems: Array,
    inputFields: Array,
    quantityCostFields: Array,
    firstDropdown: Array,
    secondDropdown: Array,
});

const search = ref(props.searchItem || '');
const selectedCostRange = ref(props.selectedCostRange || '');

watch(
    search,
    debounce((output) => {
        router.get('/', { search: output, cost_range: selectedCostRange.value }, { preserveState: true, only: ['items'] });
    })
);

watch(
    selectedCostRange,
    debounce((range) => {
        router.get('/', { search: search.value, cost_range: range }, { preserveState: true });
    })
)

const page = usePage();
const user = computed(() => page.props.auth.user);
const userRole = computed(() => user.value?.role);

const canDelete = computed(() => ['admin'].includes(userRole.value));
const canEdit = computed(() => ['admin'].includes(userRole.value));

const addModalRef = ref(null);
const editModalRef = ref(null);
const selectedViewItem = ref(null);

const form = useForm({
    property_number: "",
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
    pr_number: "",
    po_number: "",
    remarks: "",
    date_aquired: "",
    status: "",
});

const calculateTotalAmount = () => {
    form.total_amount = form.quantity * form.unit_cost;
};

watch(
    () => [form.quantity, form.unit_cost],
    calculateTotalAmount
);

// Method to handle form submission
const handleAddItem = (closeModal) => {
    form.post(route("items.store"), {
        onSuccess: () => {
            closeModal(),
                form.reset();
        }
    });
};

function openViewModal(item, open) {
    selectedViewItem.value = item; // store clicked row’s data
    open(); // call the modal’s exposed openModal()
}

// function openEditModal(item, open) {
//     // Reset the itemForm fields before filling it with item data
//     Object.keys(itemForm).forEach((key) => {
//         itemForm[key] = ''; // Clear any previous values
//     });

//     // Populate itemForm with values from the selected item
//     props.editItems.forEach((col) => {
//         const flatKey = col.key.split('.').pop(); // Flatten nested keys
//         itemForm[flatKey] = getValue(item, col.key) ?? ''; // Populate form with item data
//     });

//     // Populate quantity and unit cost fields
//     props.quantityCostFields.forEach((field) => {
//         const flatKey = field.key.split('.').pop();
//         itemForm[flatKey] = getValue(item, field.key) ?? ''; // Populate itemForm for quantity/unit cost
//     });

//     // Populate dropdowns (unit, office, supplier)
//     props.dropdownFields.forEach((dropdown) => {
//         itemForm[dropdown.model] = getValue(item, dropdown.model) ?? ''; // Populate dropdown values
//     });

//     // Populate description field
//     itemForm.description = getValue(item, 'description') ?? ''; // Populate description field

//     // Open the modal after populating the form data
//     open();
// }


// const unitOptions = ["Unit", "PC"];
const officeOptions = ["Budget Office", "ITC Office", "ILC", "CMO Office"];
const supplierOptions = [
    "Dwinar Computer Center",
    "DCNC Sales And Services Corp.",
    "JCE Marketing",
    "European Union (Makati)",
];
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
                    class="flex gap-2 bg-[#0E6021] rounded-md text-white px-3 py-2 text-xs sm:text-sm hover:bg-[#2a9754] w-full sm:w-auto justify-center">
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
                            <!-- Dynamic Input Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-6">
                                <div class="space-y-5">
                                    <div v-for="ip in inputFields" :key="ip.model" class="flex flex-col">
                                        <label class="block text-sm font-bold mb-1 ">{{ ip.label
                                            }}</label>
                                        <input v-model="form[ip.model]" :key="ip.model" type="text"
                                            :placeholder="ip.placeholder"
                                            class="w-full sm:w-[30rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                        <div v-if="form.errors[ip.model]" class="text-red-500 text-sm">
                                            {{ form.errors[ip.model] }}
                                        </div>
                                    </div>

                                    <!-- Quantity + Unit Cost -->
                                    <div class="flex gap-4 sm:gap-6 w-full">
                                        <div v-for="qcf in quantityCostFields" :key="qcf.quantityCostFields"
                                            class="flex flex-col flex-1 min-w-[8rem] sm:min-w-[10rem] md:min-w-[15rem] lg:min-w-[14.3rem]">
                                            <label class="block text-sm font-bold mb-1">{{ qcf.label
                                                }}</label>
                                            <input v-model="form[qcf.model]" :key="qcf.model" :type="qcf.type"
                                                :placeholder="qcf.placeholder"
                                                class="w-full rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm text-gray-800 focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038] transition duration-150" />
                                            <div v-if="form.errors[qcf.model]" class="text-red-500 text-sm">
                                                {{ form.errors[qcf.model] }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PR AND PO -->
                                    <div>
                                        <div>
                                            <label>PR Number</label>
                                            <input type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div class="flex flex-col justify-between">

                            <!-- First Dropdown -->
                            <div class="flex flex-col md:flex-row gap-2 mb-3">
                                <div v-for="fdp in firstDropdown" :key="fdp.name" class="flex flex-col">
                                    <label class="block text-sm font-bold mb-1">{{ fdp.label
                                        }}</label>
                                    <select v-model="form[fdp.model]" :key="fdp.model"
                                        class="w-full sm:w-[6.5rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                        <option value="">Select</option>
                                        <option v-for="item in props[fdp.name]" :key="item.id" :value="item.id">
                                            {{ item[fdp.option] || 'N/A' }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors[fdp.model]" class="text-red-500 text-sm">
                                        {{ form.errors[fdp.model] }}
                                    </div>
                                </div>
                            </div>

                            <!-- Second Dropdown -->
                            <div class="flex flex-col md:flex-row gap-3 mb-3">
                                <div v-for="sdf in secondDropdown" :key="sdf.label" class="flex gap-3">
                                    <div>
                                        <label class="block text-sm font-bold mb-1">{{ sdf.label
                                            }}</label>
                                        <select v-model="form[sdf.model]"
                                            class="w-full sm:w-[6.7rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                            <option value="">Select</option>
                                            <option v-for="op in sdf.options" :key="op.value" :value="op.value">{{
                                                op.label }}</option>
                                        </select>
                                        <div v-if="form.errors[sdf.model]" class="text-red-500 text-sm">
                                            {{ form.errors[sdf.model] }}
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold mb-1">Date Acquired</label>
                                    <input class="rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" type="date" name="" id="">
                                </div>
                            </div>

                            <div>
                                <label class="block text-md font-semibold mb-1">Description</label>
                                <textarea v-model="form.description" placeholder="Input a description"
                                    class="w-full h-36 rounded-md border border-gray-300 px-3 py-2 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]"></textarea>
                                <div v-if="form.errors.description" class="text-red-500 text-sm">
                                    {{ form.errors.description }}
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row items-center text-sm font-semibold">
                                <label class="block text-base font-bold">Total Amount:</label>
                                <input v-model="form.total_amount" readonly placeholder="₱0.00"
                                    class="block text-lg font-semibold text-gray-700 border border-none pointer-events-none">
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end items-center gap-4 mt-8">
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
        <div>
            <table class="overflow-x-auto w-full table-auto border-collapse text-left bg-white text-xs sm:text-sm">
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
                                    <!-- <EditModal ref="editModalRef">
                                        <template #EditItemButton="{ open }">
                                            <button type="button" class="text-[#54B3AB] hover:text[#54B3AB] mx-1"
                                                title="Edit" @click="() => openEditModal(item, open)">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                        </template>

                                        
                                        <template #EditInventory="{ close }">
                                            <form @submit.prevent="submitItem" class="flex flex-col gap-3">
                                                
                                                <h2 class="text-2xl font-bold text-[#850038] mb-6">Edit Item</h2>

                                               
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    
                                                    <div class="space-y-6">
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6">
                                                            <div class="space-y-5">

                                                                
                                                                <div v-for="col in editItems" :key="col.label"
                                                                    class="flex flex-col">
                                                                    <label
                                                                        class="block text-xs font-semibold mb-1 text-gray-700">
                                                                        {{ col.label }}
                                                                    </label>
                                                                    <input type="text"
                                                                        v-model="itemForm[col.key.split('.').pop()]"
                                                                        placeholder="Enter value"
                                                                        :disabled="col.key === 'category' || col.key === 'property.property_number'"
                                                                        class="w-full sm:w-[21.5rem] rounded-md border border-gray-300 px-3 py-3
                                                                        bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038]
                                                                        focus:outline-none focus:border-[#850038]
                                                                        transition duration-150 disabled:bg-gray-200
                                                                        disabled:text-gray-500 disabled:cursor-not-allowed" />
                                                                </div>
                                                                
                                                                <div class="flex gap-4 sm:gap-6 w-full">
                                                                    <div v-for="field in quantityCostFields"
                                                                        :key="field.label"
                                                                        class="flex flex-col flex-1 min-w-[6rem] sm:min-w-[8rem] md:min-w-[10rem] lg:min-w-[10rem]">
                                                                        <label
                                                                            class="block text-xs font-semibold mb-1 text-gray-700">
                                                                            {{ field.label }}
                                                                        </label>
                                                                        <input type="number"
                                                                            v-model.number="itemForm[field.key]"
                                                                            class="w-full rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm text-gray-800 focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038] transition duration-150" />
                                                                    </div>
                                                                </div>

                                                                
                                                                <div class="flex gap-3">
                                                                    <div v-for="dropdown in dropdownFields"
                                                                        :key="dropdown.model" class="flex flex-col">
                                                                        <label
                                                                            class="block text-xs font-semibold mb-1 text-gray-700">
                                                                            {{ dropdown.label }}
                                                                        </label>
                                                                        <select v-model="itemForm[dropdown.model]"
                                                                            class="w-full sm:w-[6.7rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                                                            <option value="">Select</option>
                                                                            <option v-for="option in dropdown.options"
                                                                                :key="option" :value="option">
                                                                                {{ option }}
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="flex flex-col justify-between">
                                                        <div>
                                                            <label
                                                                class="block text-xs font-semibold mb-1">DESCRIPTION</label>
                                                            <textarea v-model="itemForm.description"
                                                                placeholder="Input a description"
                                                                class="w-full h-40 rounded-md border border-gray-300 px-3 py-2 bg-[#F8F8F8] text-sm focus:ring-2 focus:ring-[#850038]"></textarea>
                                                        </div>

                                                        <div class="mt-4 text-sm font-semibold">
                                                            <p>Total Amount: ₱{{ totalAmount }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                
                                                <div class="flex justify-end items-center gap-4 mt-8">
                                                    <button type="button" @click="close"
                                                        class="border border-gray-400 px-6 py-4 rounded-full text-sm font-semibold hover:bg-gray-100">
                                                        Cancel
                                                    </button>
                                                    <button
                                                        class="bg-[#54B3AB] text-white px-8 py-4 rounded-full text-sm font-semibold hover:bg-green-800">
                                                        Confirm
                                                    </button>
                                                </div>
                                            </form>
                                        </template>
                                    </EditModal> -->


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
                                                <div class="text-4xl  text-[#FF0000]">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                </div>
                                                <p class="text-gray-600 mb-6 text-lg mt-2">{{ message }}</p>

                                                <div class="flex justify-center gap-3">
                                                    <button @click="close"
                                                        class="px-4 py-2 rounded-md text-sm bg-gray-200 hover:bg-gray-300 font-medium">
                                                        Cancel
                                                    </button>
                                                    <button @click="confirm"
                                                        class="px-4 py-2 rounded-md items-center flex gap-2 text-sm bg-red-600 hover:bg-red-700 text-white font-medium">
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