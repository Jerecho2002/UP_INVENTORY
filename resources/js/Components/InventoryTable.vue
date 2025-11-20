<script setup>
import { usePage, useForm, Link, router } from '@inertiajs/vue3';
import { reactive, computed, ref, watch } from 'vue';
import { debounce } from 'lodash';
import TableCell from './TableCell.vue';


const props = defineProps({
    // rooms: Array, // -> InventoryTable
    columns: Array, // -> InventoryTable
    rows: Object, // -> InventoryTable
    unitCostOptions: Array, // InventoryFilter
    Status: Array, // -> InventoryFilter
    itemClass: Array, // InventoryForm
    suppliers: Array, // InventoryForm
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

const page = usePage();
const user = computed(() => page.props.auth.user);
const userRole = computed(() => user.value?.role);

const canDelete = computed(() => ['admin'].includes(userRole.value));
const canEdit = computed(() => ['admin'].includes(userRole.value));

const selectedViewItem = ref(null);

// PAGINATE
const goToPage = (url) => {
    if (!url) return;
    router.visit(url, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
};

const form = useForm({
    item_classification_id: "",
    supplier_id: "",
    invoice: "",
    fund_source: "",
    item_name: "",
    description: "",
    category: "",
    quantity: "",
    unit: "",
    unit_cost: "",
    total_amount: "",
    property_number: "",
    serial_numbers: [], 
    serial_number: "", 
    pr_number: "",
    po_number: "",
    remarks: "",
    date_acquired: "",
    status: "",
});

const search = ref(props.search || '');
const cost_range = ref(props.cost_range || '');
const status = ref(props.status || '');

// const calculateTotalAmount = () => {
//     form.total_amount = form.quantity * form.unit_cost;
// };

const isEditing = ref(false);


// watch(
//     () => [form.quantity, form.unit_cost],
//     calculateTotalAmount
// );

// INVENTORY FILTER
// const fetchItems = debounce ((params) =>  {
//     router.get('inventory/items', params, {
//         preserveState: true,
//         preserveScroll: true,
//         only: ['items'],
//     });
// }, 300);

// watch( search, (value) => {
//         fetchItems ({ search: value, cost_range: cost_range.value, status: status.value  });
//     }); 

// watch(status, (value) => {
//         fetchItems({ search: search.value, cost_range: cost_range.value, status: value });
//     });

// watch(cost_range, (value) => {
//         fetchItems({ search: search.value, cost_range: value, status: status.value });
//     });

const editingItem = ref(null);

function openEditModal(item, open) {
    isEditing.value = true;
    editingItem.value = item;
    form.id = item.id;
    form.item_classification_id = item.item_classification_id;
    form.supplier_id = item.supplier_id;
    form.invoice = item.invoice;
    form.fund_source = item.fund_source;
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

function getValue(obj, path) {
    return path.split('.').reduce((acc, key) => acc?.[key], obj) ?? 'N/A'
}

</script>

<template>
    <div>
        <div>
            <!-- Table (horizontal scroll on small screens) -->
            <div class="overflow-x-auto mt-3">
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
                                    <span v-if="col.format" v-html="col.format(item[col.key])"></span>
                                    <span v-else>{{ item[col.key] }}</span>
                                </template>

                                <!-- For Action column -->
                                <template v-else>
                                    <div class="flex items-center gap-1">

                                       
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