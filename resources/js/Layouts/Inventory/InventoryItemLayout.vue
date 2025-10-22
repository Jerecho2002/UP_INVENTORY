<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import InventoryItemsTable from "@/Components/InventoryItemsTable.vue";
import PageHeader from "@/Components/PageHeader.vue";



const columns = [
  { label: "Property Records", key: 'category' },
  { label: "Property Number", key: 'property_number' },
  { label: "Item Name", key: 'item_name' },
  { label: "Unit", key: 'unit', format: (val) => val ?? 'N/A' },
  { label: "Unit Cost", key: 'unit_cost', format: (val) => val ? `₱${val}` : 'N/A' },
  { label: "Status", key: 'status', 
    format: (status) => {
      let label = 'Unknown', cls = 'text-gray-500';
      if (status === 0) { label = 'Inactive'; cls = 'text-red-700'; }
      else if (status === 1) { label = 'Active'; cls = 'text-[#14B449]'; }
      else if (status === 2) { label = 'Pending'; cls = 'text-yellow-700'; }
      return `<span class="${cls}">${label}</span>`;
    }
  },
  { label: "Action", key: "action" } // keep key, but don’t inject HTML
]

const viewItems = [
  { label: "Property Records", key: "category" },
  { label: "Property Number", key: "property_number" },
  { label: "Item Name", key: "item_name" },
  { label: "Unit", key: "unit", format: (val) => val ?? "N/A" },
  { label: "Unit Cost", key: "unit_cost", format: (val) => (val ? `₱${val}` : "N/A") },
  {
    label: "Status",
    key: "status",
    format: (status) => {
      let label = "Unknown",
        cls = "text-gray-500";
      if (status === 0) {
        label = "Inactive";
        cls = "text-red-700";
      } else if (status === 1) {
        label = "Active";
        cls = "text-[#14B449]";
      } else if (status === 2) {
        label = "Pending";
        cls = "text-yellow-700";
      }
      return `<span class="${cls}">${label}</span>`;
    },
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
  { label: "PAR/ICS Number", model: "category", placeholder: "000-0000-00-000", type: "text" },
  { label: "Item Name", model: "item_name", placeholder: "Laptops, Ceiling Fan...", type: "text" },
  { label: "Property Number", model: "property_number", placeholder: "PROP-####.", type: "text" },
];

const firstDropdown = [
  { label: "Categories", model: "item_classification_id", name: "itemClass", option: "classification_name"},
  { label: "Suppliers", model: "supplier_id", name: "suppliers", option: "supplier_name"},
  { label: "Locations", model: "location_id", name: "locations", option: "location_name"},
  { label: "Invoices", model: "invoice_id", name: "invoices", option: "invoice_number"},
  { label: "Fund Sources", model: "fund_source_id", name: "fundSources", option: "code"},
]

const secondDropdown = [
  { label: "Unit", model: "unit", options: 
                                            [{label: "Unit", value: "Unit"},
                                             {label: "PC", value: "PC"}, 
                                             {label: "Box", value: "Box"}]},
  { label: "Status", model: "status", options: 
                                            [{label: "Active", value: "1"},
                                             {label: "Inactive", value: "0"},]},
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
      <aside v-show="isSidebarOpen">
        <SideBar/>
      </aside>

      <!-- Main --> 
      <main class="flex-1 sm:p-5 md:p-6 overflow-hidden m-2">
        <!-- HEAD TITLE -->
        <PageHeader title="Items" />
        <div class="w-full h-full">
          <InventoryItemsTable 
          :columns="columns" 
          :rows="items"
          :itemClass="itemClassifications"
          :suppliers="suppliers"
          :locations="locations"
          :invoices="invoices"
          :fundSources="fundSources"
          :view-items="viewItems"
          :edit-items="editItems"
          :input-fields="inputFields"
          :quantity-cost-fields="quantityCostFields"
          :firstDropdown="firstDropdown"
          :secondDropdown="secondDropdown"
          />
        </div>
      </main>
    </div>
  </div>
</template>
