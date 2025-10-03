<script setup>
import { ref } from "vue";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import ItemOverview from "@/Components/ItemOverview.vue";
import PageHeader from "@/Components/PageHeader.vue";

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

      <!-- MAIN -->
      <main class="flex-1 sm:p-7 md:p-8">
        <PageHeader title="Dashboard" />
          <div class="w-full h-screen text-left text-xs sm:text-sm mt-[1rem]">
            <div class="flex flex-col md:flex-row gap-4">
                <ItemOverview 
                    title="Total Items" 
                    icon="fa-solid fa-cart-shopping" 
                    bgColor="bg-[#06B6D4]" 
                    textColor="text-[#06B6D4]"
                />
                <ItemOverview 
                    title="Item Distribution" 
                    icon="fa-solid fa-hand-holding-hand" 
                    bgColor="bg-[#8B5CF6]" 
                    textColor="text-[#8B5CF6]"
                />
                <ItemOverview 
                    title="Low Stock Items" 
                    icon="fa-solid fa-triangle-exclamation" 
                    bgColor="bg-[#F59E0B]" 
                    textColor="text-[#F59E0B]"
                />
                <ItemOverview 
                    title="Out of Stock Items" 
                    icon="fa-solid fa-ban" 
                    bgColor="bg-[#DC2626]" 
                    textColor="text-[#DC2626]"
                />
            </div>
          </div>  
      </main>
    </div>
  </div>

</template>