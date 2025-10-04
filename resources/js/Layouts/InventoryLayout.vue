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

const tableHeaders = [
  { name: "Property Records" },
  { name: "Property Number" },
  { name: "Item Name" },
  { name: "Unit" },
  { name: "Unit Cost" },
  { name: "Quantity" },
  { name: "Status" },
  { name: "Action" }, 
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
      if(status === 0) {
        label = 'Inactive'; cls = 'text-red-700';
      } else if(status === 1) {
        label = 'Active'; cls = 'text-[#119E26]';
      } else if(status === 2) {
        label = 'Pending'; cls = 'text-yellow-700';
      }
      return `<span class="${cls}">${label}</span>`;
    }
  },
  { label: "Action", key: 'action' },
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
      <main class="flex-1 sm:p-7 md:p-8 overflow-hidden m-2">
        <!-- HEAD TITLE -->
        <PageHeader title="Inventory" />
        <div class="w-full h-full">
          <InventoryTable :columns="columns" :rows="items" />
        </div>
      </main>
    </div>
  </div>
</template>
