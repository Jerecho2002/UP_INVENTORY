<script setup>

</script>

<template>

    <div>
        <div class="border-b-4 border-gray-100 p-4">
            <h1 class="text-[#850038] font-bold text-lg">Disposal History</h1>
        </div>


        <!-- RIGHT SEARCH + EXPORT -->
        <div class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-3 justify-end p-4">
            <!-- Search bar -->
            <div class="relative w-full sm:w-96">
                <input type="text" placeholder="Search..." v-model="search"
                    class="w-full h-[2.5rem] sm:h-[3rem] rounded-full pl-10 pr-4 border border-gray-300 focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038] text-sm bg-[#F8F8F8]" />
                <i
                    class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
            </div>
        </div>
    </div>

</template>