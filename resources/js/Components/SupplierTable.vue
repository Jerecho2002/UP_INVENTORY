<script setup>
import { usePage, useForm, Link, router } from '@inertiajs/vue3';
import { reactive, computed, ref, watch } from 'vue';
import AddModal from './Modals/AddModal.vue';

const addModalRef = ref(null);
</script>

<template>
    <div class="flex flex-col sm:flex-row items-end mb-4 mt-[3rem]">
        <AddModal ref="addModalRef">
            <template #AddSupplierButton>
                <button @click="addModalRef.openModal()"
                    class="flex gap-2 bg-[#0E6021] rounded-md text-white px-4 py-1 text-xs sm:text-sm hover:bg-[#2a9754] w-full sm:w-auto items-center justify-center">
                    <i class="fa-solid fa-plus my-2"></i>
                    <span class="font-bold">Add Supplier</span>
                </button>
            </template>
        </AddModal>


        <div class="relative ml-auto w-full sm:w-72 md:w-96">
            <div class="w-full sm:w-auto mt-3 relative">
                <!-- Search Icon -->
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>

                <!-- Search Input -->
                <input type="search" placeholder="Search item" v-model="search"
                    class="w-full sm:w-64 md:w-96 h-9 sm:h-10 rounded-full pl-10 pr-3 border text-sm focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
            </div>
        </div>

    </div>
</template>