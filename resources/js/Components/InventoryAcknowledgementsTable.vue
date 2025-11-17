<script setup>
import TableCell from './TableCell.vue';
import { usePage, useForm, Link, router } from '@inertiajs/vue3';
import { reactive, computed, ref, watch } from 'vue';
import { debounce } from 'lodash';
import AddModal from './Modals/AddModal.vue';

const props = defineProps({
    accountableField: Array,
    secondDropdown: Array,
    inputFields: Array,
    itemSelectedField: Array,
    columns: Array,
    items: Object,
    users: Array,
});

const form = useForm({
    inventory_item_id: [],
    accountable_persons_id: "",
    issued_by_id: "",
    created_by: "",
    par_date: "",
    remarks: "",
});

const search = ref(props.search || '');

watch(
    search,
    debounce((output) => {
        router.get('/inventory/acknowledgements', { search: output }, {
            preserveState: true,
            replace: true
        });
    })
);

const goToPage = (url) => {
    if (!url) return;
    router.visit(url, {
        preserveState: true,
        replace: true,
        preserveScroll: true,
    });
};

const addModalRef = ref(null);

function getValue(obj, path) {
    return path.split('.').reduce((acc, key) => acc?.[key], obj);
}

const selectedIDs = ref([]);

const allSelected = computed(() => {
    return props.items?.data?.length > 0 &&
        props.items.data.every(i => selectedIDs.value.includes(i.id));
});

function toggleSelectAll() {
    if (allSelected.value) {
        selectedIDs.value = [];
    } else {
        selectedIDs.value = props.items.data.map(i => i.id);
    }
}

function toggleCheck(item) {
    const id = item.id;
    if (selectedIDs.value.includes(id)) {
        selectedIDs.value = selectedIDs.value.filter(x => x !== id);
    } else {
        selectedIDs.value.push(id);
    }
}

// watch(selectedIDs, (val) => {
//     form.inventory_item_id = [...val];
// });

function handleAddItem(closeModal) {
    form.inventory_item_id = [...selectedIDs.value];
    form.post(route("inventory.acknowledgements.store"), {
        onSuccess: () => {
            closeModal();
            form.reset();
            selectedIDs.value = [];
        }
    });
}

</script>


