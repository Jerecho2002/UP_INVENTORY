<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import ItemOverview from "@/Components/ItemOverview.vue";
import PageHeader from "@/Components/PageHeader.vue";
import DashboardTable from "@/Components/DashboardTable.vue";

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
  { label: "Location", key: 'property.location.location_name'},
  { label: "Status", key: 'status',
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

const page = usePage();
const items = computed(() => page.props.items);

const isSidebarOpen = ref(true);
const toggleSidebar = () => { isSidebarOpen.value = !isSidebarOpen.value; };
</script>

<template>
  <div class="h-screen flex flex-col bg-gray-100 overflow-hidden">
    <NavHeader class="flex-shrink-0" @toggleSidebar="toggleSidebar" />
    <div class="flex flex-1 overflow-hidden">
      <aside v-show="isSidebarOpen">
        <SideBar :menu-items="menuItems" />
      </aside>
      <main class="flex-1 sm:p-7 md:p-8 overflow-hidden">
        <PageHeader title="Dashboard" />
        <div class="w-full h-screen text-left text-xs sm:text-sm mt-[1rem]">
          <div class="flex flex-col md:flex-row gap-4 m-2">
            <ItemOverview title="Total Items" icon="fa-solid fa-cart-shopping" bgColor="bg-[#06B6D4]" textColor="text-[#06B6D4]" />
            <ItemOverview title="Item Distribution" icon="fa-solid fa-hand-holding-hand" bgColor="bg-[#8B5CF6]" textColor="text-[#8B5CF6]" />
            <ItemOverview title="Low Stock Items" icon="fa-solid fa-triangle-exclamation" bgColor="bg-[#F59E0B]" textColor="text-[#F59E0B]" />
            <ItemOverview title="Out of Stock Items" icon="fa-solid fa-ban" bgColor="bg-[#DC2626]" textColor="text-[#DC2626]" />
          </div>
          <div class="w-full h-full">
            <DashboardTable :columns="columns" :rows="items" />
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
