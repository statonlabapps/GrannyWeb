<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);
const visible = ref(false);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <v-card class="card">
        <div class="header-w-sub">
            <h2 class="text-h5 font text-white">Update Password</h2>

            <p class="text-subtitle-1 font text-secondary">
                Update password periodically to stay secure.
            </p>
        </div>
        <v-card class="profile-inner-card">
            <div>
                <InputError
                    :message="form.errors.current_password"
                    class="mb-2"
                />
                <v-text-field
                    label="Current Password"
                    id="current_password"
                    ref="currentPasswordInput"
                    :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                    :type="visible ? 'text' : 'password'"
                    @click:append-inner="visible = !visible"
                    v-model="form.current_password"
                    type="password"
                    class="font text-accent focus:ring-0"
                ></v-text-field>

                <InputError :message="form.errors.password" class="mb-2" />

                <v-text-field
                    label="New Password"
                    class="font text-accent"
                    :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                    :type="visible ? 'text' : 'password'"
                    @click:append-inner="visible = !visible"
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                ></v-text-field>

                <InputError
                    :message="form.errors.password_confirmation"
                    class="mb-2"
                />
                <v-text-field
                    label="Confirm Password"
                    class="font text-accent"
                    :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                    :type="visible ? 'text' : 'password'"
                    @click:append-inner="visible = !visible"
                    ref="passwordInput"
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                ></v-text-field>
            </div>

            <div class="d-flex align-center">
                <v-btn
                    class="font text-accent"
                    color="secondary"
                    @click="updatePassword"
                    >Save</v-btn
                >

                <div
                    v-if="form.recentlySuccessful"
                    class="font text-p text-success ml-2"
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
