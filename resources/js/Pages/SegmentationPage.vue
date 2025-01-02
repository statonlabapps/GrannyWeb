<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, reactive, onMounted, watch } from 'vue';
import axios from 'axios';
import { defineProps } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps({
    analysis_id: String,
});
// Reactive state
const drawer = ref(false);
const rating = ref(false);
const group = ref(null);
const label = ref('');
const analysisType = ref('');
const maskedImage = ref({});
const segmentedImages = ref([]);
const segmentedImagesData = ref([]);
const analysis = reactive({});
const images = ref([]);
const imagesData = ref([]);

// List of items
const items = [
    { title: 'Starch', value: 'starch' },
    { title: 'Superficial Scald', value: 'scald' },
];

// Watchers
watch(group, () => {
    drawer.value = false;
});

// Mounted hook
onMounted(() => {
    getAnalysis();
    getMaskedImage();
});

// Methods
const getMaskedImage = async () => {
    try {
        const response = await axios.get(
            `/api/v1/getMaskedImage/${props.analysis_id}`,
            { responseType: 'arraybuffer' },
        );
        const blob = new Blob([response.data]);
        maskedImage.value = URL.createObjectURL(blob);
    } catch (error) {
        console.log(error);
    }
};

const getSegmentedImage = async (segment_number) => {
    try {
        const response = await axios.get(
            `/api/v1/getSegmentedImage/${props.analysis_id}/${segment_number}`,
            { responseType: 'arraybuffer' },
        );
        const blob = new Blob([response.data]);
        segmentedImages.value.push(URL.createObjectURL(blob));
    } catch (error) {
        console.log(error);
    }
};

const getSegmentedImageData = async (segment_number) => {
    try {
        const response = await axios.get(
            `/api/v1/getSegmentedImageData/${props.analysis_id}/${segment_number}`,
        );
        segmentedImagesData.value.push(response.data);
    } catch (error) {
        console.log(error);
    }
};

const updateSegmentedImageData = async (segment_number) => {
    try {
        const response = await axios.get(
            `/api/v1/getSegmentedImageData/${props.analysis_id}/${segment_number}`,
        );
        segmentedImagesData.value[segment_number - 1] = response.data;
    } catch (error) {
        console.log(error);
    }
};

const flagSegmentedImage = async (segment_number) => {
    try {
        const response = await axios.put(
            `/api/v1/flagSegmentedImage/${props.analysis_id}/${segment_number}`,
        );
        console.log(response);
        updateSegmentedImageData(segment_number);
    } catch (error) {
        console.log(error);
    }
};

const getAnalysis = async () => {
    try {
        const response = await axios.get(
            `/api/v1/getAnalysis/${props.analysis_id}`,
        );
        analysis.value = response.data;
        segmentedImages.value = [];
        segmentedImagesData.value = [];
        for (let x = 1; x <= analysis.value.segmented_image_count; x++) {
            getSegmentedImageData(x);
            getSegmentedImage(x);
        }
    } catch (error) {
        console.log(error);
    }
};

const fruitRating = async () => {
    rating.value = true;
    try {
        const response = await axios.put(
            `/api/v1/fruitRating/${props.analysis_id}/${analysisType.value}`,
        );
        console.log(response.data);
        router.visit('/fruit-rating/' + analysis.value.id, { method: 'get' });
    } catch (error) {
        console.log(error);
    }
};
</script>

<template>
    <Head title="Segmentation" />

    <Header title="Segmentation" :drawer="true" />
    <div class="content">
        <v-card class="card1">
            <div class="text-h5 mb-2 font-bold text-white">Segmentation</div>
            <v-card class="inner-card" color="white">
                <div
                    class="d-flex align-center w-100 mb-2 flex-row justify-center"
                >
                    <img :src="maskedImage" alt="" class="image" />
                </div>
                <div>
                    <v-select
                        class="font text-accent"
                        v-model="analysisType"
                        :items="items"
                        item-title="title"
                        item-value="value"
                        label="Select Rating Module"
                        variant="underlined"
                        color="accent"
                    >
                        <template v-slot:item="{ props, item }">
                            <v-list-item
                                class="text-accent font"
                                v-bind="props"
                            ></v-list-item>
                        </template>
                    </v-select>
                    <v-btn
                        color="secondary"
                        block
                        class="font text-accent"
                        :loading="rating"
                        @click="fruitRating()"
                        >Submit for Fruit Rating</v-btn
                    >
                </div>
            </v-card>
        </v-card>
        <v-card class="card2">
            <div class="text-h5 mb-2 font-bold text-white">
                Review Images
                <span v-if="analysis.value"
                    >({{ analysis.value.segmented_image_count }})</span
                >
            </div>
            <v-card class="segments-card" color="white">
                <v-card
                    v-for="(image, index) in segmentedImagesData"
                    color="secondary"
                    class="segment-outer-card"
                >
                    <div class="img-border">
                        <img
                            :class="
                                image.flagged ? 'flagged-img' : 'result-img'
                            "
                            :src="segmentedImages[index]"
                            alt=""
                        />
                    </div>
                    <div class="font text-accent text-p">
                        Fruit {{ image.segment_number }}
                    </div>

                    <v-btn
                        v-if="!image.flagged"
                        size="x-small"
                        color="error"
                        @click="flagSegmentedImage(image.segment_number)"
                        >Ignore</v-btn
                    >
                    <v-btn
                        v-if="image.flagged"
                        size="x-small"
                        color="warning"
                        prepend-icon="mdi-undo"
                        @click="flagSegmentedImage(image.segment_number)"
                        >Undo</v-btn
                    >
                </v-card>
            </v-card>
        </v-card>
    </div>
</template>

<style scoped>
.image {
    width: 100%;
    height: auto;
    border-color: rgb(var(--v-theme-secondary));
    border-style: solid;
    border-width: 2px;
}
.card1 {
    margin: 8px;
    height: 500px;
    width: calc(45% - 16px);
    padding: 8px !important;
    background-color: rgb(var(--v-theme-accent));
}
.card2 {
    margin: 8px;
    height: 500px;
    width: calc(55% - 16px);
    padding: 8px;
    background-color: rgb(var(--v-theme-accent));
}
.segments-card {
    height: calc(450px - 8px);
    width: 100%;
    background-color: white;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    overflow-y: auto;
}
.segment-outer-card {
    padding: 4px;
    width: calc(20% - 16px);
    margin: 8px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.result-img {
    height: 60px;
    width: 60px;
}
.img-border {
    border-style: solid;
    border-width: 2px;
    border-color: white;
}
.flagged-img {
    height: 60px;
    width: 60px;
    filter: brightness(30%);
}
</style>
