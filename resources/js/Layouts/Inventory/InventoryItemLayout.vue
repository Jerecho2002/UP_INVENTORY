<script setup>
import { router } from '@inertiajs/vue3';
import { ref, computed, readonly } from "vue";
import { usePage } from "@inertiajs/vue3";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import InventoryTable from "@/Components/InventoryTable.vue";
import PageHeader from "@/Components/PageHeader.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import InventoryFormModal from "@/Components/Modals/InventoryFormModal.vue";
import InventoryFilters from "@/Components/InventoryFilters.vue";
import ImportButton from "@/Components/Buttons/ImportButton.vue";
import ExportButton from "@/Components/Buttons/ExportButton.vue";
import ConvertButton from "@/Components/Buttons/ConvertButton.vue";
import DeleteModal from "@/Components/Modals/DeleteModal.vue";


const columns = [
  { label: "Item Name", key: 'item_name' },
  { label: "Unit", key: 'unit', format: (val) => val ?? 'N/A' },
  { label: "Unit Cost", key: 'unit_cost', format: (val) => val ? `₱${val}` : 'N/A' },
  { label: "PAR/ICS Number", key: 'category' },
  { label: "Property Number", key: 'property_number' },
  { label: "Serial Number", key: 'serial_number' },
  { label: "Invoice", key: 'invoice' },
  { label: "Supplier Name", key: 'supplier', format: (val) => val?.supplier_name ?? 'N/A' },
  { label: "Status", key: 'status', 
    format: (status) => {
      let label = 'Unknown', cls = 'text-gray-500', icon = '';
      if (status === 0) {
        label = 'Cancelled';
        cls = 'text-[#D32F2F] font-bold bg-[#F8D4D4] py-2 px-4 rounded-full';
        icon = '<i class="fa-solid fa-ban"></i>';
      }
      else if (status === 1) {
        label = 'Received';
        cls = 'text-[#2E7D32] font-bold bg-[#D4F8D4] py-2 px-4 rounded-full';
        icon = '<i class="fa-solid fa-circle-check"></i>';
      }
      return `<span class="${cls}">${icon} ${label}</span>`;
    }
  },
  { label: "Action", key: "action" }
]

const viewItems = [
  { label: "Property Records", key: "category" },
  { label: "Property Number", key: "property_number" },
  { label: "Serial Number", key: "serial_number" },
  { label: "Supplier", key: "supplier.supplier_name" },
  { label: "Invoice", key: "invoice" },
  { label: "Fund Source", key: "fund_source" },
  { label: "PR Number", key: "pr_number" },
  { label: "PO Number", key: "po_number" },
  { label: "Remarks", key: "remarks" },
  { label: "Item Name", key: "item_name" },
  { label: "Unit Cost", key: "unit_cost", format: (val) => (val ? `₱${val}` : "N/A") },
  { label: "Unit", key: "unit", format: (val) => val ?? "N/A" },
  { label: "Description", key: "description" },
  { label: "Date Acquired", key: "date_acquired" },
  {
    label: "Status", key: 'status',
    format: (status) => {
      let label = 'Unknown', cls = 'text-gray-500', icon = '';
      if (status === 0) {
        label = 'Inactive';
        cls = 'text-[#D32F2F] font-bold bg-[#F8D4D4] py-2 px-4 rounded-full';
        icon = '<i class="fa-solid fa-ban"></i>';
      }
      else if (status === 1) {
        label = 'Active';
        cls = 'text-[#2E7D32] font-bold bg-[#D4F8D4] py-2 px-4 rounded-full';
        icon = '<i class="fa-solid fa-circle-check"></i>';
      }
      else if (status === 2) { 
        label = 'Pending'; 
        cls = 'text-[#8D6E00] font-bold bg-[#FFF3CD] py-2 px-4 rounded-full';
        icon = '<i class="fa-solid fa-clock"></i>';
      }
      return `<span class="${cls}">${icon} ${label}</span>`;
    }
  },
];

// const editItems = [
//   { label: "Property Records", key: "category" },
//   { label: "Property Number", key: "property_number" },
//   { label: "Item Name", key: "item_name" },
//   { label: "Description", key: "description" },
// ];

const quantityCostFields = [
  { label: "Quantity", model: "quantity", placeholder: "0", type: "number"},
  { label: "Unit Cost", model: "unit_cost", placeholder: "0", type: "number"},
];

const inputFields = [
  { label: "Item Name", model: "item_name", placeholder: "Laptops, Ceiling Fan...", type: "text" },
];

const inputFieldsEdit = [
  { label: "Serial Number", model: "serial_number", placeholder: "SER-####.", type: "text", readonly: false },
  { label: "Item Name", model: "item_name", placeholder: "Laptops, Ceiling Fan...", type: "text", readonly: false },
];

const requestFields = [
  { label: "PAR/ICS Number", model: "category", placeholder: "000-0000-00-000", type: "text" },
  { label: "Property Number", model: "property_number", placeholder: "PROP-####.", type: "text" },
  { label: "Purchase Request", model: "pr_number", placeholder: "PR-###", type: "text" },
  { label: "Purchase Orders", model: "po_number", placeholder: "PO-###", type: "text" },
  { label: "Remarks", model: "remarks", placeholder: "RM-###", type: "text" },
]

