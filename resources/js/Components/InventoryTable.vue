<script setup>
import { usePage, Link, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { debounce } from 'lodash';

const props = defineProps({
    searchItem: String,
});

const search = ref(props.searchItem || '');
watch(
    search,
    debounce((output) => {
        router.get('/', { search: output }, { preserveState: true });
    }, 300)
);

function deleteItem(id) {
    if (confirm("Are you sure you want to delete this item?")) {
        router.delete(`/items/${id}`, {
            preserveScroll: true,
        })
    }
}

const page = usePage();
const items = computed(() => page.props.items);
</script>

<template>

    <div class="flex-col mx-[1rem] w-full">
        <div class="flex justify-between my-3">
            <h1 class="font-bold text-[23px] text-[#1b1b1b]">INVENTORY</h1>
            <p class="text-[#8F8686] font-semibold text-sm">UPCEBU<span><i
                        class="fa-solid fa-chevron-right mx-3 text-sm"></i></span>Inventory</p>
        </div>

        <!-- ADDING ITEM/FILTER/SEARCHING -->
        <div class="flex justify-between mb-3 mt-[5rem]">
            <button class="flex items-center gap-2 bg-[#3FA165] rounded-md text-white px-3 text-xs hover:bg-[#2a9754]">
                <i class="fa-solid fa-plus"></i>
                <span class="font-bold">Add Item</span>
            </button>

            <div class="flex">
                <div class="flex-col    ">
                    <p for="" class="text-xs ml-[3.1rem] font-bold">Unit Cost</p>
                    <select name="" id="" class="mx-[3rem] h-[2rem] w-[6rem] text-xs rounded-md text-[#797979]">
                        <option value="">Select</option>
                        <option value="">₱50,000 - ₱0</option>
                        <option value="">₱50,000 - ₱100,000</option>
                    </select>
                </div>

                <div>
                    <input type="search" placeholder="Search" v-model="search" class="w-[27rem] h-[3rem] rounded-full">
                </div>
            </div>
        </div>

        <!-- INVENTORY TABLE -->

        <!-- Responsive container (enables horizontal scroll on small screens) -->
        
            <table class="w-full overflow-x-auto table-auto border-collapse text-left bg-white">
                <thead class="bg-[#850038] rounded-t-lg">
                    <tr class="text-white text-xs md:text-sm">
                        <th class="rounded-tl-lg p-3 md:p-4 align-middle">Par Number</th>
                        <th class="p-3 md:p-4 align-middle">Property Number</th>
                        <th class="p-3 md:p-4 align-middle">Item Name</th>
                        <th class="p-3 md:p-4 align-middle">Unit</th>
                        <th class="p-3 md:p-4 align-middle">Unit Cost</th>
                        <th class="p-3 md:p-4 align-middle">Quantity</th>
                        <th class="rounded-tr-lg p-3 md:p-4 align-middle">Action</th>
                        <!-- <th class="p-3 md:p-4 align-middle">status</th> -->
                    </tr>
                </thead>

                <tbody class="text-xs md:text-sm text-[#3F3F3F]">
                    <tr v-for="item in items.data" :key="item.id" class="even:bg-[#DDDDDD]">
                        <td :class="{
                            'text-red-500': item.status === 0,
                            'text-green-500': item.status === 1,
                            'text-yellow-500': item.status === 2
                        }" class="p-3 md:p-4 align-middle break-words">
                            {{ item.property_ack_receipt.par_number }}</td>

                        <td class="p-3 md:p-4 align-middle break-words">{{ item.asset.property_no }}</td>
                        <td class="p-3 md:p-4 align-middle break-words">{{ item.item_name }}</td>
                        <td class="p-3 md:p-4 align-middle">{{ item.unit ?? 'N/A' }}</td>
                        <td class="p-3 md:p-4 align-middle">{{ item.unit_cost ? `₱${item.unit_cost}` : 'N/A' }}</td>
                        <td class="p-3 md:p-4 align-middle whitespace-nowrap">{{ `${item.quantity} pcs.` }}</td>
                        <td class="p-3 md:p-4 align-middle">
                            <button @click="deleteItem(item.id)" class="text-red-600 hover:underline">
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>


        <!-- PAGINATION -->
        <div>
            <span v-for="link in items.links" :key="link.label">
                <Link v-if="link.url" :href="link.url" v-html="link.label" class="p-1 mx-1"
                    :class="{ 'text-gray-600 hover:underline': link.url, 'text-blue-600 font-bold': link.active }">
                </Link>
                <span v-else v-html="link.label" :class="{ 'text-gray-300': !link.url }"></span>
            </span>
            <div>
                <p>Showing from {{ items.from }} to {{ items.to }} total of {{ items.total }}</p>
            </div>
        </div>
    </div>
</template>