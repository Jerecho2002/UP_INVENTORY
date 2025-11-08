<script setup>
import { usePage, useForm, Link, router } from '@inertiajs/vue3';
import { reactive, computed, ref, watch } from 'vue';
import AddModal from './Modals/AddModal.vue';

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
        </AddModal>

        <!-- SEARCH INPUT -->
        <div class="relative ml-auto w-full sm:w-72 md:w-96">
            <!-- Icon -->
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>

            <!-- Input -->
            <input type="search" placeholder="Search item" v-model="search"
                class="w-full h-10 rounded-full pl-10 pr-4 border border-gray-300 text-sm focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
        </div>

    </div>


</template>