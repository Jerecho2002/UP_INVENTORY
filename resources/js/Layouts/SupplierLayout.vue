<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import PageHeader from "@/Components/PageHeader.vue";
import InventoryTable from "@/Components/InventoryTable.vue";
import ItemFilterControls from "@/Components/Filters/ItemFilterControls.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";


const columns = [
  { label: "Supplier Name", key: 'supplier_name' },
  { label: "Contact", key: 'contact_no' },
  { label: "Email", key: 'email' },
  { label: "Address" , key: 'address' },
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
      return `<span class="${cls}">${icon} ${label}</span>`;
    }
  },
  { label: "Action", key: "action" }
]

const supplierOption = [
  { label: "Supplier Name", model: "supplier_name", placeholder: "Supplier Name", type: "text" },
  { label: "Contact Number", model: "contact_no", placeholder: "Contact Number", type: "text" },
  { label: "Email", model: "email", placeholder: "Email", type: "text" },
  { label: "Address", model: "address", placeholder: "Address", type: "text" },
];

const statusDropdown = [
  { label: "Status", model: "status", options: 
                                            [{label: "Active", value: "1"},
                                             {label: "Inactive", value: "0"},
                                            
  ]},
];

const page = usePage();
const items = computed(() => page.props.items || []); 
const suppliers = computed(() => page.props.suppliers || []);

let formMode = ref('create'); // CREATE || EDIT || VIEW
let showFormModal = ref(false);
let currentItem = ref({});

function openAdd(item) {
  currentItem.value = item;
  showFormModal.value = true;
};


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
       <main class="flex-1 sm:p-5 md:p-6 overflow-hidden m-2">
            <PageHeader title="Suppliers" />
              <div class="w-full h-full">
                <div class="mt-10 flex flex-col md:flex-row gap-4 justify-between">
                  <PrimaryButton @click="openAdd()">
                    <i class="fa-solid fa-user-group"></i>
                    <span>Add Supplier</span>
                  </PrimaryButton>

                  <ItemFilterControls />
                </div>

                <InventoryTable 
                  :columns="columns" 
                  :rows="suppliers"
                />
              </div>
       </main>
       
    </div>
  </div>
</template>