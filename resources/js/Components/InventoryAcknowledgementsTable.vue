<script setup>
import { usePage, useForm, Link, router } from '@inertiajs/vue3';
import { reactive, computed, ref, watch } from 'vue';
import AddModal from './Modals/AddModal.vue';

const props = defineProps({
    accountableField: Array,
});

const addModalRef = ref(null);
</script>

<template>

    <div class="flex flex-col sm:flex-row items-end mb-4 mt-[3rem]">
        <AddModal ref="addModalRef">
            <template #AddAssignButton>
                <button @click="addModalRef.openModal()"
                    class="flex items-center gap-2 bg-[#0E6021] hover:bg-[#2a9754] text-white font-semibold px-4 py-2 rounded-md text-xs sm:text-sm transition duration-150 w-full sm:w-auto">
                    <i class="fa-solid fa-plus"></i>
                    <span>Assign</span>
                </button>
            </template>

            <template #AcknowledgementForm="{ closeModal }">
                <form @submit.prevent="handleAddItem(closeModal)" class="flex flex-col gap-3 sm:overflow-y-auto">
                    <h2 class="text-2xl font-bold text-[#850038] mb-6">Assign</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <!-- LEFT -->
                        <div class="space-y-4 col-span-1 md:col-span-1">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5">
                                <div class="space-y-4">
                                    <!-- FIRST DROPDOWN -->
                                    <div class="flex flex-col md:flex-row gap-4 mb-4">
                                        <div v-for="acF in props.accountableField" :key="acF.name" class="mb-4">
                                            <label>{{ acF.label }}</label>
                                            <select
                                                class="w-full sm:w-[10rem] rounded-md border border-gray-300 px-3 py-3 bg-[#F8F8F8] text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                                                <option value="">Select</option>
                                                <option v-for="option in acF.options" :key="option.id"
                                                    :value="option.id">
                                                    {{ option[acF.option] }}
                                                </option>
                                            </select>
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
                            Assign
                        </button>
                    </div>
                </form>
            </template>
        </AddModal>

        <!-- SEARCH INPUT -->
        <div class="relative ml-auto w-full sm:w-72 md:w-96">
            <!-- Icon -->
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <!-- Input -->
            <input type="search" placeholder="Search item" v-model="search"
                class="w-full h-10 rounded-full pl-10 pr-4 text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
        </div>

    </div>


</template>