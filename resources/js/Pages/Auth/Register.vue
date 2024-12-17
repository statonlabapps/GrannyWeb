<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import BackButton from '@/Components/BackButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const visible = ref(false);

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <v-card
            color="accent"
            class="registration-card"
            @submit.prevent="submit"
        >
            <div class="text-h3 font d-flex align-center mb-5 text-white">
                <BackButton whereTo="landing" />
                <div>Register</div>
            </div>
            <v-divider
                class="divider border-opacity-100"
                :thickness="5"
            ></v-divider>
            <div class="mt-5">
                <v-text-field
                    hide-details="auto"
                    label="Name"
                    v-model="form.name"
                    class="font text-accent bg-white"
                ></v-text-field>

                <InputError class="font mt-2" :message="form.errors.name" />
            </div>
            <div class="mt-5">
                <v-text-field
                    hide-details="auto"
                    label="Email address"
                    v-model="form.email"
                    class="font text-accent bg-white"
                ></v-text-field>

                <InputError class="font mt-2" :message="form.errors.email" />
            </div>
            <div class="mt-5">
                <v-text-field
                    hide-details="auto"
                    :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                    :type="visible ? 'text' : 'password'"
                    @click:append-inner="visible = !visible"
                    label="Password"
                    type="password"
                    v-model="form.password"
                    class="font text-accent bg-white"
                ></v-text-field>

                <InputError class="font mt-2" :message="form.errors.password" />
            </div>
            <div class="mt-5">
                <v-text-field
                    hide-details="auto"
                    :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                    :type="visible ? 'text' : 'password'"
                    @click:append-inner="visible = !visible"
                    label="Confirm Password"
                    v-model="form.password_confirmation"
                    class="font text-accent bg-white"
                ></v-text-field>

                <InputError
                    class="font mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-5 flex items-center justify-end">
                <Link class="font text-white" :href="route('login')">
                    Already registered?
                </Link>

                <v-btn
                    class="text-accent ml-5"
                    color="secondary"
                    :disabled="form.processing"
                    @click="submit()"
                >
                    Register
                </v-btn>
            </div>
        </v-card>
    </GuestLayout>
</template>

<style scoped>
.registration-card {
    width: 500px;
    padding: 20px;
}
</style>
