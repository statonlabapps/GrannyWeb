<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const dialog = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = !confirmingUserDeletion.value;
    dialog.value = !dialog.value;
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <v-card class="delete-card">
        <div class="delete-header">
            <h2 class="text-h5 font text-white">Delete Account</h2>
            <v-divider
                class="divider w-25 mb-2 border-opacity-100"
                :thickness="5"
            ></v-divider>
            <p class="text-subtitle-1 font text-white">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Before deleting your account, please
                download any data or information that you wish to retain.
            </p>
        </div>

        <v-dialog v-model="dialog" max-width="500" persistent>
            <template v-slot:activator="{ props: activatorProps }">
                <div>
                    <v-btn color="error" class="font" v-bind="activatorProps">
                        Delete Account
                    </v-btn>
                </div>
            </template>

            <v-card
                prepend-icon="mdi-alert"
                title="Are You Sure?"
                text="Once your account is deleted, all of its resources and data will
                be permanently deleted. Please enter your password to confirm
                you would like to permanently delete your account."
                class="font text-accent"
                color="secondary"
            >
                <div class="mx-5">
                    <v-text-field
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    >
                    </v-text-field>

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <template v-slot:actions>
                    <v-spacer></v-spacer>

                    <v-btn
                        color="accent"
                        class="font"
                        variant="elevated"
                        @click="confirmUserDeletion"
                    >
                        Disagree
                    </v-btn>

                    <v-btn
                        color="error"
                        class="font"
                        variant="elevated"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Agree
                    </v-btn>
                </template>
            </v-card>
        </v-dialog>
    </v-card>
</template>
<style scoped>
.delete-card {
    margin: 8px;
    height: 180px;
    width: calc(100% - 16px);
    padding: 8px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background-color: rgb(var(--v-theme-accent));
}

.delete-header {
    height: 50%;
    padding-bottom: 8px;
}
</style>
