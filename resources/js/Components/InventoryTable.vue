<script setup>
import TableCell from './TableCell.vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { debounce } from 'lodash';
import AddModal from "./Modals/AddModal.vue";
import ViewModal from './Modals/ViewModal.vue';
import EditModal from './Modals/EditModal.vue';
import DeleteModal from './Modals/DeleteModal.vue';

const props = defineProps({
    searchItem: String,
    selectedCostRange: String,
    columns: Array,
    rows: Object,
});

const search = ref(props.searchItem || '');
const selectedCostRange = ref(props.selectedCostRange || '');

watch(
    search,
    debounce((output) => {
        router.get('/', { search: output, cost_range: selectedCostRange.value }, { preserveState: true, only: ['items'] });
    })
);

watch(
    selectedCostRange,
    debounce((range) => {
        router.get('/', { search: search.value, cost_range: range }, { preserveState: true });
    })
)

// function deleteItem(id) {
//     if (confirm('Are you sure you want to delete this item?')) {
//         router.delete(`/items/${id}`, { preserveScroll: true });
//     }
// }

const page = usePage();
const user = computed(() => page.props.auth.user);
const userRole = computed(() => user.value?.role);

const canDelete = computed(() => ['admin'].includes(userRole.value));
const canEdit = computed(() => ['admin'].includes(userRole.value));

function getValue(obj, path) {
    return path.split('.').reduce((acc, key) => acc?.[key], obj) ?? 'N/A'
}

const addModalRef = ref(null);
const viewModalRef = ref(null);
const editModalRef = ref(null);
const deleteModalRef = ref(null);


</script>

