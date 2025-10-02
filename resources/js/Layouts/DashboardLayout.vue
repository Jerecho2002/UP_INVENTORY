<script setup>
import { ref } from "vue";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import ItemOverview from "@/Components/ItemOverview.vue";

const menuItems = [
  { name: "Dashboard", icon: "fa-solid fa-table-cells-large", route: "dashboard.index" },
  { name: "Inventory", icon: "fa-solid fa-boxes-packing", route: "inventory.index" },
  { name: "Reports", icon: "fa-solid fa-file-export", route: "reports.index" },
  { name: "Suppliers", icon: "fa-solid fa-handshake", route: "suppliers.index" },
  { name: "Purchase", icon: "fa-solid fa-box-open", route: "purchase.index" },
  { name: "Item Disposal", icon: "fa-solid fa-recycle", route: "item_disposal.index" },
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
        <div class="flex flex-col">
          <!-- HEAD TITLE -->
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 m-3">
            <h1 class="font-bold text-lg sm:text-xl md:text-2xl text-[#1b1b1b]">DASHBOARD</h1>
            <p class="text-gray-500 font-semibold text-xs sm:text-sm">
              UPCEBU
              <span>
                <i class="fa-solid fa-chevron-right mx-1 sm:mx-2 text-xs sm:text-sm"></i>
              </span>
              Dashboard
            </p>
          </div>
        </div>
        <div class="w-full h-screen text-left text-xs sm:text-sm mt-[1rem]">
          <ItemOverview />
        </div>
      </main>
    </div>
  </div>

</template>