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

const page = usePage();
const items = computed(() => page.props.items);
</script>

<template>
    <div>
        <input type="search" placeholder="Search" v-model="search">
    </div>
    <table>
        <thead>
            <tr>
                <th class="px-2">Par Number</th>
                <th class="px-2">Property Number</th>
                <th class="px-2">Item Name</th>
                <th class="px-2">Unit</th>
                <th class="px-2">Unit Cost</th>
                <th class="px-2">Quantity</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in items.data" :key="item.id">
                <td class="px-2">{{ item.property_ack_receipt.par_number }}</td>
                <td class="px-2">{{ item.asset.property_no }}</td>
                <td class="px-2">{{ item.item_name }}</td>
                <td class="px-2">{{ item.unit ?? 'N/A' }}</td>
                <td class="px-2">{{ item.unit_cost ?? 'N/A' }}</td>
                <td class="px-2">{{ item.quantity }}</td>
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
</template>