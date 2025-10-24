<script setup>
import { ref } from 'vue';
import NavHeader from '@/Components/NavHeader.vue';
import SideBar from '@/Components/SideBar.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InventoryAcknowledgementsTable from '@/Components/InventoryAcknowledgementsTable.vue';

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
      <main class="flex-1 sm:p-5 md:p-6 overflow-hidden ">
        <!-- HEAD TITLE -->
        <PageHeader title="Acknowledgements" />
        <div class="w-full h-full">
          <InventoryAcknowledgementsTable 
          />
        </div>
      </main>
    </div>
  </div>
</template>