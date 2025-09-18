<script setup>
    import { Link } from '@inertiajs/vue3';

</script>

<template>  

    <div>
        <div class="bg-white w-[15rem] h-[42.3rem] shadow-xl">
            <div>
                <ul class="py-[3rem] mx-7 text-xl text-semibold space-y-7">
                    <li class="text-[#3A3434]"><Link><i class="fa-solid fa-table-cells-large"></i> Dashboard</Link></li>
                    <li class="-mx-1 text-[#3A3434]"><Link><i class="fa-solid fa-boxes-packing"></i> Inventory</Link></li>
                    <li class="text-[#3A3434] mx-1"><Link><i class="fa-solid fa-clipboard-user"></i> Reports</Link></li>
                    <li class="text-[#3A3434] -mx-1"><Link><i class="fa-solid fa-handshake"></i> Suppliers</Link></li>
                    <li class="text-[#3A3434] -mx-1"><Link><i class="fa-solid fa-box-open"></i> Purchase</Link></li>
                    <li class="text-[#3A3434]"><Link><i class="fa-solid fa-recycle"></i> Item Disposal</Link></li>  
                </ul>
            </div>

            <div class="mx-10 text-[#8C8C8C] text-xs">
                <span>EasyLearning © UP Cebu.</span>
            </div>
        </div>
    </div>

</template>