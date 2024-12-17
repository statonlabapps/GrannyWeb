<script setup>
const imgUrl = new URL('@/public/assets/granny_logo.png', import.meta.url).href;
</script>
<template>
    <div class="logo-bg mb-5">
        <v-img class="text-white" height="150" width="150" :src="imgUrl" cover>
        </v-img>
    </div>
</template>

<style scoped>
.logo-bg {
    height: 150px;
    width: 150px;
    border-radius: 50%;
    background-color: white;
    box-shadow: -4px 4px 4px rgb(var(--v-theme-accent));
}
</style>
