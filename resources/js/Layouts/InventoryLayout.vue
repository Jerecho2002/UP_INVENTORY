<script setup>
    import NavHeader from '@/Components/NavHeader.vue';
    import SideBar from '@/Components/SideBar.vue';
    import InventoryTable from '@/Components/InventoryTable.vue';
</script>

<template>
            <NavHeader/>
            <div class="flex">
            <SideBar />
            <InventoryTable />
            </div>
</template>