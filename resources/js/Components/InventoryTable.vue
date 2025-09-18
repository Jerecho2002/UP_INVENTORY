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

    <div class="flex-col mx-[2rem] w-full">
        <div class="flex justify-between my-5">
            <h1 class="font-bold text-[20px] text-[#414141]">INVENTORY</h1>
            <p class="text-[#8F8686] font-semibold text-sm">UPCEBU<span><i
                        class="fa-solid fa-chevron-right mx-3 text-sm"></i></span>Inventory</p>
        </div>
        <div class="flex justify-between mb-5">
            <button class="bg-[#3FA165] rounded-md px-5 text-white"><i class="fa-solid fa-plus"></i>Add Item</button>

            <div class="flex">
                <div class="flex-col">
                    <p for="" class="text-xs ml-12">Unit Cost</p>
                    <select name="" id="" class="mx-[3rem] h-[2rem] w-[6rem] text-xs rounded-md">
                        <option value="">Select</option>
                        <option value="">₱50,000 - ₱0</option>
                        <option value="">₱50,000 - ₱100,000</option>
                    </select>
                </div>

                <input type="search" placeholder="Search" v-model="search" class="w-[27rem] h-[3rem] rounded-full">
                </div>
        </div>
        <table>
            <thead>
                <tr class="text-center">
                    <th>Par Number</th>
                    <th>Property Number</th>
                    <th>Item Name</th>
                    <th>Unit</th>
                    <th>Unit Cost</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in items.data" :key="item.id">
                    <td class="'px-2'" :class="{
                        'text-red-500': item.status === 0,
                        'text-green-500': item.status === 1,
                        'text-yellow-500': item.status === 2
                    }">{{ item.property_ack_receipt.par_number }}</td>
                    <td class="px-2">{{ item.asset.property_no }}</td>
                    <td class="px-2">{{ item.item_name }}</td>
                    <td class="px-2">{{ item.unit ?? 'N/A' }}</td>
                    <td class="px-2">{{ item.unit_cost ? `₱${item.unit_cost}` : 'N/A' }}</td>
                    <td class="px-2">{{ `${item.quantity} pcs.` }}</td>
                    <td class="px-2">
                        <button @click="deleteItem(item.id)" class="text-red-600 hover:underline">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
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