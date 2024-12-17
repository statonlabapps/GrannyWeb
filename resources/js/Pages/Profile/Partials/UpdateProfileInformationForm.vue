<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <v-card class="card">
        <div class="header-w-sub">
            <h2 class="text-h5 font text-white">Profile Information</h2>

            <p class="text-subtitle-1 font text-secondary">
                Update your account's profile information and email address.
            </p>
        </div>

        <v-card class="profile-inner-card">
            <div>
                <v-text-field
                    label="Name"
                    id="name"
                    required
                    class="font text-accent"
                    v-model="form.name"
                ></v-text-field>
                <InputError class="mt-2" :message="form.errors.name" />

                <v-text-field
                    label="Email"
                    id="email"
                    v-model="form.email"
                    required
                    class="font text-accent"
                ></v-text-field>

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <div class="font text-error">
                    Your email address is unverified.
                </div>
                <Link :href="route('verification.send')" method="post">
                    <v-btn color="secondary">
                        Re-send Verification Email.
                    </v-btn>
                </Link>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="font text-success"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="d-flex align-center">
                <v-btn
                    color="secondary"
                    class="font text-accent"
                    @click="form.patch(route('profile.update'))"
                    :disabled="form.processing"
                    >Save</v-btn
                >

                <div
                    v-if="form.recentlySuccessful"
                    class="font text-success ml-2"
                >
                    Saved!
                </div>
            </div>
        </v-card>
    </v-card>
</template>
<style scoped>
.profile-inner-card {
    padding: 8px;
    height: 85%;
    width: 100%;
    background-color: white;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow-y: auto;
}
</style>
