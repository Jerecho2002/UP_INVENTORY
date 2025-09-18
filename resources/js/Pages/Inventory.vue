<script setup>
    import InventoryLayout from '@/Layouts/InventoryLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import InventoryTable from '@/Components/InventoryTable.vue';
</script>

<template>
   <Head title="UP | Inventory" />
    
        <div class="bg-[#F3F3F3]">
            <InventoryLayout />
        </div> 

</template>