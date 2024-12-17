<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import BackButton from '@/Components/BackButton.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <v-card color="accent" class="forgot-card">
            <div class="text-h3 font d-flex align-center mb-5 text-white">
                <BackButton whereTo="landing" />
                <div>Forgot Password</div>
            </div>
            <v-divider
                class="divider border-opacity-100"
                :thickness="5"
            ></v-divider>
            <div class="font my-5 text-white">
                Forgot your password? No problem. Just let us know your email
                address and we will email you a password reset link that will
                allow you to choose a new one.
            </div>

            <div
                v-if="status"
                class="mb-4 text-sm font-medium text-green-600 dark:text-green-400"
            >
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <v-text-field
                        v-model="form.email"
                        hide-details="auto"
                        label="Email"
                        class="font text-accent bg-white"
                    ></v-text-field>

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4 flex items-center justify-end">
                    <v-btn
                        :disabled="form.processing"
                        color="secondary"
                        class="font text-accent"
                        @click="submit"
                    >
                        Email Password Reset Link
                    </v-btn>
                </div>
            </form>
        </v-card>
    </GuestLayout>
</template>

<style scoped>
.forgot-card {
    width: 500px;
    padding: 20px;
}
</style>