<template>

    <!-- Add button + filters + search -->
    <div class="flex flex-col sm:flex-row justify-between items-end gap-4 mb-4 mt-[5rem]">
        <AddModal ref="addModalRef">
            <!-- Add Item Button -->
            <template #AddItemButton>
                <button @click="addModalRef.openModal()"
                    class="flex gap-2 bg-[#0E6021] rounded-md text-white px-3 py-2 text-xs sm:text-sm hover:bg-[#2a9754] w-full sm:w-auto justify-center">
                    <i class="fa-solid fa-plus my-[3px]"></i>
                    <span class="font-bold">Add Item</span>
                </button>
            </template>

            <!--ADD FORM CONTENT -->
            <template #InventoryForm>
                <form @submit.prevent="submitItem" class="flex flex-col gap-3">

                    <!-- HEAD TITLE ADD ITEM MODAL FORM-->
                    <div class="font-bold text-2xl">
                        <h2>Add New Item</h2>
                    </div>

                    <!-- <div>
                    <label class="text-sm font-semibold text-gray-700">Item Name</label>
                    <input type="text"
                        class="w-full border rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#0E6021] outline-none"
                        placeholder="Enter item name" required />
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700">Quantity</label>
                    <input type="number"
                        class="w-full border rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#0E6021] outline-none"
                        placeholder="Enter quantity" required />
                </div> -->
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" @click="addModalRef.closeModal()"
                            class="px-3 py-2 text-sm rounded-md  bg-[#F03636] text-white border border-gray-300 hover:bg-[#ec4848]">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-3 py-2 text-sm rounded-md bg-[#0E6021] text-white hover:bg-[#2a9754]">
                            Add
                        </button>
                    </div>
                </form>
            </template>
        </AddModal>

        <!-- UNIT COST FILTER -->

        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full sm:w-auto">
            <div class="flex flex-col w-full sm:w-auto">
                <label class="text-xs font-bold mb-1 sm:mb-0">Location</label>
                <select class="h-8 sm:h-9 w-full sm:w-28 text-xs rounded-md text-gray-600 border">
                    <option value="">Select</option>
                    <option value="0-50000">₱0 - ₱50,000</option>
                    <option value="50000-100000">₱50,000 Above</option>
                </select>
            </div>
            <div class="flex flex-col w-full sm:w-auto">
                <label class="text-xs font-bold mb-1 sm:mb-0">Unit Cost</label>
                <select class="h-8 sm:h-9 w-full sm:w-28 text-xs rounded-md text-gray-600 border"
                    v-model="selectedCostRange">
                    <option value="">Select All</option>
                    <option value="0-50000">₱0 - ₱50,000</option>
                    <option value="50000-100000">₱50,000 Above</option>
                </select>
            </div>

            <div class="w-full sm:w-auto mt-3 relative">
                <!-- Search Icon -->
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>

                <!-- Search Input -->
                <input type="search" placeholder="Search stock item" v-model="search"
                    class="w-full sm:w-64 md:w-96 h-9 sm:h-10 rounded-full pl-10 pr-3 border text-sm" />
            </div>
        </div>
    </div>

    <div>
        <!-- Table (horizontal scroll on small screens) -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse text-left bg-white text-xs sm:text-sm">
                <thead class="bg-[#850038]">
                    <tr class="text-white">
                        <th v-for="col in columns" :key="col.key"
                            class="p-2 sm:p-3 md:p-4 align-middle first:rounded-tl-lg last:rounded-tr-lg">
                            {{ col.label }}
                        </th>
                    </tr>
                </thead>

                <tbody class="text-gray-700">
                    <tr v-for="item in rows.data" :key="item.id" class="even:bg-gray-200">
                        <TableCell v-for="col in columns" :key="col.key">
                            <!-- For normal columns -->
                            <template v-if="col.key !== 'action'">
                                <span v-if="col.format" v-html="col.format(getValue(item, col.key))"></span>
                                <span v-else>{{ getValue(item, col.key) }}</span>
                            </template>

                            <!-- For Action column -->
                            <template v-else>
                                <div class="flex items-center gap-1">
                                    <!-- VIEW MODAL -->
                                    <ViewModal ref="viewModalRef">
                                        <template #ViewItemButton="{ open }">
                                            <button type="button" class="text-blue-600 mx-1" title="View" @click="open">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </template>
                                    </ViewModal>

                                    <!-- EDIT MODAL -->
                                    <EditModal ref="editModalRef">
                                        <template #EditItemButton="{ open }">
                                            <button type="button" class="text-green-600 mx-1" title="Edit"
                                                @click="open">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                        </template>
                                        <!--EDIT FORM CONTENT -->
                                        <template #EditInventory="{ close }">
                                            <form @submit.prevent="submitItem" class="flex flex-col gap-3">

                                                <!-- HEAD TITLE ADD ITEM MODAL FORM-->
                                                <div class="font-bold text-2xl">
                                                    <h2>Edit Item</h2>
                                                </div>

                                                <!-- <div>
                                                    <label class="text-sm font-semibold text-gray-700">Item Name</label>
                                                    <input type="text"
                                                        class="w-full border rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#0E6021] outline-none"
                                                        placeholder="Enter item name" required />
                                                </div>

                                                <div>
                                                    <label class="text-sm font-semibold text-gray-700">Quantity</label>
                                                    <input type="number"
                                                        class="w-full border rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#0E6021] outline-none"
                                                        placeholder="Enter quantity" required />
                                                </div> -->

                                                <div class="flex justify-end gap-2 mt-4">
                                                    <button type="button" @click="close"
                                                        class="px-3 py-2 text-sm rounded-md  bg-[#F03636] text-white border border-gray-300 hover:bg-[#ec4848]">
                                                        Cancel
                                                    </button>
                                                    <button type="submit"
                                                        class="px-3 py-2 text-sm rounded-md bg-[#0E6021] text-white hover:bg-[#2a9754]">
                                                        Add
                                                    </button>
                                                </div>
                                            </form>
                                        </template>
                                    </EditModal>

                                    <!-- DELETE MODAL -->
                                    <DeleteModal :delete-url="`/items/${item.id}`">
                                        <template #DeleteItemButton="{ open }">
                                            <button type="button" class="text-red-600 mx-1" title="Delete"
                                                @click="open">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </template>

                                        <template #DeleteItem="{ close, confirm, message }">
                                            <div class="text-center">
                                                <h2 class="text-lg font-bold text-gray-800 mb-3">Confirm Deletion</h2>
                                                <p class="text-gray-600 mb-5 text-sm">{{ message }}</p>

                                                <div class="flex justify-center gap-3">
                                                    <button @click="close"
                                                        class="px-4 py-2 rounded-md text-sm bg-gray-200 hover:bg-gray-300 font-medium">
                                                        Cancel
                                                    </button>
                                                    <button @click="confirm"
                                                        class="px-4 py-2 rounded-md text-sm bg-red-600 hover:bg-red-700 text-white font-medium">
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    </DeleteModal>

                                </div>
                            </template>
                        </TableCell>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-2 flex justify-end">
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 
                bg-gray-100 border border-gray-300 rounded-md px-3 py-1">
                <p class="text-xs sm:text-sm border-r border-gray-300 px-3">
                    {{ rows.from }}-{{ rows.to }} of
                    {{ rows.total }}
                </p>
                <div>
                    <span v-for="link in rows.links" :key="link.label">
                        <component :is="link.url ? Link : 'span'" :href="link.url || null"
                            class="p-1 text-xs sm:text-sm" :class="{
                                'text-gray-600 hover:underline': link.url,
                                'text-blue-600 font-bold': link.active,
                                'text-gray-300': !link.url
                            }">
                            <!-- Render label or icon -->
                            <i v-if="link.label.includes('Previous')" class="fa-solid fa-chevron-left"></i>
                            <i v-else-if="link.label.includes('Next')" class="fa-solid fa-chevron-right"></i>
                            <span class="px-1" v-else>{{ link.label }}</span>
                        </component>
                    </span>
                </div>
            </div>
        </div>
    </div>

</template>