const invoicesFundFields = [
  { label: "Invoice Number", model: "invoice", placeholder: "0000", type: "text", readonly: false },
  { label: "Fund Source", model: "fund_source", placeholder: "000", type: "text", readonly: false },
]

const firstDropdown = [
  { label: "Category", model: "item_classification_id", name: "itemClass", option: "classification_name", value: "id" },
  { label: "Supplier", model: "supplier_id", name: "suppliers", option: "supplier_name", value: "id" },
];

const secondDropdown = [
  { label: "Unit", model: "unit", options: 
                                            [{label: "unit", value: "unit"},
                                             {label: "pcs", value: "pcs"}, 
                                             {label: "box", value: "box"}]},
  { label: "Status", model: "status", options: 
                                            [{label: "Received", value: "1"},
                                             {label: "Cancelled", value: "0"},
                                            
  ]},
];

const totalAmount = [
  { label: "Total Amount"}
];


const unitCostOptions = [
    { label: "Unit Cost", options: [{label: "0-50000", value: "0-50000"},
                                    {label: "₱50,000 Above", value: "50000-99999999"},
  ]},
];

const filterStatus = [
    {label: "Status", options: [{ label: "Received", value: 1},
                                { label: "Cancelled", value: 0 },
  ]},
];

const page = usePage();
const items = computed(() => page.props.items || []);
const itemClassifications = computed(() => page.props.itemClassifications || []);
const suppliers = computed(() => page.props.suppliers || []);
// const rooms = computed(() => page.props.rooms?.rooms || []);

//INVENTORY FILTER 
let search = ref('');
let status = ref(null);
let cost_range = ref(null);


// MODAL FUNCTION
let formMode = ref('create'); // CREATE || EDIT || VIEW
let showFormModal = ref(false);
let showDeleteModal = ref(false);
let currentItem = ref({});

function openAdd(item) {
  formMode.value = 'create';
  currentItem.value = item;
  showFormModal.value = true;
};

function handleView(item) {
  formMode.value = 'view';
  currentItem.value = item;
  showFormModal.value = true;
}

function handleEdit(item) {
  formMode.value = 'edit';
  currentItem.value = item ;
  showFormModal.value = true;
}

function handleDelete(item) {
  currentItem.value = item;
  showDeleteModal.value = true;
}

function handleSubmit (item) {
  Inertia.post('/items', item, {
    onSuccess: () => {
      showFormModal.value = false;
      refreshItems();
    },
    onError: (errors) => {
      console.error('Validation errors:', errors);
    }
  });
}


function confirmDelete(item) {
  // call delete API or Inertia.delete(...)
  console.log('delete confirmed', item);
  showDeleteModal.value = false;
  refreshItems();
}

function refreshItems() {
  router.reload({
    only: ['items'] // optional but faster if you only need items
  });
}



const isSidebarOpen = ref(true);
const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

</script>

<template>
  <div class="h-screen flex flex-col bg-gray-100 overflow-hidden">
    <!-- Pass toggle event -->
    <NavHeader class="flex-shrink-0" @toggleSidebar="toggleSidebar" />  

    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <aside  class="transition-all duration-600 ease-in-out transform"
    :class="isSidebarOpen ? 'translate-x-0 opacity-100 w-64' : '-translate-x-full opacity-0 w-0'">
        <SideBar/>
      </aside>

      <!-- Main --> 
      <main class="flex-1 sm:p-5 md:p-6 md:mx-0 overflow-y-auto">
        <!-- HEAD TITLE -->
        <div class="m-2">
          <PageHeader title="Items" />
            <div class="w-full h-full">
                <div class="flex flex-col md:flex-row gap-2 justify-end mt-6">
                  <ConvertButton />
                  <ImportButton />
                  <ExportButton />
                </div>
                
              <div class="flex flex-col md:flex-row justify-between mt-10">
                <PrimaryButton @click="openAdd">
                  <i class="fa-solid fa-plus"></i>
                  <span> Add Item</span>
                </PrimaryButton>

                <InventoryFilters
                  :search="search"
                  :cost_range="cost_range"
                  :status="status"
                  :unitCostOptions="unitCostOptions" 
                  :filterStatus="filterStatus"
                  @update:search="search = $event"
                  @update:status="status = $event"
                  @update:cost_range="cost_range = $event"
                  :mode="'inventory'"
                />
              </div>

              <InventoryFormModal
                v-if="showFormModal"
                @created="refreshItems"
                :mode="formMode"
                :firstDropdown="firstDropdown"
                :secondDropdown="secondDropdown"
                :quantityCostFields="quantityCostFields"
                :input-fields="inputFields"
                :invoicesFundFields="invoicesFundFields"
                :requestFields="requestFields"
                :inputFieldsEdit="inputFieldsEdit"
                :totalAmount="totalAmount"
                :itemClass="itemClassifications"
                :suppliers="suppliers"
                :initialValues="currentItem"
                @submit="handleSubmit"
                @close="() => showFormModal = false"
              />

              <InventoryTable 
                :columns="columns" 
                :rows="items"
                @view="handleView"
                @edit="handleEdit"
                @delete="handleDelete"
                />

               <DeleteModal
                v-if="showDeleteModal"
                :item="currentItem"
                @confirm="confirmDelete"
                @close="() => showDeleteModal = false"
                />
            </div>
        </div>
      </main>
    </div>
  </div>
</template>