<template>
    <!-- <pre>{{ items }}</pre> -->
    <div class="flex flex-col sm:flex-row items-end mb-4 mt-[3rem]">
        <AddModal ref="addModalRef">
            <template #AddAssignButton>
                <button @click="addModalRef.openModal()"
                    class="flex items-center gap-2 bg-[#0E6021]  hover:bg-[#2a9754] text-white font-semibold px-4 py-2 rounded-md text-xs sm:text-sm transition duration-150 w-full sm:w-auto">
                    <i class="fa-solid fa-user-check"></i>
                    <span>Assign</span>
                </button>
            </template>

            <template #AcknowledgementForm="{ closeModal }">
                <form @submit.prevent="handleAddItem(closeModal)" class="flex flex-col gap-3 sm:overflow-y-auto ">
                    <h2 class="text-2xl font-bold text-[#850038] mb-6">Assign</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- LEFT -->
                        <div class="space-y-4 col-span-1 md:col-span-1">
                            <div class="space-y-4">
                                <!-- ACCOUNTABLE/ISSUED/CREATED DROPDOWN -->
                                <div class="flex flex-col md:flex-row gap-4">
                                    <div v-for="accf in accountableField" :key="accf.name" class="flex flex-col">
                                        <label class="block text-sm font-bold mb-1">{{ accf.label }}</label>
                                        <select v-model="form[accf.model]" :key="accf.model"
                                            class="w-full sm:w-[15.5rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                            <option value="">Select</option>
                                            <option v-for="item in props[accf.name]" :key="item.id"
                                                :value="item[accf.value]">
                                                {{ item[accf.option] || 'N/A' }}
                                            </option>
                                        </select>
                                        <div v-if="form.errors[accf.model]" class="text-red-500 text-sm">{{
                                            form.errors[accf.model] }}</div>
                                    </div>
                                </div>

                                <!-- STATUS/DATE ACQUIRED FIELDS  -->
                                <div class="flex md:flex-row gap-4 mb-8">
                                    <!-- <div v-for="sdf in secondDropdown" :key="sdf.label" class="flex gap-3">
                                        <div>
                                            <label class="block text-sm font-bold mb-1">{{ sdf.label }}</label>
                                            <select
                                                class="w-full sm:w-[10rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                                <option value="">Select</option>
                                                <option v-for="op in sdf.options" :key="op.value" :value="op.value">
                                                    {{
                                                        op.label }}</option>
                                            </select> -->
                                    <!-- <div v-if="form.errors[sdf.model]" class="text-red-500 text-sm">{{
                                                    form.errors[sdf.model] }}</div> -->
                                    <!-- </div>
                                    </div> -->
                                    <label class="block text-md font-semibold mb-1">Par Date</label>
                                    <input v-model="form.par_date"
                                        class="rounded-md w-full sm:w-[10rem] border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038']"
                                        type="date" />
                                    <div v-if="form.errors.par_date" class="text-red-500 text-sm">
                                        {{ form.errors.par_date }}
                                    </div>
                                </div>

                                <!-- ROOMS -->
                                <!-- <div v-for="ip in inputFields" :key="ip.model" class="flex flex-col">
                                    <label class="block text-sm font-bold mb-1">{{ ip.label }}</label>
                                    <input :placeholder="ip.placeholder"
                                        class="w-full sm:w-[32rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" /> -->
                                <!-- <div v-if="form.errors[ip.model]" class="text-red-500 text-sm">{{
                                            form.errors[ip.model] }}</div> -->
                                <!-- </div> -->

                                <!-- REMARKS -->
                                <div>
                                    <label class="block text-md font-semibold mb-1">Remarks</label>
                                    <textarea v-model="form.remarks" placeholder="Input a remarks"
                                        class="w-full sm:w-[32rem] h-32 rounded-md border border-gray-300 px-3 py-2 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]"></textarea>
                                    <div v-if="form.errors.remarks" class="text-red-500 text-sm">{{
                                        form.errors.remarks }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- RIGHT -->
                        <div class="space-y-4">
                            <!-- ITEM SELECTED -->
                            <div v-for="select in itemSelectedField" :key="select.model">
                                <label class="block text-sm font-bold mb-1">{{ select.label }}</label>
                                <div
                                    class="bg-[#F8F8F8] border border-gray-300 rounded-md h-[24.6rem] p-3 overflow-y-auto">
                                    <!-- If nothing selected -->
                                    <p v-if="selectedIDs.length === 0" class="text-gray-500 text-sm">
                                        No items selected
                                    </p>
                                    <!-- Show list of selected IDs -->
                                    <ul v-else class="space-y-2">
                                        <li v-for="id in selectedIDs" :key="id"
                                            class="bg-white border p-2 rounded-md shadow-sm text-sm">
                                            <p><strong>Item Name:</strong> {{items.data.find(i => i.id ==
                                                id)?.item_name}}</p>
                                            <p><strong>Property Number:</strong> {{items.data.find(i => i.id ==
                                                id)?.property_number}}</p>
                                            <p><strong>Quantity:</strong> {{items.data.find(i => i.id ==
                                                id)?.quantity}}</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end items-center gap-4">
                        <button type="button" @click="closeModal"
                            class="border border-gray-400 px-6 py-4 rounded-full text-sm font-semibold hover:bg-gray-100">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-[#0E6021] text-white px-8 py-4 rounded-full text-sm font-semibold hover:bg-green-800">
                            Assign
                        </button>
                    </div>
                </form>
            </template>
        </AddModal>

        <!-- SEARCH INPUT -->
        <div class="relative ml-auto w-full sm:w-72 md:w-96 mt-3">
            <!-- Icon -->
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <!-- Input -->
            <input type="search" placeholder="Search item" v-model="search"
                class="w-full h-10 rounded-full pl-10 pr-4 text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto border-collapse text-left bg-white text-xs sm:text-sm">
            <thead class="bg-[#850038]">
                <tr class="text-white">
                    <th v-for="col in columns" :key="col.key"
                        class="p-2 sm:p-3 md:p-4 align-middle first:rounded-tl-lg last:rounded-tr-lg">

                        <template v-if="col.key === 'select_all'">
                            <input type="checkbox" class="w-4 h-4" :checked="allSelected" @change="toggleSelectAll" />
                        </template>

                        <template v-else>
                            {{ col.label }}
                        </template>
                    </th>
                </tr>
            </thead>


            <tbody class="text-gray-700">
                <tr v-for="item in items.data" :key="item.id" class="even:bg-gray-200">
                    <TableCell v-for="col in columns" :key="col.key">

                        <template v-if="col.key === 'select_all'">
                            <input type="checkbox" class="w-4 h-4 text-[#0E6021]"
                                :checked="selectedIDs.includes(item.id)" @click="toggleCheck(item)" />
                        </template>

                        <template v-else-if="col.key !== 'action'">
                            <span v-if="col.format" v-html="col.format(getValue(item, col.key), item)"></span>
                            <span v-else>{{ getValue(item, col.key) ?? 'N/A' }}</span>
                        </template>

                        <template v-else>
                            <div class="flex items-center gap-1"></div>
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
                {{ items.from }}-{{ items.to }} of
                {{ items.total }}
            </p>
            <div>
                <span v-for="link in items.links" :key="link.label">
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