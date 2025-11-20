<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { watch, ref } from 'vue';

const props = defineProps({
    mode: { type: String, default: 'create' },
    initialValues: { type: Object, default: () => ({}) },
    inputFields: { type: Array, default: () => [] },
    firstDropdown: { type: Array, default: () => [] },
    secondDropdown: { type: Array, default: () => [] },
    invoicesFundFields: { type: Array, default: () => [] },
    quantityCostFields: { type: Array, default: () => [] },
    requestFields: { type: Array, default: () => [] },
    itemClass: { type: Array, default: () => [] },
    suppliers: { type: Array, default: () => [] },
});

const page = usePage();

const emit = defineEmits(['submit', 'close']);


const form = useForm({
    item_classification_id: "",
    supplier_id: "",
    invoice: "",
    fund_source: "",
    item_name: "",
    description: "",
    category: "",
    quantity: "",
    unit: "",
    unit_cost: "",
    total_amount: "",
    property_number: "",
    serial_numbers: [], // FOR ADD ITEM
    serial_number: "", // FOR EDIT ITEM
    pr_number: "",
    po_number: "",
    remarks: "",
    date_acquired: "",
    status: "",
});

const isEditing = ref(false);

const calculateTotalAmount = () => {
    const qty = Number(form.quantity) || 0;
    const cost = Number(form.unit_cost) || 0;

    const total = qty * cost;

    form.total_amount = total.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
};

watch(
    () => [form.quantity, form.unit_cost],
    calculateTotalAmount
);

watch(() => form.item_classification_id, (newVal) => {
    if (isEditing.value || !newVal) return;

    // Find the selected classification object
    const selectedClass = props.itemClass.find(c => c.id === newVal);

    if (selectedClass) {
        const code = selectedClass.classification_code;

        // Get current year and month (with leading zero for month)
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, "0");

        // Update the input fields (PAR/ICS and Property Number)
        if (code) {
            const formatted = `${code}-${year}-${month}-`;
            form.category = formatted;
            form.property_number = `${code}-`;
        } else {
            form.category = "";
            form.property_number = "";
        }
    }
});



watch(() => form.quantity, (newVal) => {
    const qty = parseInt(newVal);

    if (!qty || qty <= 0) {
        form.serial_numbers = [];
        return;
    }

    // Adjust number of fields based on quantity
    form.serial_numbers = Array.from({ length: qty }, (_, i) => form.serial_numbers[i] || "");
});

function submit() {
    if (typeof form.serial_numbers === 'string' && form.serial_numbers.length) {
        form.serial_numbers = form.serial_numbers.split(',').map(s => s.trim());
    }
    form.post(route('items.store'), {
        onSuccess: () => {
            emit('close');
            form.reset();
            emit('created');
        },
    });
}

</script>


