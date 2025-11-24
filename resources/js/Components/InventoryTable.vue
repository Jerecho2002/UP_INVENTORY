<script setup>
import { usePage, useForm, Link, router } from '@inertiajs/vue3';
import { reactive, computed, ref, watch } from 'vue';
import { debounce } from 'lodash';
import TableCell from './TableCell.vue';


const props = defineProps({
    // rooms: Array, // -> InventoryTable
    columns: Array, // -> InventoryTable
    rows: Object, // -> InventoryTable
    unitCostOptions: Array, // InventoryFilter
    Status: Array, // -> InventoryFilter
    itemClass: Array, // InventoryForm
    suppliers: Array, // InventoryForm
    viewItems: Array, // InventoryForm
    inputFields: Array, // InventoryForm
    inputFieldsEdit: Array, // InventoryForm
    quantityCostFields: Array, // InventoryForm
    firstDropdown: Array, // InventoryForm
    secondDropdown: Array, // InventoryForm
    requestFields: Array, // InventoryForm
    invoicesFundFields: Array, // InventoryForm
    suppliers: Array,
    search: String, //InventoryFilter
    cost_range: String, //InventoryFilter
    status: String, // InventoryFilter
});

defineEmits(['view', 'edit', 'delete']); 

const page = usePage();
const user = computed(() => page.props.auth.user);
const userRole = computed(() => user.value?.role);

const canDelete = computed(() => ['admin'].includes(userRole.value));
const canEdit = computed(() => ['admin'].includes(userRole.value));

// PAGINATE
const goToPage = (url) => {
    if (!url) return;
    router.visit(url, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
};

function getValue(obj, path) {
    return path.split('.').reduce((acc, key) => acc?.[key], obj) ?? 'N/A'
}

</script>

<template>
    <!-- <pre>{{ rows }}</pre> -->
    <!-- Table (horizontal scroll on small screens) -->
    <div class="overflow-x-auto mt-3">
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
                            <div class="flex items-center gap-2">
                                <button @click="$emit('view', item)"
                                    class="text-[#3F3F3F] hover:text-[#6d6d6d]" title="View Item">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                                
                                <button @click="$emit('edit', item)"
                                    class="text-[#54B3AB] hover:text-[#66a9a3]" title="Edit Item">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <button @click="$emit('delete', item)"
                                    class="text-red-600 hover:text-red-800" title="Delete Item">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
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
                    <span v-if="link.url" @click="goToPage(link.url)" class="cursor-pointer p-1 text-xs sm:text-sm"
                        :class="{
                            'text-gray-600 hover:underline': link.url,
                            'text-blue-600 font-bold': link.active
                        }">
                        <!-- Render label or icon -->
                        <i v-if="link.label.includes('Previous')" class="fa-solid fa-chevron-left"></i>
                        <i v-else-if="link.label.includes('Next')" class="fa-solid fa-chevron-right"></i>
                        <span class="px-1" v-else>{{ link.label }}</span>
                    </span>
                </span>
            </div>
        </div>
    </div>
</template>