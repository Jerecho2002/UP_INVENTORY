<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import NavHeader from '@/Components/NavHeader.vue';
import SideBar from '@/Components/SideBar.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InventoryAcknowledgementsTable from '@/Components/InventoryAcknowledgementsTable.vue';

const columns = [
  { label: "", key: "select_all" },
  { label: "Item Name", key: "item_name"},
  { label: "Quantity", key: "quantity" },
  { label: "Unit", key: "unit" },
  { label: "Unit Cost", key: "unit_cost", format: (val) => val ? `₱${val}` : 'N/A' },
  { label: "Property Number", key: "property_number" },
  { label: "PAR/ICS Number", key: 'category' },
  { label: "Suppliers", key: "supplier", format: (val) => val?.supplier_name ?? 'N/A'  },
  { label: "Date Acquired", key: "date_acquired" },
  { label: "Status", key: 'status',
    format: (status) => {
      let label = 'Unknown', cls = 'text-gray-500', icon = '';
      if (status === 0) {
        label = 'Cancelled';
        cls = 'text-[#D32F2F] font-bold bg-[#F8D4D4] py-2 px-4 rounded-full';
        icon = '<i class="fa-solid fa-ban"></i>';
      }
      else if (status === 1) {
        label = 'Recieved';
        cls = 'text-[#2E7D32] font-bold bg-[#D4F8D4] py-2 px-4 rounded-full';
        icon = '<i class="fa-solid fa-circle-check"></i>';
      }
      else if (status === 2) { 
        label = 'Pending'; 
        cls = 'text-[#8D6E00] font-bold bg-[#FFF3CD] py-2 px-4 rounded-full';
        icon = '<i class="fa-solid fa-clock"></i>';
      }
      return `<span class="${cls}">${icon} ${label}</span>`;
    }
  },
  { label: "Action", key: "action" }
];

const accountableField = [
  { label: "Accountable Person", model: "accountable_persons_id", name: "users", option: "email", value: "id" },
  { label: "Issued By", model: "issued_by_id", name: "users", option: "email", value: "id" },
  { label: "Created By", model: "created_by", name: "users", option: "email", value: "id" },
];

// const secondDropdown = [
//   { label: "Status", model: "status", options: 
//                                             [{label: "Recieved", value: "1"},
//                                              {label: "Cancelled", value: "0"},
                                            
//   ]},
// ];

const inputFields = [
  { label: "Room", model: "item_name", placeholder: "Room 000", type: "text" },
];

const itemSelectedField = [
  { label: "Item Selected", model: "item_name"},
];

const page = usePage();
const items = computed(() => page.props.items);
const users = computed(() => page.props.users);

const isSidebarOpen = ref(true);
const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

const firstDropdown = ref(null);
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

      <!-- Main --> 
      <main class="flex-1 sm:p-5 md:p-6 overflow-hidden ">
        <!-- HEAD TITLE -->
       <div class="m-2">
          <PageHeader title="Acknowledgements" />
          <div class="w-full h-full">
            <InventoryAcknowledgementsTable
            :accountableField="accountableField"
            :firstDropdown="firstDropdown"
            :secondDropdown="secondDropdown"
            :inputFields="inputFields"
            :itemSelectedField="itemSelectedField"
            :items="items"
            :users="users"
            :columns="columns"
            @update:selected="ids => selectedIds = ids"
            @selection-changed="ids => console.log('selection', ids)" 
            />  
        </div>
       </div>
      </main>
    </div>
  </div>
</template>