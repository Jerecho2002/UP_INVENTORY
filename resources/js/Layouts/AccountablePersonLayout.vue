  <script setup>
  import { ref, computed } from "vue";
  import { usePage } from "@inertiajs/vue3";
  import NavHeader from "@/Components/NavHeader.vue";
  import SideBar from "@/Components/SideBar.vue";
  import PageHeader from "@/Components/PageHeader.vue";
  import InventoryTable from "@/Components/InventoryTable.vue";
  import ItemFilterControls from "@/Components/Filters/ItemFilterControls.vue";
  import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
  import AccountablePersonFormModal from "@/Components/Modals/AccountablePersonFormModal.vue";


  const columns = [
    { label: "Accountable Person Name", key: 'full_name' },
    { label: "Department", key: 'department' },
    { label: "Position", key: 'position' },
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

  const accountablePersonFields = [
    { label: "Accountable Person Name", model: "full_name", placeholder: "Accountable Person Name", type: "text" },
    { label: "Department", model: "department", placeholder: "Department", type: "text" },
    { label: "Position", model: "position", placeholder: "Position", type: "text" },
  ];

  const page = usePage();
  const accountablePerson = computed(() => page.props.accountablePerson || []);

  //ITEMS FILTER CONTROL
  let search = ref('');

  let formMode = ref('create'); // CREATE || EDIT || VIEW
  let showFormModal = ref(false);
  let currentAccountablePerson = ref({})

  const showSuccessModal = ref(false);

  function openAdd() {
    formMode.value = 'create';
    currentAccountablePerson.value = {};
    showFormModal.value = true;
  }

function handleSubmit(form) {
  if (formMode.value === 'create') {
    form.post(route('accountable.store'), {
      preserveScroll: true,
      onSuccess: () => {
        showFormModal.value = false
        form.reset()
      },
    })
  } else {
    form.put(route('accountable.update', form.id), {
      preserveScroll: true,
      onSuccess: () => {
        showFormModal.value = false
        form.reset()
      },
    })
  }
}

function handleEdit(accountablePerson) {
  formMode.value = 'edit'
  currentAccountablePerson.value = accountablePerson
  showFormModal.value = true
}


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
        <main class="flex-1 sm:p-5 md:p-6 overflow-y-auto m-2">
              <PageHeader title="AccountablePerson" />
                <div class="w-full h-full">
                  <div class="mt-10 flex flex-col md:flex-row gap-4 justify-between">
                    <PrimaryButton @click="openAdd()">
                      <i class="fa-solid fa-user-group"></i>
                      <span>Add Accountable Person</span>
                    </PrimaryButton>

                    <ItemFilterControls 
                    :search="search"
                    @update:search="search = $event"
                    :mode="'accountable-person'"
                    />
                  </div>

                  <AccountablePersonFormModal 
                    v-if="showFormModal"
                    :mode="formMode"
                    :accountablePerson="currentAccountablePerson"
                    :accountablePersonFields="accountablePersonFields"
                    @submit="handleSubmit"
                    @close="showFormModal = false"
                  />

                  <InventoryTable 
                    :columns="columns" 
                    :rows="accountablePerson"
                  />
                </div>
        </main>
        
      </div>
    </div>
  </template>