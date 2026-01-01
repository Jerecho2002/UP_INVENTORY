<script setup>
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import NavHeader from '@/Components/NavHeader.vue';
import SideBar from '@/Components/SideBar.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InventoryTable from '@/Components/InventoryTable.vue';
import AcknowledgementFormModal from '@/Components/Modals/AcknowledgementFormModal.vue';
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue';
import ItemFilterControls from '@/Components/Filters/ItemFilterControls.vue';
import SuccessModal from '@/Components/Modals/SuccessModal.vue';

const columns = [
  { label: "", key: "select_all" },
  { label: "Item Name", key: "item_name"},
  { label: "Unit", key: "unit" },
  { label: "Unit Cost", key: "unit_cost", format: (val) => val ? `₱${val}` : 'N/A' },
  { label: "Property Number", key: "property_number" },
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
  { label: "Accountable Person", model: "accountable_persons_id", name: "accPerson", option: "full_name", value: "id" },
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
const accPerson = computed(() => page.props.accPerson);
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

const showSuccessModal = ref(false);
const successMessage = ref("");

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

function handleSubmit() {
  showSuccessModal.value = true;
  successMessage.value =
    formMode.value === "edit"
      ? "Item updated successfully!"
      : "Item added successfully!";

  showFormModal.value = false;
}

function handleAction() {
  console.log("Action button clicked");
}

//-----------DYNAMIC ICON-------------------
const iconAdded = `
 <svg class="w-16 h-16" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect width="50" height="50" rx="25" fill="#C8EFD4"/>
    <path d="M37.0186 23.9703C37.4469 26.5163 37.055 29.1325 35.8995 31.4412C34.7439 33.7499 32.8846 35.6317 30.5899 36.8148C28.2952 37.9979 25.6839 38.4211 23.133 38.0234C20.582 37.6256 18.2235 36.4274 16.398 34.6019C14.5724 32.7763 13.3742 30.4178 12.9765 27.8669C12.5787 25.316 13.0019 22.7046 14.185 20.4099C15.3681 18.1152 17.2499 16.2559 19.5586 15.1004C21.8673 13.9449 24.4835 13.553 27.0295 13.9813C27.2729 14.0243 27.4894 14.1617 27.632 14.3635C27.7746 14.5654 27.8317 14.8154 27.7909 15.0591C27.7501 15.3029 27.6147 15.5207 27.4142 15.6651C27.2137 15.8096 26.9642 15.869 26.7201 15.8305C25.2414 15.5817 23.7262 15.6582 22.28 16.0546C20.8338 16.4509 19.4913 17.1577 18.3461 18.1256C17.2008 19.0935 16.2802 20.2994 15.6483 21.6593C15.0164 23.0192 14.6885 24.5005 14.6873 26C14.6851 28.5245 15.6129 30.9613 17.2936 32.8449C17.9751 31.8563 18.8409 31.0084 19.8436 30.3477C19.9292 30.2909 20.031 30.2639 20.1335 30.2706C20.236 30.2774 20.3334 30.3176 20.4108 30.3852C21.6843 31.4872 23.3121 32.0937 24.9963 32.0937C26.6805 32.0937 28.3083 31.4872 29.5818 30.3852C29.6592 30.3176 29.7566 30.2774 29.8591 30.2706C29.9616 30.2639 30.0634 30.2909 30.149 30.3477C31.1526 31.0085 32.0195 31.8564 32.7026 32.8449C34.3845 30.9618 35.3136 28.5249 35.3123 26C35.3123 25.4236 35.2645 24.8482 35.1693 24.2797C35.1478 24.1577 35.1506 24.0327 35.1776 23.9119C35.2046 23.791 35.2553 23.6767 35.3268 23.5755C35.3983 23.4744 35.489 23.3884 35.5939 23.3225C35.6988 23.2567 35.8157 23.2122 35.9378 23.1918C36.06 23.1714 36.185 23.1753 36.3056 23.2035C36.4262 23.2316 36.54 23.2834 36.6405 23.3558C36.741 23.4281 36.8261 23.5197 36.891 23.6252C36.9559 23.7307 36.9993 23.848 37.0186 23.9703ZM19.8436 25.0625C19.8436 26.0823 20.146 27.0792 20.7126 27.9272C21.2791 28.7751 22.0844 29.436 23.0266 29.8263C23.9688 30.2165 25.0055 30.3186 26.0058 30.1197C27.006 29.9207 27.9247 29.4296 28.6458 28.7085C29.367 27.9874 29.858 27.0687 30.057 26.0684C30.2559 25.0682 30.1538 24.0315 29.7636 23.0893C29.3733 22.1471 28.7124 21.3418 27.8645 20.7752C27.0165 20.2087 26.0196 19.9063 24.9998 19.9063C23.6323 19.9063 22.3208 20.4495 21.3538 21.4165C20.3868 22.3835 19.8436 23.695 19.8436 25.0625ZM37.8506 15.0242C37.7635 14.9371 37.6601 14.8679 37.5463 14.8207C37.4325 14.7736 37.3105 14.7493 37.1873 14.7493C37.0641 14.7493 36.9421 14.7736 36.8283 14.8207C36.7145 14.8679 36.6111 14.9371 36.524 15.0242L33.4373 18.1121L32.2256 16.8992C32.1385 16.8121 32.0351 16.743 31.9213 16.6959C31.8075 16.6488 31.6855 16.6245 31.5623 16.6245C31.4391 16.6245 31.3172 16.6488 31.2034 16.6959C31.0895 16.743 30.9861 16.8121 30.899 16.8992C30.8119 16.9863 30.7428 17.0897 30.6957 17.2035C30.6486 17.3174 30.6243 17.4393 30.6243 17.5625C30.6243 17.6857 30.6486 17.8077 30.6957 17.9215C30.7428 18.0353 30.8119 18.1387 30.899 18.2258L32.774 20.1008C32.8611 20.188 32.9645 20.2571 33.0783 20.3043C33.1921 20.3515 33.3141 20.3758 33.4373 20.3758C33.5605 20.3758 33.6825 20.3515 33.7963 20.3043C33.9101 20.2571 34.0135 20.188 34.1006 20.1008L37.8506 16.3508C37.9378 16.2637 38.0069 16.1603 38.0541 16.0465C38.1013 15.9327 38.1256 15.8107 38.1256 15.6875C38.1256 15.5643 38.1013 15.4423 38.0541 15.3285C38.0069 15.2147 37.9378 15.1113 37.8506 15.0242Z" fill="#41BD66"/>
  </svg>`;

const iconEdit = `
  <svg class="w-16 h-16" viewBox="0 0 50 50" fill="none">
    <rect width="50" height="50" rx="25" fill="#C8EFD4"/>
    <path d="M38.2962 23.1706C38.2896 22.7431 38.2812 22.3321 38.2712 21.9375C37.8312 21.9375 37.2756 21.9363 36.6712 21.93C35.2519 21.9156 33.5194 21.875 32.3794 21.7656C30.2962 21.5656 28.6762 19.9056 28.4756 17.8125C28.3663 16.6719 28.3287 14.9531 28.3169 13.5481C28.3119 12.9469 28.3119 12.395 28.3131 11.9594C27.6298 11.9452 26.9004 11.9379 26.125 11.9375C22.1781 11.9375 19.4537 12.1394 17.7387 12.3356C15.9294 12.5419 14.555 13.9175 14.3556 15.73C14.1519 17.5763 13.9375 20.6438 13.9375 25.375C13.9375 30.1063 14.1519 33.1737 14.3556 35.02C14.5556 36.8325 15.9294 38.2075 17.7387 38.4144C19.3337 38.5969 21.8019 38.7844 25.3144 38.8094V38.7375C25.3162 37.3456 25.3981 36.3119 25.4875 35.5969C25.6312 34.45 26.2062 33.5094 26.8937 32.8219L35.3825 24.3325C36.0588 23.6562 37.1306 23.0925 38.2962 23.1706ZM25.14 17.2606C25.3045 17.0244 25.3692 16.7329 25.3202 16.4493C25.2712 16.1657 25.1123 15.9128 24.8781 15.7455C24.6439 15.5783 24.3532 15.5101 24.069 15.5557C23.7848 15.6013 23.5301 15.7572 23.36 15.9894L20.985 19.3138L19.3981 17.7263C19.2972 17.6218 19.1765 17.5385 19.043 17.4812C18.9096 17.4239 18.7661 17.3938 18.6208 17.3925C18.4756 17.3913 18.3316 17.419 18.1972 17.474C18.0628 17.5291 17.9407 17.6103 17.838 17.713C17.7353 17.8157 17.6541 17.9379 17.5992 18.0723C17.5442 18.2067 17.5166 18.3507 17.5179 18.496C17.5191 18.6412 17.5493 18.7847 17.6067 18.9181C17.664 19.0516 17.7474 19.1723 17.8519 19.2731L20.3519 21.7731C20.4642 21.8854 20.5996 21.9719 20.7487 22.0265C20.8978 22.0812 21.057 22.1027 21.2153 22.0897C21.3736 22.0766 21.5271 22.0292 21.6652 21.9508C21.8033 21.8724 21.9227 21.7648 22.015 21.6356L25.14 17.2606ZM24.8856 22.61C25.1217 22.7786 25.281 23.034 25.3287 23.3201C25.3765 23.6062 25.3086 23.8996 25.14 24.1356L22.015 28.5106C21.9227 28.6398 21.8033 28.7474 21.6652 28.8258C21.5271 28.9042 21.3736 28.9516 21.2153 28.9647C21.057 28.9777 20.8978 28.9562 20.7487 28.9015C20.5996 28.8469 20.4642 28.7604 20.3519 28.6481L17.8519 26.1481C17.7459 26.0476 17.6611 25.9269 17.6025 25.7931C17.5439 25.6592 17.5127 25.515 17.5108 25.369C17.5089 25.2229 17.5362 25.0779 17.5912 24.9426C17.6462 24.8073 17.7278 24.6843 17.8311 24.5811C17.9343 24.4778 18.0573 24.3962 18.1926 24.3412C18.3279 24.2862 18.4729 24.2589 18.619 24.2608C18.765 24.2627 18.9092 24.2939 19.0431 24.3525C19.1769 24.4111 19.2976 24.4959 19.3981 24.6019L20.9856 26.1888L23.36 22.8638C23.5287 22.6278 23.7842 22.4686 24.0703 22.421C24.3564 22.3734 24.6497 22.4414 24.8856 22.61ZM30.1919 13.5325C30.2038 14.9419 30.2419 16.5844 30.3419 17.6337C30.4594 18.8575 31.3944 19.7875 32.5581 19.8994C33.6081 20 35.2662 20.0406 36.69 20.0556C37.1919 20.0606 37.6587 20.0619 38.0531 20.0619C37.6825 19.1912 36.7825 17.5975 34.6581 15.5144C32.6562 13.5519 31.0994 12.6575 30.1875 12.2581C30.1875 12.6319 30.1881 13.0669 30.1919 13.5325ZM41.4669 30.4163C42.0369 29.8463 42.2856 29.0256 41.8838 28.3263C41.6081 27.8463 41.1819 27.25 40.5281 26.5969C39.875 25.9431 39.2781 25.5169 38.7987 25.2406C38.0994 24.8387 37.2787 25.0881 36.7087 25.6588L35.5325 26.8344C36.1937 27.2031 37.0569 27.8031 38.0212 28.7669C39.0413 29.7869 39.6531 30.6931 40.015 31.3669C40.0783 31.4856 40.1344 31.5967 40.1831 31.7L41.4669 30.4163ZM34.535 28.4256C34.4046 28.3551 34.2711 28.2905 34.135 28.2319L28.22 34.1475C27.7587 34.6081 27.4288 35.1825 27.3481 35.8288C27.2688 36.4638 27.1906 37.4212 27.1887 38.7406C27.1885 38.8977 27.2193 39.0533 27.2793 39.1985C27.3393 39.3436 27.4273 39.4755 27.5384 39.5866C27.6495 39.6977 27.7814 39.7857 27.9265 39.8457C28.0717 39.9057 28.2273 39.9365 28.3844 39.9363C29.7037 39.9338 30.6613 39.8562 31.2956 39.7769C31.9425 39.6956 32.5169 39.3662 32.9775 38.905L38.7337 33.1488C38.7054 33.0873 38.6838 33.023 38.6694 32.9569C38.6567 32.9139 38.6426 32.8714 38.6269 32.8294C38.553 32.6314 38.4648 32.439 38.3631 32.2537C38.0881 31.7413 37.5856 30.9831 36.6956 30.0931C35.805 29.2025 35.0469 28.7006 34.535 28.4256Z" fill="#41BD66"/>
  </svg>`;


const successIcon = computed(() => {
  return formMode.value === "edit" ? iconEdit : iconAdded;
});

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
              :accPerson="accPerson"
              :users="users"
              :itemSelectedField="itemSelectedField"
              :selectedIDs="selectedIds.value"
              :items="items"
              @submit="handleSubmit"
              @close="() => showFormModal = false"
            />

            <SuccessModal 
              v-if="showSuccessModal"
              :icon="successIcon"
              :title="formMode === 'edit' ? 'Edit Success' : 'Added Success'"
              :message="formMode === 'edit' ? 'Edit successfully!' : 'Assign successfully!'"
              @action="handleAction"
              @close="showSuccessModal = false" 
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