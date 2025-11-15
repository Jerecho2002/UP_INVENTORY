<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import NavHeader from '@/Components/NavHeader.vue';
import SideBar from '@/Components/SideBar.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InventoryAcknowledgementsTable from '@/Components/InventoryAcknowledgementsTable.vue';

const columns = [
  { label: '', key: 'select' },
  { label: "Item Name", key: "inventory_items", format: (val) => val?.item_name ?? 'N/A'  },
  { label: "Quantity", key: "inventory_items", format: (val) => val?.quantity ?? 'N/A'  },
  { label: "Unit", key: "inventory_items", format: (val) => val ?.unit ?? 'N/A' },
  { label: "Unit Cost", key: "inventory_items", format: (val) => val ?.unit_cost ? `₱${val.unit_cost}` : 'N/A' },
  { label: "Propety Number", key: "property_number" },
  { label: "Accountable Person", key: 'accountable_person.user', format: (val) => val?.email ?? 'N/A'   },
  { label: "Issued By", key: "issuedBy", format: (val) => val?.full_name ?? 'N/A'  },
  { label: "Date Recieved", key: "par_date" },
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
   { label: "Accountable Person", model: "", name: "accountablePerson", option: "classification_name"},
   { label: "Issued By", model: "", name: "issued_by", option: "issued_by"},
   { label: "Created By", model: "", name: "created_by", option: "creadted_by"},
];

const secondDropdown = [
  { label: "Status", model: "status", options: 
                                            [{label: "Recieved", value: "1"},
                                             {label: "Cancelled", value: "0"},
                                            
  ]},
];

const inputFields = [
  { label: "Room", model: "item_name", placeholder: "Room 000", type: "text" },
  { label: "Item Name", model: "item_name", placeholder: "Laptops, Ceiling Fan...", type: "text" },
];

// const invoicesFundFields = [
//   { label: "Invoice Number", model: "invoice_id", placeholder: "0000", type: "text", readonly: false },
//   { label: "Fund Source", model: "fund_source_id", placeholder: "000", type: "text", readonly: false },
// ];

const requestFields = [
  { label: "Purchase Request", model: "pr_number", placeholder: "PR-###", type: "text" },
  { label: "Purchase Orders", model: "po_number", placeholder: "PO-###", type: "text" },
  { label: "Remarks", model: "remarks", placeholder: "RM-###", type: "text" },
]

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
      <aside  class="transition-all duration-600 ease-in-out transform"
    :class="isSidebarOpen ? 'translate-x-0 opacity-100 w-64' : '-translate-x-full opacity-0 w-0'">
        <SideBar/>
      </aside>

      <!-- Main --> 
      <main class="flex-1 sm:p-5 md:p-6 overflow-hidden ">
        <!-- HEAD TITLE -->
        <PageHeader title="Acknowledgements" />
        <div class="w-full h-full">
          <InventoryAcknowledgementsTable
          :accountableField="accountableField"
          :firstDropdown="firstDropdown"
          :secondDropdown="secondDropdown"
          :inputFields="inputFields"
          :invoicesFundFields="invoicesFundFields"
          :requestFields="requestFields"
          :items="items"
          :columns="columns"
          @update:selected="ids => selectedIds = ids"
          @selection-changed="ids => console.log('selection', ids)" 
          />  
        </div>
      </main>
    </div>
  </div>
</template>