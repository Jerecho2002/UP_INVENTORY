<script setup>
    defineProps({ extra: String });
</script>   

<template>
  <td :class="['p-2 sm:p-3 md:p-4 align-middle break-words', extra]">
    <slot />
  </td>
</template>

