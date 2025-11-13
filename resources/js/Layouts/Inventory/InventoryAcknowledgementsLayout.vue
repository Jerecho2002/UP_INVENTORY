<script setup>
import { ref } from 'vue';
import NavHeader from '@/Components/NavHeader.vue';
import SideBar from '@/Components/SideBar.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InventoryAcknowledgementsTable from '@/Components/InventoryAcknowledgementsTable.vue';


const accountableField = [
   { label: "Accountable Person", model: "", name: "accountablePerson", option: "classification_name"},
   { label: "Issued By", model: "", name: "issued_by", option: "issued_by"},
   { label: "Created By", model: "", name: "created_by", option: "creadted_by"},
];

const firstDropdown = [
  { label: "Categories", model: "item_classification_id", name: "itemClass", option: "classification_name"},
  { label: "Suppliers", model: "supplier_id", name: "suppliers", option: "supplier_name"},
  { label: "Locations", model: "location_id", name: "locations", option: "location_name"},
];

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

const quantityCostFields = [
  { label: "Quantity", model: "quantity", placeholder: "0", type: "number"},
  { label: "Unit Cost", model: "unit_cost", placeholder: "0", type: "number"},
];

const inputFields = [
  { label: "Item Name", model: "item_name", placeholder: "Laptops, Ceiling Fan...", type: "text" },
  { label: "PAR/ICS Number", model: "category", placeholder: "000-0000-00-000", type: "text" },
  { label: "Property Number", model: "property_number", placeholder: "PROP-####.", type: "text" },
];

const invoicesFundFields = [
  { label: "Invoice Number", model: "invoice_id", placeholder: "0000", type: "text", readonly: false },
  { label: "Fund Source", model: "fund_source_id", placeholder: "000", type: "text", readonly: false },
];

const requestFields = [
  { label: "Purchase Request", model: "pr_number", placeholder: "PR-###", type: "text" },
  { label: "Purchase Orders", model: "po_number", placeholder: "PO-###", type: "text" },
  { label: "Remarks", model: "remarks", placeholder: "RM-###", type: "text" },
]


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
      <main class="flex-1 sm:p-5 md:p-6 overflow-hidden ">
        <!-- HEAD TITLE -->
        <PageHeader title="Acknowledgements" />
        <div class="w-full h-full">
          <InventoryAcknowledgementsTable
          :accountableField="accountableField"
          :firstDropdown="firstDropdown"
          :secondDropdown="secondDropdown"
          :quantityCostFields="quantityCostFields"
          :inputFields="inputFields"
          :invoicesFundFields="invoicesFundFields"
          :requestFields="requestFields"
          />  
        </div>
      </main>
    </div>
  </div>
</template>