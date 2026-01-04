<script setup>
import { ref, computed, nextTick } from "vue";
import axios from 'axios';
import { usePage, router } from "@inertiajs/vue3";
import NavHeader from "@/Components/NavHeader.vue";
import SideBar from "@/Components/SideBar.vue";
import PageHeader from "@/Components/PageHeader.vue";
import InventoryTable from "@/Components/InventoryTable.vue";
import ImportButton from "@/Components/Buttons/ImportButton.vue";
import ExportButton from "@/Components/Buttons/ExportButton.vue";
import SecondaryButton from "@/Components/Buttons/SecondaryButton.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import ItemFilterControls from "@/Components/Filters/ItemFilterControls.vue";
import AssignedFormModal from "@/Components/Modals/AssignedFormModal.vue";
import dayjs from 'dayjs';

const columns = [
  { label: "", key: "select_all" },
  { label: 'Item Name', key: 'inventory_items', format: (val) => val?.item_name ?? 'N/A' },
  { label: 'Quantity', key: 'inventory_items', format: (val) => val?.quantity ?? 'N/A' },
  { label: 'Unit Cost', key: 'inventory_items', format: (val) => val?.unit_cost ? `₱${(val.unit_cost)}` : 'N/A' },
  { label: 'Property Number', key: 'inventory_items', format: (val) => val?.property_number ?? 'N/A' },
  { label: 'PAR/ICS', key: 'acknowledgement_receipts', format: (val) => val?.category ?? 'N/A' },
  { label: 'Accountable Person', key: 'acknowledgement_receipts.accountable_person', format: (val) => val?.full_name ?? 'N/A' },
  { label: 'Issued By', key: 'acknowledgement_receipts.issued_by', format: (val) => val?.email ?? 'N/A' },
  { label: 'Date Released', key: 'acknowledgement_receipts', format: (val) => val ? dayjs(val.created_at).format('MMM D, YYYY') : 'N/A' },
  { label: "Action", key: "action" }
]

const accountableField = [
  { label: "New Accountable Person", model: "accountable_persons_id", name: "users", option: "email", value: "id" },
  { label: "Issued By", model: "issued_by_id", name: "users", option: "email", value: "id" },
  // { label: "Created By", model: "created_by", name: "users", option: "email", value: "id" }, 
];

const inputFields = [
  { label: "Rooms", model: "item_name", placeholder: "Room 000", type: "text" },
];

const itemSelectedField = [
  { label: "Item Selected", model: "item_name" },
];

const unitCostOptions = [
  {
    label: "Unit Cost", options: [{ label: "₱0-50,000", value: "0-50000" },
    { label: "₱50,000 Above", value: "50000-99999999" },
    ]
  },
];

const page = usePage();
const items = computed(() => page.props.items);

//ITEMS FILTER CONTROL
let search = ref('');
let status = ref(null);
let cost_range = ref(null);

// MODAL FUNCTION
let formMode = ref('create'); // CREATE || EDIT || VIEW
let showFormModal = ref(false);
let currentItem = ref({});

function openAdd(item) {
  formMode.value = 'create';
  currentItem.value = item;
  showFormModal.value = true;
};

// const processedItems = computed(() =>
//   items.value.data.map((item) => {
//     const newItem = { ...item }
//     tableHeader.forEach(col => {
//       if (col.format) {
//         newItem[col.key] = col.format(item[col.key])
//       }
//     })
//     return newItem
//   })
// )

const isSidebarOpen = ref(true);
const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

const tempSelectedIds = ref([])
const isPrinting = ref(false)

const handleSelectionChanged = (ids) => {
  tempSelectedIds.value = ids
  console.log('IDs updated from table:', [...tempSelectedIds.value])
}

const printSelected = async () => {
  if (isPrinting.value) return; // ignore if already printing

  if (!tempSelectedIds.value.length) {
    alert('Please select at least one item to print.')
    return
  }

  console.log('Selected IDs before print:', [...tempSelectedIds.value])

  isPrinting.value = true
  try {
    const response = await axios.post('/print/receipt', {
      ids: tempSelectedIds.value
    })

    if (response.data?.url) {
      window.open(response.data.url, '_blank')
    }
  } catch (error) {
    console.error(error)
    alert('Failed to generate PDF')
  } finally {
    isPrinting.value = false // release lock
  }
}
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
        <PageHeader title="Transactions" />
        <div class="w-full h-screen bg-white flex flex-col rounded-lg shadow-md mt-10">
          <div class="flex flex-col md:flex-row gap-2 justify-end mt-10 mx-2 mb-6">
            <ImportButton />
            <ExportButton />
          </div>
          <div class="flex justify-between mx-2">
            <div class="flex flex-col md:flex-row gap-2">
              <PrimaryButton @click="openAdd">
                <i class="fa-solid fa-exchange-alt"></i>
                <span> Re-Assign</span>
              </PrimaryButton>

              <SecondaryButton @click="printSelected" class="gap-2 text-white text-xs sm:text-sm">
                <i class="fa-solid fa-print"></i>
                <span>Print</span>
              </SecondaryButton>
            </div>


            <ItemFilterControls :search="search" :status="status" :unitCostOptions="unitCostOptions"
              :filterStatus="filterStatus" :cost_range="cost_range" @update:search="search = $event"
              @update:status="status = $event" @update:cost_range="cost_range = $event" :mode="'transactions'" />
          </div>

          <AssignedFormModal v-if="showFormModal" :mode="formMode" :accountableField="accountableField"
            :inputFields="inputFields" :itemSelectedField="itemSelectedField" @close="showFormModal = false" />

          <InventoryTable :columns="columns" :rows="items" :actions="['view', 'delete', 'print']"
            @selection-changed="handleSelectionChanged" />
        </div>
      </main>
    </div>
  </div>
</template>
