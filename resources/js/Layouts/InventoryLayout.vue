<script setup>
import { ref } from "vue";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import InventoryTable from "@/Components/InventoryTable.vue";

const menuItems = [
  { name: "Dashboard", icon: "fa-solid fa-table-cells-large", route: "/dashboard" },
  { name: "Inventory", icon: "fa-solid fa-boxes-packing", route: "/inventory" },
  { name: "Reports", icon: "fa-solid fa-file-export", route: "/reports" },
  { name: "Suppliers", icon: "fa-solid fa-handshake", route: "/suppliers" },
  { name: "Purchase", icon: "fa-solid fa-box-open", route: "/purchase" },
  { name: "Item Disposal", icon: "fa-solid fa-recycle", route: "/item-disposal" },
];

const tableHeaders = [
  { name: "Property Records" },
  { name: "Property Number" },
  { name: "Item Name"},
  { name: "Unit" },
  { name: "Unit Cost" },
  { name: "Quantity" },
  { name: "Status" },
  { name: "Action" },
];

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
      <main class="flex-1 sm:p-7 md:p-8 overflow-hidden">
        <div class="w-full h-full">
          <InventoryTable :table-headers="tableHeaders" />
        </div>
      </main>
    </div>
  </div>
</template>
