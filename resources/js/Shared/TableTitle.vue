<script setup>
import { useAttrs } from 'vue';
useAttrs()
</script>
<template>
    <h1 v-bind="$attrs" :class="$attrs.class" class="text-2xl text-gray-500 font-extrabold">
        <slot />
    </h1>
</template>