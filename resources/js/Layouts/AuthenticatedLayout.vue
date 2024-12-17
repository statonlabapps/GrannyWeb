<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import NavDrawer from '@/Components/NavDrawer.vue';

// Define reactive state variables using `ref` for primitive values and `reactive` for objects or arrays
const navItems = ref([
    { title: 'Profile', route: 'profile.edit' },
    { title: 'Log Out', route: 'logout', method: 'post' },
]);
</script>

<template>
    <v-sheet color="flesh" class="main">
        <div class="topbar">
            <Link :href="route('dashboard')" class="d-flex align-center">
                <div class="logo-bg">
                    <img src="@/public/assets/granny_logo.png" class="logo" />
                </div>
                <div class="text-h4 title ml-2 font-bold text-white">
                    Granny
                </div>
            </Link>
            <!-- Settings Dropdown -->
            <div>
                <v-menu location="bottom">
                    <template v-slot:activator="{ props }">
                        <v-btn
                            class="font text-accent"
                            color="white"
                            v-bind="props"
                        >
                            {{ $page.props.auth.user.name }}
                        </v-btn>
                    </template>

                    <v-list>
                        <Link
                            v-for="(nav, index) in navItems"
                            :href="route(nav.route)"
                            :method="nav.method"
                            :key="index"
                        >
                            <v-list-item>
                                <v-list-item-title class="font text-accent">
                                    {{ nav.title }}
                                </v-list-item-title>
                            </v-list-item>
                        </Link>
                    </v-list>
                </v-menu>
            </div>
        </div>

        <!-- Page Heading -->
        <v-sheet v-if="$slots.header">
            <slot name="header" />
        </v-sheet>
        <!-- Page Content -->
        <main>
            <slot />
        </main>
    </v-sheet>
</template>

<style scoped>
.logo {
    height: 50px;
    width: 50px;
}
.logo-bg {
    height: 50px;
    width: 50px;
    border-radius: 50%;
    background-color: white;
    box-shadow: -2px 2px 2px rgb(var(--v-theme-accent));
}
.title {
    text-shadow: -2px 2px 2px rgb(var(--v-theme-accent));
}
.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 64px;
    padding: 20px;
}
.main {
    min-height: 100vh;
}
</style>
