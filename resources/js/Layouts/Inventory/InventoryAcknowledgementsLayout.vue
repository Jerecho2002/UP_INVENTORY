<script setup>
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import NavHeader from '@/Components/NavHeader.vue';
import SideBar from '@/Components/SideBar.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InventoryTable from '@/Components/InventoryTable.vue';
import AcknowledgementFormModal from '@/Components/Modals/AcknowledgementFormModal.vue';
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue';
import ItemFilterControls from '@/Components/ItemFilterControls.vue';

const columns = [
  { label: "", key: "select_all" },
  { label: "Item Name", key: "item_name"},
  { label: "Unit", key: "unit" },
  { label: "Unit Cost", key: "unit_cost", format: (val) => val ? `₱${val}` : 'N/A' },
  { label: "Property Number", key: "property_number" },
  { label: "PAR/ICS Number", key: 'category' },
  { label: "Serial Number", key: 'serial_number' },
  { label: "Invoice", key: 'invoice' },
  { label: "Supplier Name", key: "supplier", format: (val) => val?.supplier_name ?? 'N/A'  },
  
  { label: "Status", key: 'status',
    format: (status) => {
      let label = 'Unknown', cls = 'text-gray-500', icon = '';
      if (status === 1) {
        label = 'Unassigned';
        cls = 'text-[#D32F2F] font-bold bg-[#F8D4D4] py-2 px-4 rounded-full';
        icon = '<i class="fa-solid fa-ban"></i>';
      }
      return `<span class="${cls}">${icon} ${label}</span>`;
    }
  },
  { label: "Action", key: "action" }
];

const accountableField = [
  { label: "Accountable Person", model: "accountable_persons_id", name: "users", option: "email", value: "id" },
  { label: "Issued By", model: "issued_by_id", name: "users", option: "email", value: "id" },
  // { label: "Created By", model: "created_by", name: "users", option: "email", value: "id" }, 
];

const inputFields = [
  { label: "Rooms", model: "item_name", placeholder: "Room 000", type: "text" },
];

const itemSelectedField = [
  { label: "Item Selected", model: "item_name"},
];

const page = usePage();
const items = computed(() => page.props.items);
const users = computed(() => page.props.users);

//INVENTORY FILTER 
let search = ref('');
let status = ref(null);
let cost_range = ref(null);

// MODAL FUNCTION
let formMode = ref('create'); // CREATE || EDIT || VIEW
let showFormModal = ref(false);
let showDeleteModal = ref(false);
let currentItem = ref({});

function openAdd(item) {
  formMode.value = 'create';
  currentItem.value = item;
  showFormModal.value = true;
  console.log("Selected IDs before opening modal:", selectedIds.value);
};

function handleView(item) {
  formMode.value = 'view';
  currentItem.value = item;
  showFormModal.value = true;
}

function handleSubmit (item) {
  router.post('/inventory/acknowledgements/store', item, {
    onSuccess: () => {
      showFormModal.value = false;
      refreshItems();
    },
    onError: (errors) => {
      console.error('Validation errors:', errors);
    }
  });
}

function refreshItems() {
  router.reload({
    only: ['items']
  });
}

const isSidebarOpen = ref(true);
const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

const selectedIds = ref([]);

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
      <main class="flex-1 sm:p-5 md:p-6 overflow-y-auto">
        <!-- HEAD TITLE -->
       <div class="m-2">
          <PageHeader title="Acknowledgements" />
          <div class="w-full h-full">
            <div class="flex flex-col md:flex-row justify-between mt-12">
              <PrimaryButton @click="openAdd">
                <i class="fa-solid fa-user-plus"></i>
                <span> Assign </span>
              </PrimaryButton>

            <ItemFilterControls 
              :search="search"
              :status="status"
              @update:search="search = $event"
              @update:status="status = $event"
              :mode="'acknowledgements'"
            />

            </div>
            <AcknowledgementFormModal 
              v-if="showFormModal"
              :mode="formMode"
              :accountableField="accountableField"
              :inputFields="inputFields"
              :users="users"
              :itemSelectedField="itemSelectedField"
              :selectedIDs="selectedIds.value"
              :items="items"
              @submit="handleSubmit"
              @close="() => showFormModal = false"
            />

            <InventoryTable
              :rows="items"
              :columns="columns"
              @view="handleView"
              @update:selected="ids => selectedIds.value = ids"
              @selection-changed="ids => console.log('Item Selected', ids)" 
            />  
        </div>
       </div>
      </main>
    </div>
  </div>
</template>