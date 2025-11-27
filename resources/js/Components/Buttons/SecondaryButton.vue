<template>
        <button @click="$emit('click')"
             class="flex rounded-md px-3 py-2 bg-[#54B3AB] hover:bg-[#4cbbb2] w-full sm:w-auto items-center mt-2 justify-center">
            <slot></slot>
        </button>
</template> 