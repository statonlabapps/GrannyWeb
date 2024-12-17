<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Header from '@/Components/Header.vue';

const user = usePage().props.auth.user;

// Reactive state using `ref`
const isMobile = ref(false);
const uploader = ref('');
const isSelecting = ref(false);
const segmenting = ref(false);
const selectedFile = ref(null);
const url = ref(null);
const a1_model = ref('');
const custom_id = ref('');
const analysis = ref([]);
const models = ref([
    {
        title: 'Pome Fruit - Apple',
        value: 'pome_fruit-v1_0',
    },
]);

// Mobile check function
const mobileCheck = () => {
    isMobile.value = window.innerWidth <= 1000;
};

// Handle file import
const handleFileImport = () => {
    isSelecting.value = true;

    // After obtaining the focus when closing the FilePicker, return the button state to normal
    window.addEventListener(
        'focus',
        () => {
            isSelecting.value = false;
        },
        { once: true },
    );

    // Trigger click on the FileInput
    // This will be handled via `ref`
    uploader.value.click();
};

// Handle file change
const onFileChanged = (e) => {
    selectedFile.value = e.target.files[0];
    url.value = URL.createObjectURL(selectedFile.value);
    console.log(selectedFile.value);
    // Do whatever you need with the file, like reading it with FileReader
};

// Upload image function
const uploadImage = async () => {
    segmenting.value = true;
    const formData = new FormData();
    formData.append('image', selectedFile.value);
    formData.append('custom_id', custom_id.value);
    formData.append('a1_model', a1_model.value);
    formData.append('user_id', user.id);
    formData.append('user_name', user.name);

    try {
        const response = await axios.post(
            'http://127.0.0.1:8001/api/v1/newAnalysis',
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            },
        );
        analysis.value = response.data;
        console.log('Image uploaded successfully:', analysis.value.id);
        segmenting.value = false;
        console.log(analysis.value.id);
        router.visit('/segmentation/' + analysis.value.id, { method: 'get' });
    } catch (error) {
        console.error('Error uploading image:', error);
    }
};

// Lifecycle hooks
onMounted(() => {
    mobileCheck();
    window.addEventListener('resize', mobileCheck);
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', mobileCheck);
});
</script>

<template>
    <Head title="New Analysis" />

    <AuthenticatedLayout>
        <Header title="New Analysis - Apple" location="dashboard" />
        <div class="content">
            <v-card color="accent" class="card">
                <div class="card-title">
                    <div class="text-h5 font text-white">Image Upload</div>
                </div>
                <div class="d-flex flex-column align-center justify-center">
                    <div
                        v-if="url"
                        class="d-flex flex-column upload mb-2 justify-evenly"
                    >
                        <img :src="url" class="image mb-5" />
                        <div v-if="url">
                            <v-btn
                                class="font text-accent"
                                color="secondary"
                                :loading="isSelecting"
                                @click="handleFileImport()"
                            >
                                Change Image
                            </v-btn>
                            <input
                                ref="uploader"
                                class="d-none"
                                type="file"
                                @change="onFileChanged"
                            />
                        </div>
                    </div>
                    <div v-if="!url" class="d-flex upload mb-2 justify-evenly">
                        <v-btn
                            class="font text-accent"
                            color="secondary"
                            :loading="isSelecting"
                            @click="handleFileImport()"
                        >
                            Upload File
                        </v-btn>
                        <input
                            ref="uploader"
                            class="d-none"
                            type="file"
                            @change="onFileChanged"
                        />
                    </div>
                </div>
            </v-card>
            <!-- Info card -->

            <v-card color="accent" class="card">
                <div class="text-h5 font card-title text-white">Input Data</div>
                <div class="inner-card d-flex flex-column justify-between">
                    <div>
                        <v-select
                            class="font text-accent"
                            v-model="a1_model"
                            :items="models"
                            label="Select Model"
                        >
                            <template v-slot:item="{ props, item }">
                                <v-list-item
                                    class="text-accent font"
                                    v-bind="props"
                                ></v-list-item>
                            </template>
                        </v-select>
                        <v-text-field
                            label="Enter Custom ID"
                            class="font text-accent"
                            v-model="custom_id"
                        ></v-text-field>
                    </div>
                    <div class="font text-accent mb-5">
                        * The segmentation step takes the image of a tray of
                        fruit and extracts each individual piece of fruit into a
                        new image. The fruit itself needs to be isolated from
                        any background material in the image so that the rating
                        modules can analyze only the fruit and none of the other
                        elements in the image.
                    </div>

                    <v-btn
                        color="secondary"
                        class="font text-accent"
                        :loading="segmenting"
                        @click="uploadImage()"
                    >
                        Submit for Segmentation
                    </v-btn>
                </div>
            </v-card>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.input {
    height: 2px !important;
}

.upload {
    height: calc(450px - 8px);
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: white;
    border-style: solid;
    border-width: 2px;
    border-color: rgb(var(--v-theme-primary));
}
.analysis-data {
    padding: 1rem;
    height: 100px;
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
}
.image {
    height: 300px;
    width: auto;
    border-color: rgb(var(--v-theme-accent));
}
.history-card {
    min-height: 80px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.inner-card {
    height: 400px;
    display: flex;
    flex-direction: column;
    overflow-y: scroll;
}
.card {
    min-height: 300px;
    height: fit-content;
    width: 500px;
    margin-top: 1rem;
}
.logo {
    height: auto;
    width: 150px;
}
.analysis-history {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.analysis {
    min-height: 60px;
    width: 100%;
    display: flex;
    justify-content: space-evenly;
    align-items: center;
}
.btn {
    height: 60px;
    width: 100%;
}
.img {
    height: auto;
    width: 500;
}
</style>
