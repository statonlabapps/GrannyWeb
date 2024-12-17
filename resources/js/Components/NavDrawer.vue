<script setup>
import { defineProps, ref, watch } from 'vue';

const group = ref(null);
const items = [
    { title: 'Foo', value: 'foo' },
    { title: 'Bar', value: 'bar' },
    { title: 'Fizz', value: 'fizz' },
    { title: 'Buzz', value: 'buzz' },
];

watch(group, () => {
    drawer.value = false;
});
</script>
<template>
    <v-btn id="menu-activator" variant="text" color="white" icon="mdi-menu">
    </v-btn>

    <v-menu activator="#menu-activator">
        <v-list>
            <v-list-item
                v-for="(item, index) in items"
                :key="index"
                :value="index"
            >
                <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-menu>
</template>
<style scoped></style>
