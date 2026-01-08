<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    mode: { type: String, default: 'create' },
    accountableField: { type: Array, default: () => [] },
    inputFields: { type: Array, default: () => [] },
    itemSelectedField: { type: Array, default: () => [] },
    selectedIDs: { type: Array, default: () => [] },
    viewItem: { type: Array, default: () => [] },
    item: { type: Object, default: () => ({}) }
});

const emit = defineEmits(['close']);

const form = useForm({
    inventory_item_id: [],
    accountable_persons_id: "",
    issued_by_id: "",
    created_by: "",
    par_date: "",
    remarks: "",
});

const isClosing = ref(false);

// ANIMATION FOR CLOSE
function closeWithAnimation() {
    isClosing.value = true;
    setTimeout(() => {
        emit('close');
        isClosing.value = false;
    }, 200);
}

function getNestedValue(obj, path) {
  if (!obj || !path) return null;
  return path.split('.').reduce((o, k) => (o ? o[k] : null), obj);
}

function getViewValue(view) {
    const rawValue = getNestedValue(props.item, view.key);

    if (view.format) {
        return view.format(rawValue);
    }
    return rawValue ?? 'N/A';
}
</script>

<template>
     <div class="fixed inset-0 bg-black/40 flex items-center justify-center p-4" @click="$emit('close')" >
        <div :class="['bg-white rounded-lg w-full max-w-6xl p-4 overflow-y-auto max-h-[90vh]',
            isClosing ? 'animate-pop-out' : 'animate-pop-in'
        ]" @click.stop>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-[#850038] mb-6">
                    {{ mode === 'edit' ? 'Update' : mode === 'view' ? 'Assigned Details' : 'Re-Assign' }}
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
                                    class="w-full sm:w-[33.4rem] h-52 rounded-md border border-gray-300 px-3 py-2 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]"></textarea>
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
                        {{ mode === 'edit' ? "Update" : "Re-Assign" }}</button>
                </div>
            </form>
             <!-- VIEW MODAL -->
            <div v-else>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 border-b border-[#850038] pb-4">
                    <div v-for="view in viewItem" :key="view.key"
                        class="flex items-start justify-between gap-4 border-b pb-2">
                        <!-- LABEL -->
                        <div class="text-sm font-semibold text-[#000000] w-1/2">
                            {{ view.label }}:
                        </div>

                        <!-- VALUE -->
                        <div class="text-sm font-medium text-gray-600 w-1/2 text-right" v-html="getViewValue(view)" />
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="mt-6 flex justify-end">
                    <button @click="closeWithAnimation"
                        class="px-6 py-3 border border-[#8d8a8a] rounded-md hover:bg-gray-100 text-sm font-medium">
                        Back
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>