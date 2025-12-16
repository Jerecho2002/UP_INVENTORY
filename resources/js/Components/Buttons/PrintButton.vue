<script setup>
const props = defineProps({
  item: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(["print"]);

function handlePrint() {
  emit("print", props.item);
}
</script>

<template>
  <button
    @click="handlePrint"
    class="text-[#0E6021] hover:text-[#0a7523]"
    title="Print"
  >
    <i class="fa-solid fa-print"></i>
  </button>
</template>
