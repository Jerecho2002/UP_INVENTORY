<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({ item: Object });
const emit = defineEmits(['close', 'confirm']);

async function doDelete() {
    if (!props.item || !props.item.id) return;
    try {
        await router.delete(`/items/${props.item.id}`, {
            preserveState: false,
            onSuccess: () => emit('confirm'),
            onError: () => emit('close')
        });
    } catch (e) {
        console.error(e);
    }
}
</script>

<template>
    <div class="fixed inset-0 bg-black/40 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full flex flex-col items-center text-center">
            <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4"
                style="background-color: #F5CCCC;">
                <i class="fa-solid fa-triangle-exclamation text-red-600 text-3xl"></i>
            </div>
            <h4 class="font-semibold mb-2 text-xl">Confirm Delete</h4>
            <p class="text-lg font-bold mb-4 text-[#888484]">
                Are you sure you want to delete
                <strong class="text-black">{{ item?.item_name }}</strong>?
            </p>
            <div class="flex justify-center gap-3 mt-2">
                <button @click="$emit('close')" class="px-4 py-2 bg-[#D9D9D9] rounded">
                    Cancel
                </button>

                <button @click="doDelete" class="px-4 py-2 bg-red-600 text-white rounded gap-2 flex items-center justify-center">
                    <i class="fa-solid fa-trash"></i>
                    Delete
                </button>
            </div>
        </div>
    </div>
</template>