<template>
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg w-full max-w-6xl p-4 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-[#850038] mb-6">
                    {{ mode === 'edit' ? 'Edit Item' : mode === 'view' ? 'View Item' : 'Add Item' }}
                </h3>
            </div>

            <form @submit.prevent="submit" v-if="mode !== 'view'">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- LEFT -->
                    <div class="space-y-4 col-span-1 md:col-span-1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5">
                            <div class="space-y-4">

                                <!-- FIRST DOWN -->
                                <div class="flex flex-col md:flex-row gap-4 mb-4">
                                    <div v-for="fdp in firstDropdown" :key="fdp.name">
                                        <label class="block text-sm font-bold mb-1">{{ fdp.label }}</label>
                                        <select v-model="form[fdp.model]" :key="fdp.model"
                                            class="w-full sm:w-[15.5rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                            <option value="">Select</option>
                                            <option v-for="item in props[fdp.name]" :key="item.id"
                                                :value="item[fdp.value]">
                                                {{ item[fdp.option] || 'N/A' }}
                                            </option>
                                        </select>
                                        <div v-if="form.errors[fdp.model]" class="text-red-500 text-sm">{{
                                            form.errors[fdp.model] }}</div>
                                    </div>
                                </div>

                                <!-- SECOND DROP DOWN -->
                                <div class="flex md:flex-row gap-4 mb-8">
                                    <div v-for="sdf in secondDropdown" :key="sdf.label" class="flex gap-3">
                                        <div>
                                            <label class="block text-sm font-bold mb-1">{{ sdf.label }}</label>
                                            <select v-model="form[sdf.model]"
                                                class="w-full sm:w-[10rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                                <option value="">Select</option>
                                                <option v-for="op in sdf.options" :key="op.value" :value="op.value">
                                                    {{
                                                        op.label }}</option>
                                            </select>
                                            <div v-if="form.errors[sdf.model]" class="text-red-500 text-sm">{{
                                                form.errors[sdf.model] }}</div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold mb-1">Date Acquired</label>
                                        <input v-model="form.date_acquired"
                                            class="rounded-md w-full sm:w-[10rem] border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]"
                                            type="date" />
                                    </div>
                                </div>

                                <!-- QUANTITY/UNIT COST -->
                                <div class="flex gap-4 sm:gap-8 w-full">
                                    <div v-for="qcf in quantityCostFields" :key="qcf.quantityCostFields"
                                        class="flex flex-col flex-1 min-w-[8rem] sm:min-w-[10rem] md:min-w-[15rem] lg:min-w-[14.3rem]">
                                        <label class="block text-sm font-bold mb-1">{{ qcf.label }}</label>
                                        <input v-model="form[qcf.model]" :key="qcf.model" :type="qcf.type"
                                            :placeholder="qcf.placeholder" step="any"
                                            class="w-full sm:w-[15.7rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm text-gray-800 focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038] transition duration-150" />
                                        <div v-if="form.errors[qcf.model]" class="text-red-500 text-sm">{{
                                            form.errors[qcf.model] }}</div>
                                    </div>
                                </div>

                                <!-- SERIAL NUMBERS -->
                                <div v-if="form.quantity > 0" class="flex flex-col gap-2 mt-4">
                                    <label class="block text-sm font-bold mb-1">Serial Numbers</label>
                                    <div v-for="(sn, index) in form.serial_numbers" :key="index">
                                        <input v-model="form.serial_numbers[index]" type="text"
                                            :placeholder="`SER-${String(index + 1).padStart(3, '0')}`"
                                            class="w-full sm:w-[32rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                    </div>
                                </div>

                                <!-- ITEM NAME -->
                                <div v-for="ip in inputFields" :key="ip.model" class="flex flex-col">
                                    <label class="block text-sm font-bold mb-1">{{ ip.label }}</label>
                                    <input v-model="form[ip.model]" :key="ip.model" type="text"
                                        :placeholder="ip.placeholder"
                                        class="w-full sm:w-[32rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                    <div v-if="form.errors[ip.model]" class="text-red-500 text-sm">{{
                                        form.errors[ip.model] }}</div>
                                </div>

                                <!-- DESCRIPTION -->
                                <div>
                                    <label class="block text-md font-semibold mb-1">Description</label>
                                    <textarea v-model="form.description" placeholder="Input a description"
                                        class="w-full sm:w-[32rem] h-32 rounded-md border border-gray-300 px-3 py-2 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]"></textarea>
                                    <div v-if="form.errors.description" class="text-red-500 text-sm">{{
                                        form.errors.description }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- RIGHT -->
                    <div class="space-y-4">
                        <!-- Invoices + Fund Sources -->
                        <div class="flex flex-col md:flex-row gap-4 mb-4">
                            <div v-for="inv in invoicesFundFields" :key="inv.model">
                                <label class="block text-sm font-bold mb-1">{{ inv.label }}</label>
                                <input v-model="form[inv.model]" :key="inv.model" type="text"
                                    :placeholder="inv.placeholder"
                                    class="w-full sm:w-[16.5rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                <div v-if="form.errors[inv.model]" class="text-red-500 text-sm">{{
                                    form.errors[inv.model] }}</div>
                            </div>
                        </div>
                        <!-- REQUEST FIELDS -->
                        <div class="space-y-4">
                            <div v-for="rf in requestFields" :key="rf.model" class="flex flex-col">
                                <label class="block text-sm font-bold mb-1 ">{{ rf.label }}</label>
                                <input v-model="form[rf.model]" :key="rf.model" type="text"
                                    :placeholder="rf.placeholder"
                                    class="w-full sm:w-[34rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                <div v-if="form.errors[rf.model]" class="text-red-500 text-sm">{{
                                    form.errors[rf.model] }}</div>
                            </div>
                        </div>

                        <!-- TOTAL AMOUNT -->
                        <div
                            class="flex flex-col md:flex-row sm:items-center md:items-center text-sm font-semibold mt-8">
                            <label class="block text-base font-bold">Total Amount: ₱</label>
                            <input v-model="form.total_amount" readonly placeholder="0.00"
                                class="block text-lg font-semibold text-gray-700 border border-none pointer-events-none" />
                        </div>
                    </div>
                </div>

                <!-- BUTTON -->
                <div class="mt-4 flex justify-end gap-4">
                    <button type="button" @click="$emit('close')"
                        class="border border-gray-400 px-6 py-4 rounded-full text-sm font-semibold hover:bg-gray-100">Cancel</button>
                    <button type="submit"
                        class="bg-[#0E6021] text-white px-8 py-4 rounded-full text-sm font-semibold hover:bg-green-800">Add</button>
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
