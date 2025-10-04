<script setup>

</script>

<template>

    <div>
        <!-- Filter Form -->
        <div
            class="w-full bg-white p-4 rounded-lg shadow-md flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <!-- FROM -->
            <div class="flex flex-col w-full sm:w-auto">
                <label class="text-sm font-bold mb-1">From</label>
                <input type="date"
                    class="h-[2.5rem] sm:h-[3rem] w-full sm:w-48 px-3 text-xs rounded-md text-[#000000] cursor-pointer border focus:ring-2 focus:ring-blue-400 focus:outline-none" />
            </div>

            <!-- TO -->
            <div class="flex flex-col w-full sm:w-auto">
                <label class="text-sm font-bold mb-1">To</label>
                <input type="date"
                    class="h-[2.5rem] sm:h-[3rem] w-full sm:w-48 px-3 text-xs rounded-md text-[#000000] cursor-pointer border focus:ring-2 focus:ring-blue-400 focus:outline-none" />
            </div>

            <!-- CATEGORY -->
            <div class="flex flex-col w-full sm:w-auto">
                <label class="text-sm font-bold mb-1">Category</label>
                <select
                    class="h-[2.5rem] sm:h-[3rem] w-full sm:w-40 px-3 text-xs cursor-pointer rounded-md text-[#000000] border focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    <option value="">Select</option>
                    <option value="0-50000">In Stock</option>
                    <option value="50000-100000">Out of Stock</option>
                </select>
            </div>
            <!-- SEARCH -->
            <div class="flex flex-col w-full sm:w-auto top-2 relative ml-auto">
                <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="search" placeholder="Search stock item" v-model="search"
                    class="h-[2.5rem] sm:h-[3rem] w-full sm:w-96 pl-10 pr-3 text-sm rounded-full text-[#000000] border focus:ring-2 focus:ring-blue-400 focus:outline-none" />
            </div>
        </div>
    </div>

</template> 