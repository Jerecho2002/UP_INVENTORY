<script setup>
import { onMounted, ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import ItemOverview from "@/Components/ItemOverview.vue";
import PageHeader from "@/Components/PageHeader.vue";
import DashboardTable from "@/Components/DashboardTable.vue";
import BarChartCard from "@/Components/BarChartCard.vue";
import SupplierChartCard from "@/Components/SupplierChartCard.vue";
import UserActivity from "@/Components/UserActivity.vue";

const columns = [
  { label: "Property Records", key: 'category' },
  { label: "Property Number", key: 'property_number' },
  { label: "Item Name", key: 'item_name' },
  { label: "Unit", key: 'unit', format: (val) => val ?? 'N/A' },
  { label: "Unit Cost", key: 'unit_cost', format: (val) => val ? `₱${val}` : 'N/A' },
  { label: "Status", key: 'status', 
    format: (status) => {
      let label = 'Unknown', cls = 'text-gray-500', icon = '';
      if (status === 0) { 
        label = 'Inactive'; 
        cls = 'text-[#D32F2F] font-bold bg-[#F8D4D4] py-2 px-4 rounded-full'; 
        icon = '<i class="fa-solid fa-ban"></i>';
      }
      else if (status === 1) { 
        label = 'Active'; 
        cls = 'text-[#2E7D32] font-bold bg-[#D4F8D4] py-2 px-4 rounded-full'; 
        icon = '<i class="fa-solid fa-circle-check"></i>'; 
      }
      else if (status === 2) { 
        label = 'Pending'; 
        cls = 'text-yellow-700'; 
        icon = '<i class="fa-solid fa-clock"></i>';
      }
      return `<span class="${cls}">${icon} ${label}</span>`;
    }
  },
  { label: "Action", key: "action" }
]


const itemOverview = [
  { title: "Total Items", icon: "fa-solid fa-cart-shopping text-[#06B6D4]", bgColor: "bg-[#06B6D4]" },
  { title: "Item Distribution", icon: "fa-solid fa-hand-holding-hand text-[#8B5CF6]", bgColor: "bg-[#8B5CF6]" },
  { title: "Low Stock Items", icon: "fa-solid fa-triangle-exclamation text-[#F59E0B]", bgColor: "bg-[#F59E0B]" },
  { title: "Out of Stock Items", icon: "fa-solid fa-ban text-[#DC2626]", bgColor: "bg-[#DC2626]" },

];

const supplierChart = [
  { name: 'Supplied', icon: 'fa-solid fa-circle text-[#7E19FA]' },
  { name: 'Delivered', icon: 'fa-solid fa-circle text-[#19FA21]' },
  { name: 'Cancelled', icon: 'fa-solid fa-circle text-[#FA2C19]' },
];

const dropdownSupplierChart = [
  { model: "supplier", options: ["Budget Office", "ITC Office", "ILC", "CMO Office"] }
];

const page = usePage();
const items = computed(() => page.props.items);

const isSidebarOpen = ref(true);
const toggleSidebar = () => { isSidebarOpen.value = !isSidebarOpen.value; };
</script>

<template>
  <div class="h-screen flex flex-col bg-gray-100">
    <!-- NAVHEADER -->
    <NavHeader class="flex-shrink-0" @toggleSidebar="toggleSidebar" />

    <!-- SIDERBAR -->
    <div class="flex flex-1 overflow-hidden">
      <aside  class="transition-all duration-600 ease-in-out transform"
    :class="isSidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full opacity-0 w-0'">
        <SideBar/>
      </aside>

      <!-- MAIN CONTENT -->
      <main class="flex-1 sm:p-5 md:p-6 overflow-y-auto mx-2">
        <PageHeader title="Dashboard" />

        <div class="flex flex-col md:flex-row gap-4 my-5">
          <!-- LEFT -->
          <div class="w-full md:w-[80rem]">
            <div>
              <ItemOverview :item-overview="itemOverview" />
            </div>
            <div class="mt-3">
              <BarChartCard />
            </div>
            <div class="my-5">
              <DashboardTable :columns="columns" :rows="items" />
            </div>
          </div>

          <!-- RIGHT -->
          <div class="block w-full md:w-[25rem] space-y-4">
            <div>
              <SupplierChartCard 
              title="Supplier Statistics" 
              :supplier-chart="supplierChart"
              :dropdown-supplier-list="dropdownSupplierChart"
              />
            </div>
            <div>
              <UserActivity title="Recent Activity"/>
            </div>
          </div>
        </div>
      </main>

    </div>
  </div>
</template>
