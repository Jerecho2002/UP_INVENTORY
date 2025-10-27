<script setup>
import { ref } from "vue";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import PageHeader from "@/Components/PageHeader.vue";


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
      <aside  class="transition-all duration-600 ease-in-out transform"
    :class="isSidebarOpen ? 'translate-x-0 opacity-100 w-64' : '-translate-x-full opacity-0 w-0'">
        <SideBar/>
      </aside>
      <!-- MAIN -->
       <main class="flex-1 sm:p-5 md:p-6 m-2">
          <div>
            <PageHeader title="Item Disposal" />
              <div class="mt-[3rem]">
                
              </div>
          </div>
       </main>
    </div>
  </div>
</template>