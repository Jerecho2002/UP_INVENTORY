<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import ItemOverview from "@/Components/ItemOverview.vue";
import PageHeader from "@/Components/PageHeader.vue";
import DashboardTable from "@/Components/DashboardTable.vue";
import BarChartCard from "@/Components/BarChartCard.vue";
import SupplierChartCard from "@/Components/SupplierChartCard.vue";
import UserActivity from "@/Components/UserActivity.vue";

const menuItems = [
  { name: "Dashboard", icon: "fa-solid fa-table-cells-large", route: "dashboard.index" },
  { name: "Inventory", icon: "fa-solid fa-boxes-packing", route: "inventory.index" },
  { name: "Reports", icon: "fa-solid fa-file-export", route: "reports.index" },
  { name: "Suppliers", icon: "fa-solid fa-handshake", route: "suppliers.index" },
  { name: "Purchase", icon: "fa-solid fa-box-open", route: "purchase.index" },
  { name: "Item Disposal", icon: "fa-solid fa-recycle", route: "item_disposal.index" },
];

const columns = [
  { label: "Item Name", key: 'item_name' },
  { label: "Description", key: 'description' },
  { label: "Unit", key: 'unit', format: (val) => val ?? 'N/A' },
  { label: "Unit Cost", key: 'unit_cost', format: (val) => val ? `₱${val}` : 'N/A' },
  { label: "Location", key: 'property.location.location_name' },
  {
    label: "Status", key: 'status',
    format: (status) => {
      let label = 'Unknown', cls = 'text-gray-500';
      if (status === 0) { label = 'Inactive'; cls = 'text-red-700'; }
      else if (status === 1) { label = 'Active'; cls = 'text-[#14B449]'; }
      else if (status === 2) { label = 'Pending'; cls = 'text-yellow-700'; }
      return `<span class="${cls}">${label}</span>`;
    }
  },
  { label: "Action", key: "action" }
];

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
      <aside v-show="isSidebarOpen">
        <SideBar :menu-items="menuItems" />
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
