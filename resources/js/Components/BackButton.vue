<script setup>
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    whereTo: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link :href="route(props.whereTo)">
        <v-btn class="text-white" variant="plain" icon="mdi-arrow-left"></v-btn>
    </Link>
</template>
<style scoped></style>
