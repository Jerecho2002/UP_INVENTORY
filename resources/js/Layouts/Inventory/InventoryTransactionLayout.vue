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

const page = usePage();
const items = computed(() => page.props.items);

const tableHeader = [
  { label: 'Item Name', key: 'inventory_item', format: (val) => val?.item_name ?? 'N/A' },
  { label: 'Office Name', key: 'inventory_item', format: (val) => val?.location.office.office_name ?? 'N/A' },
  { label: 'Quantity', key: 'quantity' },
  { label: 'Unit Cost', key: 'inventory_item', format: (val) => val?.unit_cost ? `₱${(val.unit_cost)}` : 'N/A' },
  { label: 'Property Number', key: 'inventory_item', format: (val) => val?.category ?? 'N/A' },
  { label: 'PR Number', key: 'inventory_item', format: (val) => val?.pr_number ?? 'N/A' },
  { label: 'PO Number', key: 'inventory_item', format: (val) => val?.po_number ?? 'N/A' },
  { label: 'Invoice Number', key: 'inventory_item', format: (val) => val?.invoice.invoice_number ?? 'N/A' },
  {
    label: "Status", key: 'status',
    format: (status) => {
      let label = 'Unknown', cls = 'text-gray-500', icon = '';
      if (status === 1) {
        label = 'Completed';
        cls = 'text-[#2E7D32] font-bold bg-[#D4F8D4] py-2 px-4 rounded-full';
        icon = '<i class="fa-solid fa-circle-check"></i>';
      }
      else if (status === 0) { 
        label = 'Pending'; 
        cls = 'text-[#8D6E00] font-bold bg-[#FFF3CD] py-2 px-4 rounded-full';
        icon = '<i class="fa-solid fa-clock"></i>';
      }
      return `<span class="${cls}">${icon} ${label}</span>`;
    }
  },
  { label: "Action", key: "action" }
]

const processedItems = computed(() =>
  items.value.data.map((item) => {
    const newItem = { ...item }
    tableHeader.forEach(col => {
      if (col.format) {
        newItem[col.key] = col.format(item[col.key])
      }
    })
    return newItem
  })
)

</script>

<template>
  <div class="h-screen flex flex-col bg-gray-100 overflow-hidden">
    <!-- Pass toggle event -->
    <NavHeader class="flex-shrink-0" @toggleSidebar="toggleSidebar" />

    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <aside class="transition-all duration-600 ease-in-out transform"
        :class="isSidebarOpen ? 'translate-x-0 opacity-100 w-64' : '-translate-x-full opacity-0 w-0'">
        <SideBar />
      </aside>

      <!-- Main -->
      <main class="flex-1 sm:p-5 md:p-6  mx-2 sm:mx-2 md:mx-0 overflow-y-auto">
        <!-- HEAD TITLE -->
        <PageHeader title="Transactions" />
        <div class="bg-white rounded-lg p-4 drop-shadow-md mt-8 ">
          <!-- Inventory Transaction Table Component -->
          <InventoryTransactionTable :tableHeader=tableHeader :items=items />
        </div>
      </main>
    </div>
  </div>
</template>
