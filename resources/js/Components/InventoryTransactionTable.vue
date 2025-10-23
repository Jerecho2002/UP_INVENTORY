<script setup>

</script>

<template>
    <div class="p-2">
        <div>
            <h1 class="text-[#850038] font-bold text-xl">Transaction History</h1>
        </div>

        <!-- FILTER DATE/COST RANGE/STATUS + SEARCH -->
        <div class="flex flex-col sm:flex-row justify-between">
            <!-- LEFT FILTERS -->
            <div class="flex flex-col md:flex-row gap-8 my-8">
                <div>
                    <input type="date"
                        class="h-[2.5rem] sm:h-[3rem] w-full sm:w-56 rounded-md cursor-pointer focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]" />
                </div>

                <div>
                    <select
                        class="h-[2.5rem] sm:h-[3rem] w-full sm:w-48 rounded-md cursor-pointer focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                        <option value="" disabled selected>Cost Range</option>
                        <option value="">₱0 - ₱25,000</option>
                        <option value="">₱25,000 - ₱50,000</option>
                        <option value="">₱50,000 - ₱75,000</option>
                        <option value="">₱75,000 - ₱100,000</option>
                    </select>
                </div>

                <div>
                    <select
                        class="h-[2.5rem] sm:h-[3rem] w-full sm:w-48 rounded-md cursor-pointer focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038]">
                        <option value="" disabled selected>Status</option>
                        <option value="">Active</option>
                        <option value="">Inactive</option>
                    </select>
                </div>
            </div>

            <!-- RIGHT SEARCH + EXPORT -->
            <div class="w-full sm:w-auto flex flex-col sm:flex-row items-center gap-3">
                 <!-- Export button -->
                <button
                    class="flex items-center justify-center gap-2 bg-[#20C6D2] text-white font-semibold rounded-md h-[2.5rem] sm:h-[3rem] px-5 hover:bg-[#49dce6] transition duration-150">
                    <i class="fa-solid fa-file-export"></i>
                    <span>Export</span>
                </button>

                <!-- Search bar -->
                <div class="relative w-full sm:w-96">
                    <input type="text" placeholder="Search..."
                        class="w-full h-[2.5rem] sm:h-[3rem] rounded-full pl-10 pr-4 border border-gray-300 focus:ring-1 focus:ring-[#850038] focus:outline-none focus:border-[#850038] text-sm bg-[#F8F8F8]" />
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                </div>
            </div>
        </div>
    </div>


</template>