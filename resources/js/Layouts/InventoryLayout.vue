<script setup>
import NavHeader from '@/Components/NavHeader.vue';
import SideBar from '@/Components/SideBar.vue';

const menuItems = [
  { name: "Dashboard", icon: "fa-solid fa-table-cells-large", route: "/dashboard" },
  { name: "Inventory", icon: "fa-solid fa-boxes-packing", route: "/inventory" },
  { name: "Reports", icon: "fa-solid fa-file-export", route: "/reports" },
  { name: "Suppliers", icon: "fa-solid fa-handshake", route: "/suppliers" },
  { name: "Purchase", icon: "fa-solid fa-box-open", route: "/purchase" },
  { name: "Item Disposal", icon: "fa-solid fa-recycle", route: "/item-disposal" },
];
</script>

<template>
  <div>
    <NavHeader />
    <!-- kebab-case when passing props in template -->
    <SideBar :menu-items="menuItems" />
  </div>
            <div class="flex">
            <SideBar />
            <InventoryTable />
            </div>
</template>

