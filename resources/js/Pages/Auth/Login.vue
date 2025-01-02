<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import BackButton from '@/Components/BackButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});
const visible = ref(false);
const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log In" />

        <div v-if="status" class="mb-4 text-sm font-medium text-orange-600">
            {{ status }}
        </div>
        <v-card class="login-card" color="accent" @submit.prevent="submit">
            <div class="text-h3 font d-flex align-center mb-5 text-white">
                <BackButton whereTo="landing" />
                <div>Login</div>
            </div>
            <v-divider
                class="divider border-opacity-100"
                :thickness="5"
            ></v-divider>
            <div class="mt-5">
                <v-text-field
                    hide-details="auto"
                    label="Email address"
                    placeholder="johndoe@gmail.com"
                    type="email"
                    v-model="form.email"
                    class="font text-accent bg-white"
                ></v-text-field>

                <InputError class="font mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-5">
                <v-text-field
                    class="font text-accent bg-white"
                    :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                    :type="visible ? 'text' : 'password'"
                    @click:append-inner="visible = !visible"
                    v-model="form.password"
                    hide-details="auto"
                    label="Password"
                    type="password"
                ></v-text-field>

                <InputError class="font mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-5 block">
                <label class="d-flex align-center">
                    <v-checkbox
                        v-model:checked="form.remember"
                        color="secondary"
                        class="font"
                        label="Remember Me"
                    ></v-checkbox>
                </label>
            </div>

            <div class="flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="font text-white"
                >
                    Forgot your password?
                </Link>

                <v-btn
                    class="font text-accent ml-5"
                    color="secondary"
                    @click="submit()"
                    :disabled="form.processing"
                >
                    Log in
                </v-btn>
            </div>
        </v-card>
    </GuestLayout>
</template>
<style scoped>
.login-card {
    width: 500px;
    padding: 20px;
}
</style>
