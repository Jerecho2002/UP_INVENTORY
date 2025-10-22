<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import PageHeader from "@/Components/PageHeader.vue";
import InventoryTransactionTable from "@/Components/InventoryTransactionTable.vue";


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
      <main class="flex-1 sm:p-5 md:p-6 overflow-hidden mx-2 sm:mx-2 md:mx-0">
        <!-- HEAD TITLE -->
        <PageHeader title="Transactions" />
        <div class="bg-white h-full rounded-lg p-4 drop-shadow-md mt-6">
          <!-- Inventory Transaction Table Component -->
            <InventoryTransactionTable />
        </div>
      </main>
    </div>
  </div>
</template>
