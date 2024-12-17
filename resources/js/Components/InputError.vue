<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="font text-error">
            {{ message }}
        </p>
    </div>
</template>
