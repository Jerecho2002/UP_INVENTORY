<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import PageHeader from "@/Components/PageHeader.vue";
import ReportFilterForm from "@/Components/ReportFilterForm.vue";


const columns = [
  { label: "Item Name", key: 'item_name' },
  { label: "Description", key: 'description' },
  { label: "Quantity", key: 'quantity' },
  { 
    label: "Status", 
    key: 'status',
    format: (status) => {
      let label = 'Unknown', cls = 'text-gray-500';
      if (status === 0) { 
        label = 'Out of Stock'; 
        cls = 'text-red-700'; 
      } 
      else if (status === 1) { 
        label = 'In Stock'; 
        cls = 'text-[#14B449]'; 
      }

      return `<span class="${cls} font-semibold">${label}</span>`;
    }
  },
  { label: "Action", key: "action" }
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
        <SideBar/>
      </aside>
      <!-- MAIN -->
      <main class="flex-1 sm:p-5 md:p-6 m-2">
        <div>
          <PageHeader title="Reports" />
          <div class="mt-[3rem]">
            <ReportFilterForm  :columns="columns" :rows="items" />
          </div>
        </div>
      </main>

    </div>
  </div>
</template>