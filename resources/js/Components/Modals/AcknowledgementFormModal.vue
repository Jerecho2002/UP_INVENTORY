<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed } from "vue";

const props = defineProps({
    mode: { type: String, default: 'create' },
    accountableField: { type: Array, default: () => [] },
    inputFields: { type: Array, default: () => [] },
    itemSelectedField: { type: Array, default: () => [] },
    selectedIDs: { type: Array, default: () => [] },
    items: { type: Object, default: () => ({ data: [] }) },
    users: { type: Array, default: () => [] },
});

const emit = defineEmits(['submit', 'close', 'created']);
``
const form = useForm({
    inventory_item_id: [],
    accountable_persons_id: "",
    issued_by_id: "",
    created_by: "",
    par_date: "",
    remarks: "",
});

const itemMap = computed(() => {
    const map = {};
    props.items.data?.forEach(item => {
        map[item.id] = item;
    });
    return map;
});

function submit() {
    form.inventory_item_id = props.selectedIDs;
    form.created_by = props.users[0]?.id ?? null;

    if (props.mode === "edit") {
        if (!form.id) {
            console.error('Edit mode but form.id is missing', form);
            return;
        }

        form.put(route('items.update', form.id), {
            onSuccess: () => {
                emit('close');
                emit('submit', form);
            },
            onError: (errors) => {
                console.error('Update failed', errors);
            },
        });
    } else {
        form.post(route('inventory.acknowledgements.store'), {
            onSuccess: () => {
                emit('close');
                emit('created');
                form.reset();   
            },
            onError: (errors) => {
                console.error('Create failed', errors);
            },
        });
    }
}

</script>

<template>
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg w-full max-w-6xl p-4 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-[#850038] mb-6">
                    {{ mode === 'edit' ? 'Edit Item' : mode === 'view' ? 'Item Details' : 'Assign' }}
                </h3>
            </div>

            <form @submit.prevent="submit" v-if="mode !== 'view'">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- LEFT -->
                    <div class="space-y-4 col-span-1 md:col-span-1">
                        <div class="space-y-4">
                            <!-- ACCOUNTABLE/ISSUED/CREATED DROPDOWN -->
                            <div class="flex flex-col md:flex-row gap-4">
                                <div v-for="accf in accountableField" :key="accf.name" class="flex flex-col">
                                    <label class="block text-sm font-bold mb-1">{{ accf.label }}</label>
                                    <select v-model="form[accf.model]" :key="accf.model"
                                        class="w-full sm:w-[11rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                        <option value="">Select</option>
                                        <option v-for="item in props[accf.name]" :key="item.id"
                                            :value="item[accf.value]">
                                            {{ item[accf.option] || 'N/A' }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors[accf.model]" class="text-red-500 text-sm">{{
                                        form.errors[accf.model] }}</div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold mb-1">Date Acquired</label>
                                    <input v-model="form.par_date"
                                        class="rounded-md w-full  border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038']"
                                        type="date" />
                                    <div v-if="form.errors.par_date" class="text-red-500 text-sm">
                                        {{ form.errors.par_date }}
                                    </div>
                                </div>
                            </div>

                            <!-- STATUS/DATE ACQUIRED FIELDS  -->
                            <div class="flex flex-col md:flex-row gap-4 mb-8">
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
                            </div>

                            <!-- ROOMS -->
                            <div v-for="ip in inputFields" :key="ip.model" class="flex flex-col">
                                <label class="block text-sm font-bold mb-1">{{ ip.label }}</label>
                                <input :placeholder="ip.placeholder"
                                    class="w-full sm:w-[33.4rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                <div v-if="form.errors[ip.model]" class="text-red-500 text-sm">{{
                                    form.errors[ip.model] }}</div>
                            </div>

                            <!-- REMARKS -->
                            <div>
                                <label class="block text-md font-semibold mb-1">Remarks</label>
                                <textarea v-model="form.remarks" placeholder="Input a remarks"
                                    class="w-full sm:w-[33.4rem] h-48 rounded-md border border-gray-300 px-3 py-2 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]"></textarea>
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
                            <div class="bg-[#F8F8F8] border border-gray-300 rounded-md h-[24.1rem] p-3 overflow-y-auto">
                                <!-- If nothing selected -->
                                <p v-if="selectedIDs.length === 0" class="text-gray-500 text-sm">
                                    No items selected
                                </p>
                                <!-- Show list of selected IDs -->
                                <ul v-else class="space-y-2">
                                    <li v-for="id in selectedIDs" :key="id"
                                        class="bg-white border p-2 rounded-md shadow-sm text-sm">
                                        <p><strong class="text-[#850038]">Item Name:</strong> {{ itemMap[id]?.item_name }}</p>
                                        <p><strong class="text-[#850038]">Property Number:</strong> {{ itemMap[id]?.property_number }}</p>
                                        <p><strong class="text-[#850038]">Quantity:</strong> {{ itemMap[id]?.quantity }}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="mt-4 flex justify-end gap-4">
                    <button type="button" @click="$emit('close')"
                        class="border border-gray-400 px-6 py-4 rounded-full text-sm font-semibold hover:bg-gray-100">Cancel</button>
                    <button type="submit"
                        class="bg-[#0E6021] text-white px-8 py-4 rounded-full text-sm font-semibold hover:bg-green-800">
                        {{ mode === 'edit' ? "Update" : "Assign" }}</button>
                </div>
            </form>

            <!-- VIEW MODAL -->
            <div v-else>
                <div class="grid grid-cols-2 gap-3">
                    <div v-for="(v, k) in local" :key="k" class="p-2 border rounded">
                        <div class="text-xs text-gray-500">{{ k }}</div>
                        <div class="font-medium">{{ v }}</div>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button @click="$emit('close')" class="px-3 py-1 border rounded">Close</button>
                </div>
            </div>

        </div>
    </div>
</template>