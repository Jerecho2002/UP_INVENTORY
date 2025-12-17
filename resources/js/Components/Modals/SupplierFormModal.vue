<script setup>
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    mode: { type: String, default: 'create' },
    supplierFields: { type: Array, default: () => [] },
    supplier: { type: Object, default: () => ({}) }
})

const emit = defineEmits(['submit', 'close'])

const form = useForm({
    id: null,
    supplier_name: '',
    contact_no: '',
    email: '',
    address: '',
})

// populate form when editing
watch(
    () => props.supplier,
    (val) => {
        if (val) Object.assign(form, val)
    },
    { immediate: true }
)

// animation
const isClosing = ref(false)

function closeWithAnimation() {
    isClosing.value = true
    setTimeout(() => {
        emit('close')
        isClosing.value = false
    }, 200)
}

function submitForm() {
    emit('submit', form)
}
</script>

<template>
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
        <div :class="[
            'bg-white rounded-lg w-full max-w-lg p-6 overflow-y-auto max-h-[90vh]',
            isClosing ? 'animate-pop-out' : 'animate-pop-in'
        ]" @click.stop>
            <h3 class="text-2xl font-bold text-[#850038] mb-6">
                {{ mode === 'edit' ? 'Edit Supplier' : 'Add Supplier' }}
            </h3>

            <!-- FORM -->
            <form @submit.prevent="submitForm">
                <div class="space-y-4">
                    <div v-for="supF in supplierFields" :key="supF.model">
                        <label class="block text-sm font-bold mb-1">
                            {{ supF.label }}
                        </label>

                        <input v-model="form[supF.model]" :type="supF.type || 'text'" :placeholder="supF.placeholder"
                            class="w-full rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm
                     focus:ring-1 focus:ring-[#850038] focus:outline-none" />
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="closeWithAnimation"
                        class="border border-gray-400 px-6 py-3 rounded-full text-sm font-semibold hover:bg-gray-100">
                        Cancel
                    </button>

                    <button type="submit"
                        class="bg-[#0E6021] text-white px-8 py-3 rounded-full text-sm font-semibold hover:bg-green-800">
                        {{ mode === 'edit' ? 'Update' : 'Add' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
