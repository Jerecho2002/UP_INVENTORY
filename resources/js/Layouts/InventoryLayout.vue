<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import InventoryTable from "@/Components/InventoryTable.vue";
import PageHeader from "@/Components/PageHeader.vue";

const menuItems = [
  { name: "Dashboard", icon: "fa-solid fa-table-cells-large", route: "dashboard.index" },
  { name: "Inventory", icon: "fa-solid fa-boxes-packing", route: "inventory.index" },
  { name: "Reports", icon: "fa-solid fa-file-export", route: "reports.index" },
  { name: "Suppliers", icon: "fa-solid fa-handshake", route: "suppliers.index" },
  { name: "Purchase", icon: "fa-solid fa-box-open", route: "purchase.index" },
  { name: "Item Disposal", icon: "fa-solid fa-recycle", route: "item_disposal.index" },
];

const columns = [
  { label: "Property Records", key: 'category' },
  { label: "Property Number", key: 'property.property_number' },
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
  { label: "Property Number", key: "property.property_number" },
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
  { label: "Property Number", key: "property.property_number" },
  { label: "Item Name", key: "item_name" },
  { label: "Description", key: "description" },
];

const quantityCostFields = [
  { label: "Quantity", key: "quantity" },
  { label: "Unit Cost", key: "unit_cost" },
];

const inputFields = [
  { label: "PAR/ICS NUMBER", model: "par_ics_number", placeholder: "000-0000-00-000", type: "text" },
  { label: "PROPERTY NUMBER", model: "property_number", placeholder: "000-0000-(000-000)", type: "text" },
  { label: "ITEM NAME", model: "item_name", placeholder: "Laptops, Ceiling Fan...", type: "text" },
];

const dropdownFields = [
  { label: "UNIT", model: "unit", options: ["Unit", "PC", "Box"] },
  { label: "OFFICE", model: "office", options: ["Budget Office", "ITC Office", "ILC", "CMO Office"] },
  { label: "SUPPLIER", model: "supplier", options: ["Dwinar Computer Center", "DCNC Sales And Services Corp.", "JCE Marketing", "European Union (Makati)"] },
];



const page = usePage();
const items = computed(() => page.props.items);

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
        <SideBar :menu-items="menuItems" />
      </aside>

      <!-- Main --> 
      <main class="flex-1 sm:p-5 md:p-6 overflow-hidden m-2">
        <!-- HEAD TITLE -->
        <PageHeader title="Inventory" />
        <div class="w-full h-full">
          <InventoryTable 
          :columns="columns" 
          :rows="items" 
          :view-items="viewItems"
          :edit-items="editItems"
          :input-fields="inputFields"
          :quantity-cost-fields="quantityCostFields"
          :dropdown-fields="dropdownFields"
          />
        </div>
      </main>
    </div>
  </div>
</template>
