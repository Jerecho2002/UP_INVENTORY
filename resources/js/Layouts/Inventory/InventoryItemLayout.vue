<script setup>
import { ref, computed, readonly } from "vue";
import { usePage } from "@inertiajs/vue3";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import InventoryItemsTable from "@/Components/InventoryItemsTable.vue";
import PageHeader from "@/Components/PageHeader.vue";

const columns = [
  { label: "Item Name", key: 'item_name' },
  { label: "Property Records", key: 'category' },
  { label: "Property Number", key: 'property_number' },
  { label: "Serial Number", key: 'serial_number' },
  { label: "Unit", key: 'unit', format: (val) => val ?? 'N/A' },
  { label: "Unit Cost", key: 'unit_cost', format: (val) => val ? `₱${val}` : 'N/A' },
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
        label = 'Recieved';
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
  { label: "Location", key: "location.location_name" },
  { label: "Invoice", key: "invoice.invoice_number" },
  { label: "Fund Source", key: "fund_source.code" },
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

const editItems = [
  { label: "Property Records", key: "category" },
  { label: "Property Number", key: "property_number" },
  { label: "Item Name", key: "item_name" },
  { label: "Description", key: "description" },
];

const quantityCostFields = [
  { label: "Quantity", model: "quantity", placeholder: "0", type: "number"},
  { label: "Unit Cost", model: "unit_cost", placeholder: "0", type: "number"},
];

const inputFields = [
  { label: "Item Name", model: "item_name", placeholder: "Laptops, Ceiling Fan...", type: "text" },
  { label: "PAR/ICS Number", model: "category", placeholder: "000-0000-00-000", type: "text" },
  { label: "Property Number", model: "property_number", placeholder: "PROP-####.", type: "text" },
];

const inputFieldsEdit = [
  { label: "Serial Number", model: "serial_number", placeholder: "SER-####.", type: "text", readonly: false },
  { label: "Item Name", model: "item_name", placeholder: "Laptops, Ceiling Fan...", type: "text", readonly: false },
  { label: "PAR/ICS Number", model: "category", placeholder: "000-0000-00-000", type: "text", readonly: true }, 
  { label: "Property Number", model: "property_number", placeholder: "PROP-####.", type: "text" , readonly: true},
];

const requestFields = [
  { label: "Purchase Request", model: "pr_number", placeholder: "PR-###", type: "text" },
  { label: "Purchase Orders", model: "po_number", placeholder: "PO-###", type: "text" },
  { label: "Remarks", model: "remarks", placeholder: "RM-###", type: "text" },
]

const invoicesFundFields = [
  { label: "Invoice Number", model: "invoice_id", placeholder: "0000", type: "text", readonly: false },
  { label: "Fund Source", model: "fund_source_id", placeholder: "000", type: "text", readonly: false },
]

const firstDropdown = [
  { label: "Categories", model: "item_classification_id", name: "itemClass", option: "classification_name"},
  { label: "Suppliers", model: "supplier_id", name: "suppliers", option: "supplier_name"},
  { label: "Locations", model: "location_id", name: "locations", option: "location_name"},
]

const secondDropdown = [
  { label: "Unit", model: "unit", options: 
                                            [{label: "unit", value: "unit"},
                                             {label: "pcs", value: "pcs"}, 
                                             {label: "box", value: "box"}]},
  { label: "Status", model: "status", options: 
                                            [{label: "Recieved", value: "1"},
                                             {label: "Cancelled", value: "0"},
                                            
  ]},
];

const unitCostOptions = [
    { label: "₱0 - ₱50,000", value: "0-50000" },
    { label: "₱50,000 Above", value: "50000-99999999" },
];

const Status = [
    { label: "Recieved", value: 1},
    { label: "Cancelled", value: 0 },
];

const page = usePage();
const items = computed(() => page.props.items);
const itemClassifications = computed(() => page.props.itemClassifications);
const suppliers = computed(() => page.props.suppliers);
const locations = computed(() => page.props.locations);
const invoices = computed(() => page.props.invoices);
const fundSources = computed(() => page.props.fundSources);

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
      <main class="flex-1 sm:p-5 md:p-6 m-2 overflow-x-hidden overflow-y-auto">
        <!-- HEAD TITLE -->
        <PageHeader title="Items" />
        <div class="w-full h-full">
          <InventoryItemsTable 
          :columns="columns" 
          :rows="items"
          :unitCostOptions="unitCostOptions"
          :Status="Status"
          :itemClass="itemClassifications"
          :suppliers="suppliers"
          :locations="locations"
          :invoices="invoices"
          :fundSources="fundSources"
          :view-items="viewItems"
          :edit-items="editItems"
          :input-fields="inputFields"
          :inputFieldsEdit="inputFieldsEdit"
          :quantity-cost-fields="quantityCostFields"
          :firstDropdown="firstDropdown"
          :secondDropdown="secondDropdown"
          :requestFields="requestFields"
          :invoicesFundFields="invoicesFundFields"
          />
        </div>
      </main>
    </div>
  </div>
</template>
