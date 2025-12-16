<script setup>

const props = defineProps({
    mode: { type: String, default: 'create' },
    supplierFields: { type: Array, default: () => [] },
});

</script>

<template>
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
        <div :class="['bg-white rounded-lg w-full max-w-2xl p-4 overflow-y-auto max-h-[90vh]',
            isClosing ? 'animate-pop-out' : 'animate-pop-in'
        ]">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-[#850038] mb-6">
                    {{ mode === 'edit' ? 'Edit Supplier' : mode === 'view' ? 'Supplier Details' : 'Add Supplier' }}
                </h3>
            </div>

            <form v-if="mode !== 'view'">
                <div class="flex flex-col gap-2">
                    <div class="space-y-4">
                        <div v-for="supF in supplierFields" :key="supF.model">
                            <label class="block text-sm font-bold mb-1">{{ supF.label }}</label>
                             <input :type="fif.type || 'text'"
                                            :placeholder="fif.placeholder" class="w-full sm:w-[21rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm
                   focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</template>