<script setup>

</script>

<template>
   <div class="flex flex-col lg:flex-row flex-wrap items-start lg:items-end gap-4">
         <!-- FROM -->
        <div class="flex flex-col w-full sm:w-1/2 md:w-auto">
            <label class="text-sm font-bold mb-1">From</label>
            <input type="date"
                class="h-[2.8rem] w-full sm:w-48 px-3 text-sm rounded-md text-black border cursor-pointer focus:ring-2 focus:ring-blue-400 focus:outline-none" />
        </div>

        <!-- TO -->
        <div class="flex flex-col w-full sm:w-1/2 md:w-auto">
            <label class="text-sm font-bold mb-1">To</label>
            <input type="date"
                class="h-[2.8rem] w-full sm:w-48 px-3 text-sm rounded-md text-black border cursor-pointer focus:ring-2 focus:ring-blue-400 focus:outline-none" />
        </div>

        <!--STOCK STATUS -->
        <div class="flex flex-col w-full sm:w-1/2 md:w-auto">
            <label class="text-sm font-bold mb-1">Stock Status</label>
            <select
                class="h-[2.8rem] w-full sm:w-40 px-3 text-sm rounded-md text-black border cursor-pointer focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <option value="">All</option>
                <option value="1">In Stock</option>
                <option value="0">Out of Stock</option>
                <option value="2">Low Stock</option>
            </select>
        </div>
   </div>
</template>