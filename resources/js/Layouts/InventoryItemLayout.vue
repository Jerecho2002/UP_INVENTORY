<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import InventoryTable from "@/Components/InventoryTable.vue";
import PageHeader from "@/Components/PageHeader.vue";



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
  { label: "Quantity", model: "quantity", placeholder: "0", type: "number"},
  { label: "Unit Cost", model: "unit_cost", placeholder: "0", type: "number"},
];

const inputFields = [
  { label: "PAR/ICS NUMBER", model: "category", placeholder: "000-0000-00-000", type: "text" },
  { label: "ITEM NAME", model: "item_name", placeholder: "Laptops, Ceiling Fan...", type: "text" },
];

const dropdownFields = [
  { label: "UNIT", model: "unit", options: 
                                            [{label: "Unit", value: "Unit"},
                                             {label: "PC", value: "PC"}, 
                                             {label: "Box", value: "Box"}]},
  { label: "STATUS", model: "status", options: 
                                            [{label: "Active", value: "1"},
                                             {label: "Inactive", value: "0"},]},
];

const classificationDropdown = [
  { label: "Categories" }
]

const page = usePage();
const items = computed(() => page.props.items);
const itemClassifications = computed(() => page.props.itemClassifications);

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
          <InventoryTable 
          :columns="columns" 
          :rows="items"
          :itemClass="itemClassifications" 
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
