<script setup>
    import { usePage } from '@inertiajs/vue3';
    import { computed } from 'vue';

    const page = usePage();
    const items = computed(() => page.props.items);
</script>

<template>
    <table>
        <tr>
            <thead>
                <th class="px-2">Par Number</th>
                <th class="px-2">Property Number</th>
                <th class="px-2">Item Name</th>
                <th class="px-2">Unit</th>
                <th class="px-2">Unit Cost</th>
                <th class="px-2">Quantity</th>
                
            </thead>
        </tr>
        <tr v-for="item in items">
            <tbody>
                <td class="px-2">{{ item.property_ack_receipt.par_number }}</td>
                <td class="px-2">{{ item.asset.property_no }}</td>
                <td class="px-2">{{ item.item_name }}</td>
                <td class="px-2">{{ item.unit ?? 'N/A' }}</td>
                <td class="px-2">{{ item.unit_cost ?? 'N/A' }}</td>
                <td class="px-2">{{ item.quantity }}</td>
            </tbody>
        </tr>
    </table>
</template>