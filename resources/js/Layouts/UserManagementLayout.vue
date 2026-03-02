<script setup>
import { ref, computed } from "vue";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import PageHeader from "@/Components/PageHeader.vue";
import InventoryTable from "@/Components/InventoryTable.vue";

const columns = [
  { label: "Full Name"},
  { label: "Email"},
  { label: "Role"},
  { label: "Status"},
  { label: "Action"},
];

const isSidebarOpen = ref(true);
const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};
</script>

<template>
  <div class="h-screen flex flex-col bg-gray-100 overflow-hidden">
    <NavHeader class="flex-shrink-0" @toggleSidebar="toggleSidebar" />

    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <aside
        class="transition-all duration-600 ease-in-out transform"
        :class="
          isSidebarOpen
            ? 'translate-x-0 opacity-100'
            : '-translate-x-full opacity-0 w-0'
        "
      >
        <SideBar />
      </aside>

      <!-- Main -->
      <main class="flex-1 sm:p-5 md:p-6 md:mx-0 overflow-y-auto">
        <div class="m-2">
            <PageHeader title="User Management" />

            <!-- <InventoryTable /> -->
        </div>
      </main>
    </div>
  </div>
</template>