<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';
import Header from '@/Components/Header.vue';

// Reactive properties using `ref`
const isMobile = ref(false);
const analyses = ref([]);
const originalImages = ref([]);

// Mobile check function
const mobileCheck = () => {
    if (window.innerWidth <= 1000) {
        isMobile.value = true;
    } else {
        isMobile.value = false;
    }
};

// Function to fetch analyses data
const getAnalyses = async () => {
    try {
        const response = await axios.get(
            'http://127.0.0.1:8001/api/v1/getAnalyses',
        );
        originalImages.value = [];
        analyses.value = response.data;

        if (analyses.value) {
            analyses.value.forEach((analysis) => {
                console.log(analysis.id);
                getOriginalImage(analysis.id);
            });
        }
    } catch (error) {
        console.log(error);
    }
};

// Function to fetch the original image for a specific analysis
const getOriginalImage = async (analysis_id) => {
    try {
        const response = await axios.get(
            `http://127.0.0.1:8001/api/v1/getOriginalImage/${analysis_id}`,
            { responseType: 'arraybuffer' },
        );
        const blob = new Blob([response.data]);
        originalImages.value.push(URL.createObjectURL(blob));
    } catch (error) {
        console.log(error);
    }
};
const goToHistoryPage = async (analysis_id) => {
    router.visit('/summary/' + analysis_id, { method: 'get' });
};
// Lifecycle hooks for component setup
onMounted(() => {
    mobileCheck();
    getAnalyses();
    window.addEventListener('resize', mobileCheck);
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', mobileCheck);
});
</script>

<template>
    <Head title="Pears" />

    <AuthenticatedLayout>
        <Header title="Pears Dashboard" location="dashboard" />

        <v-card class="card">
            <div class="card-title">
                <div class="text-h5 mb-2 font-bold text-white">
                    Analysis History
                </div>
                <Link :href="route('new-analysis')">
                    <v-btn color="secondary" class="text-accent font"
                        >New Analysis</v-btn
                    >
                </Link>
            </div>
            <v-card class="inner-card" color="white">
                <v-card
                    color="secondary"
                    v-if="analyses.length == 0"
                    class="history-card text-accent font mb-2"
                >
                    No Analyses Available
                </v-card>
                <v-card
                    v-for="(analysis, index) in analyses"
                    :key="index"
                    class="history-card text-accent mb-2"
                    color="secondary"
                    @click="goToHistoryPage(analysis.id)"
                >
                    <div class="d-flex align-center w-50">
                        <v-icon color="accent" icon="mdi-food-apple"></v-icon>

                        <div class="d-flex flex-column ml-5">
                            <div class="font text-accent">
                                ID: {{ analysis.custom_id }}
                            </div>
                            <div class="font text-accent">
                                User:
                                {{ analysis.user ? analysis.user : 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-center w-50 justify-between">
                        <div class="d-flex flex-column align-baseline">
                            <div class="font text-accent">
                                Date: {{ analysis.date }}
                            </div>
                            <div class="font text-accent">
                                Time: {{ analysis.time }}
                            </div>
                        </div>

                        <div class="d-flex align-center justify-end">
                            <img
                                class="img"
                                :src="originalImages[index]"
                                alt=""
                            />
                        </div>
                    </div>
                </v-card>
            </v-card>
        </v-card>
    </AuthenticatedLayout>
</template>

<style scoped>
.history-card {
    width: 100%;
    min-height: 68px !important;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px;
}

.img {
    height: 50px;
    width: auto;
    border-style: solid;
    border-width: 1px;
    border-color: rgb(var(--v-theme-accent));
}
</style>
