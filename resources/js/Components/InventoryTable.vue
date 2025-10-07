<script setup>
import TableCell from './TableCell.vue';
import { usePage, Link, router } from '@inertiajs/vue3';
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
    rows: Object,
    itemDetails: Array,
    inputFields: Array,
    quantityCostFields: Array,
    dropdownFields: Array,
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

function getValue(obj, path) {
    return path.split('.').reduce((acc, key) => acc?.[key], obj) ?? 'N/A'
}

const addModalRef = ref(null);
const viewModalRef = ref(null);
const editModalRef = ref(null);
const deleteModalRef = ref(null);


const itemForm = reactive({
    par_ics_number: "",
    property_number: "",
    item_name: "",
    quantity: 0,
    unit_cost: 0,
    unit: "",
    office: "",
    supplier: "",
    description: "",
});

// DROPDOWN LISTS

const unitOptions = ["Unit", "PC"];
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



// COMPUTED TOTAL AMOUNT
const totalAmount = computed(() => itemForm.quantity * itemForm.unit_cost);

// function submitForm() {
//   console.log("Submitted data:", { ...itemForm });
//   // You can now post this to your Laravel route via Inertia or axios
// }


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

            <!--ADD FORM CONTENT -->
            <template #InventoryForm>
                <form @submit.prevent="submitItem" class="flex flex-col gap-3">
                    <!-- Header -->
                    <h2 class="text-2xl font-bold text-[#850038] mb-6">Add Item</h2>

                    <!-- Form Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Side -->
                        <div class="space-y-6">
                            <!-- Dynamic Input Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6">
                                <div class="space-y-5">
                                    <div v-for="field in inputFields" :key="field.model" class="flex flex-col">
                                        <label class="block text-xs font-semibold mb-1 text-gray-700">
                                            {{ field.label }}
                                        </label>
                                        <input :type="field.type" v-model="itemForm[field.model]"
                                            :placeholder="field.placeholder"
                                            class="w-full sm:w-[21.5rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                    </div>


                                    <!-- Quantity + Unit Cost -->
                                    <div class="flex gap-4 sm:gap-6 w-full">
                                        <div v-for="field in quantityCostFields" :key="field.model"
                                            class="flex flex-col flex-1 min-w-[6rem] sm:min-w-[8rem] md:min-w-[10rem] lg:min-w-[10rem]">
                                            <label class="block text-xs font-semibold mb-1 text-gray-700">
                                                {{ field.label }}
                                            </label>

                                            <input type="number" v-model.number="itemForm[field.model]"
                                                :placeholder="field.placeholder"
                                                class="w-full rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm text-gray-800  focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038] transition duration-150" />
                                        </div>
                                    </div>


                                    <!-- Unit / Office / Supplier -->
                                    <div class="flex gap-3">
                                        <div v-for="dropdown in dropdownFields" :key="dropdown.model"
                                            class="flex flex-col">
                                            <label class="block text-xs font-semibold mb-1 text-gray-700">
                                                {{ dropdown.label }}
                                            </label>
                                            <select v-model="itemForm[dropdown.model]"
                                                class="w-full sm:w-[6.7rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                                <option disabled value="">Select</option>
                                                <option v-for="option in dropdown.options" :key="option"
                                                    :value="option">
                                                    {{ option }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Right Side -->
                        <div class="flex flex-col justify-between">
                            <div>
                                <label class="block text-xs font-semibold mb-1">DESCRIPTION</label>
                                <textarea v-model="itemForm.description" placeholder="Input a description"
                                    class="w-full h-40 rounded-md border border-gray-300 px-3 py-2 bg-[#F8F8F8] text-sm focus:ring-2 focus:ring-[#850038]"></textarea>
                            </div>

                            <div class="mt-4 text-sm font-semibold">
                                <p>Total Amount:</p>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end items-center gap-4 mt-8">
                        <button type="button" @click="addModalRef.closeModal()"
                            class="border border-gray-400 px-6 py-4 rounded-full text-sm font-semibold hover:bg-gray-100">
                            Cancel
                        </button>
                        <button
                            class="bg-[#0E6021] text-white px-8 py-4 rounded-full text-sm font-semibold hover:bg-green-800">
                            Add
                        </button>
                    </div>
                </form>
            </template>
        </AddModal>

        <!-- OFFICE FILTER -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full sm:w-auto">
            <div class="flex flex-col w-full sm:w-auto">
                <label class="text-xs font-bold mb-1 sm:mb-0">Office</label>
                <select v-model="itemForm.office"
                    class="h-8 sm:h-9 w-full sm:w-28 text-xs rounded-md text-gray-600 border">
                    <option value="">Select</option>
                    <option v-for="office in officeOptions" :key="office" :value="office">{{ office }}</option>

                </select>

            </div>
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
                <input type="search" placeholder="Search stock item" v-model="search"
                    class="w-full sm:w-64 md:w-96 h-9 sm:h-10 rounded-full pl-10 pr-3 border text-sm" />
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
                                    <ViewModal ref="viewModalRef">
                                        <template #ViewItemButton="{ open }">
                                            <button type="button" class="text-blue-600 mx-1" title="View" @click="open">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </template>
                                        <!-- VIEW FORM CONTENT -->
                                        <template #ViewBodyContent>
                                            <div class="font-bold text-2xl mb-4 text-[#850038]">
                                                <h2>Item Details</h2>
                                            </div>

                                            <!-- ITEM DETAILS LEFT -->
                                            <div class="grid grid-cols-1 md:grid-cols-1 gap-2">
                                                <div class="space-y-3 mt-5">
                                                    <!-- Loop through each detail -->
                                                    <div v-for="detail in itemDetails" :key="detail.label"
                                                        class="flex md:items-start gap-3">
                                                        <!-- LABEL -->
                                                        <label
                                                            class="w-40 shrink-0 font-semibold text-gray-700 text-left">
                                                            {{ detail.label }}
                                                        </label>

                                                        <!-- VALUE (plain text) -->
                                                        <p v-if="!detail.format"
                                                            class="text-gray-800 leading-snug break-words whitespace-normal max-w-[calc(100%-10rem)]">
                                                            {{ detail.value }}
                                                        </p>

                                                        <!-- VALUE (formatted) -->
                                                        <div v-else v-html="detail.format(detail.value)"
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
                                                title="Edit" @click="open">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                        </template>
                                        <!--EDIT FORM CONTENT -->
                                        <template #EditInventory="{ close }">
                                            <form @submit.prevent="submitItem" class="flex flex-col gap-3">
                                                <!-- Header -->
                                                <h2 class="text-2xl font-bold text-[#850038] mb-6">Edit Item</h2>

                                                <!-- Form Grid -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <!-- Left Side -->
                                                    <div class="space-y-6">
                                                        <!-- Dynamic Input Fields -->
                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6">
                                                            <div class="space-y-5">
                                                                <div v-for="field in inputFields" :key="field.model"
                                                                    class="flex flex-col">
                                                                    <label
                                                                        class="block text-xs font-semibold mb-1 text-gray-700">
                                                                        {{ field.label }}
                                                                    </label>
                                                                    <input :type="field.type"
                                                                        v-model="itemForm[field.model]"
                                                                        :placeholder="field.placeholder"
                                                                        class="w-full sm:w-[21.5rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                                                </div>


                                                                <!-- Quantity + Unit Cost -->
                                                                <div class="flex gap-4 sm:gap-6 w-full">
                                                                    <div v-for="field in quantityCostFields"
                                                                        :key="field.model"
                                                                        class="flex flex-col flex-1 min-w-[6rem] sm:min-w-[8rem] md:min-w-[10rem] lg:min-w-[10rem]">
                                                                        <label
                                                                            class="block text-xs font-semibold mb-1 text-gray-700">
                                                                            {{ field.label }}
                                                                        </label>

                                                                        <input type="number"
                                                                            v-model.number="itemForm[field.model]"
                                                                            :placeholder="field.placeholder"
                                                                            class="w-full rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm text-gray-800  focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038] transition duration-150" />
                                                                    </div>
                                                                </div>


                                                                <!-- Unit / Office / Supplier -->
                                                                <div class="flex gap-3">
                                                                    <div v-for="dropdown in dropdownFields"
                                                                        :key="dropdown.model" class="flex flex-col">
                                                                        <label
                                                                            class="block text-xs font-semibold mb-1 text-gray-700">
                                                                            {{ dropdown.label }}
                                                                        </label>
                                                                        <select v-model="itemForm[dropdown.model]"
                                                                            class="w-full sm:w-[6.7rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                                                            <option disabled value="">Select</option>
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


                                                    <!-- Right Side -->
                                                    <div class="flex flex-col justify-between">
                                                        <div>
                                                            <label
                                                                class="block text-xs font-semibold mb-1">DESCRIPTION</label>
                                                            <textarea v-model="itemForm.description"
                                                                placeholder="Input a description"
                                                                class="w-full h-40 rounded-md border border-gray-300 px-3 py-2 bg-[#F8F8F8] text-sm focus:ring-2 focus:ring-[#850038]"></textarea>
                                                        </div>

                                                        <div class="mt-4 text-sm font-semibold">
                                                            <p>Total Amount:</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Buttons -->
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