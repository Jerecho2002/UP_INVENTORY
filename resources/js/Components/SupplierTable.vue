<script setup>
import { usePage, useForm, Link, router } from '@inertiajs/vue3';
import { reactive, computed, ref, watch } from 'vue';
import AddModal from './Modals/AddModal.vue';

const props = defineProps({
    supplierOption: Array,
    statusDropdown: Array,
});

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
            
            <!-- ADD FORM CONTENT -->
            <template #InventoryForm="{ closeModal }">
                <form @submit.prevent="handleAddItem(closeModal)" class="flex flex-col gap-3 sm:overflow-y-auto">
                    <!-- Header -->
                    <h2 class="text-2xl font-bold text-[#850038] mb-6">Add Supplier</h2>

                    <!-- Form Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- Left Side -->
                        <div class="space-y-4 col-span-1 md:col-span-1">
                            <!-- Input Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5">
                                <div class="space-y-4">
                                    <!-- SUPPLIER NAME / CONTACT NUMBER / EMAIL / ADDRESS / STATUS-->
                                    <div class="flex flex-col md:flex-row gap-4 mb-4">
                                        <div v-for="supp in supplierOption" :key="supp.name" class="flex flex-col">
                                            <label class="block text-sm font-bold mb-1">{{ supp.label }}</label>
                                            <input  :placeholder="supp.placeholder"
                                                class="w-full sm:w-[13.5rem] border border-gray-300 rounded-md px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                                             <!-- <div v-if="form.errors[fdp.model]" class="text-red-500 text-sm">{{
                                                form.errors[fdp.model] }}</div> -->
                                        </div>

                                        <div v-for="stats in statusDropdown" :key="stats.label">
                                            <label class="block text-sm font-bold mb-1">{{ stats.label }}</label>
                                            <select
                                                class="w-full sm:w-[12rem] border border-gray-300 rounded-md px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                                <option v-for="option in stats.options" :key="option.value" :value="option.value">
                                                    {{ option.label }}
                                                </option>
                                            </select>
                                            <!-- <div v-if="form.errors[fdp.model]" class="text-red-500 text-sm">{{
                                                form.errors[fdp.model] }}</div> -->
                                        </div>
                                    </div>
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
                            Add
                        </button>
                    </div>
                </form>
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