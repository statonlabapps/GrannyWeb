<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { ref, onMounted, computed } from 'vue';
import Papa from 'papaparse'; // Library to parse CSV data
import axios from 'axios'; // Assuming axios is imported globally
import { defineProps } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import NavDrawer from '@/Components/NavDrawer.vue';

const props = defineProps({
    analysis_id: String,
});
// Reactive state using refs
const scaleValue = ref(null);
const menuOpen = ref(false);
const menu = ref(false);
const resultsCSV = ref('');
const selectedScale = ref({ key: '', value: '' });
const summaryCSV = ref([]);
const analysis = ref({});
const maskedImage = ref({});
const originalImage = ref({});
const tab = ref(null);
const ratings = ref([]);
const ratingImages = ref([]);
const ratingImagesData = ref([]);
const segmentedImages = ref([]);
const segmentedImages2 = ref([]);
const segmentedImagesData = ref([]);
const fields = ref([
    'Name',
    'HONEY_CRISP',
    'WA38_1',
    'WA38_2',
    'ALLAN_BROS',
    'GOLDEN_DELICIOUS',
    'GRANNY_SMITH',
    'JONAGOLD',
    'CORNELL',
    'rating',
    'TrayName',
]);
const scales = ref([
    {
        key: 'Avg. Rating',
        value: 'rating',
    },
    {
        key: 'Honey Crisp',
        value: 'HONEY_CRISP',
    },
    { key: 'WA-38 V1', value: 'WA38_1' },
    { key: 'WA-38 V2', value: 'WA38_2' },
    { key: 'Allan Brothers', value: 'ALLAN_BROS' },
    { key: 'Golden Delicious', value: 'GOLDEN_DELICIOUS' },
    { key: 'Granny Smith', value: 'GRANNY_SMITH' },
    { key: 'Jonagold', value: 'JONAGOLD' },
    { key: 'Cornel', value: 'CORNELL' },
]);
var summaryData = ref([]);
const dt = ref();
const exportCSV = () => {
    dt.value.exportCSV();
};
const dialogVisible = ref(false);

// Fetch CSV data
const fetchResults = async () => {
    try {
        const response = await axios.get(
            `/api/v1/getResults/${props.analysis_id}`,
            { responseType: 'text' },
        );
        resultsCSV.value = Papa.parse(response.data, {
            skipEmptyLines: true,
            header: true,
        });
    } catch (error) {
        console.error(error);
    }
};
const fetchSummary = async () => {
    try {
        const response = await axios.get(
            `/api/v1/getTraySummary/${props.analysis_id}`,
            { responseType: 'text' },
        );
        // Log the keys and values for each row in the parsed data
        Papa.parse(response.data, {
            skipEmptyLines: true,
            header: true,
        }).data.forEach((row) => {
            Object.entries(row).forEach(([key, value]) => {
                // if key is TrayName or rating
                summaryCSV.value.push({ key: key, value: value });
            });
        });
    } catch (error) {
        console.error(error);
    }
};

// Fetch original image
const getOriginalImage = async () => {
    try {
        const response = await axios.get(
            `/api/v1/getOriginalImage/${props.analysis_id}`,
            { responseType: 'arraybuffer' },
        );
        const blob = new Blob([response.data]);
        originalImage.value = URL.createObjectURL(blob);
    } catch (error) {
        console.log(error);
    }
};

// Fetch masked image
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

// Fetch rating image for a segment
const getRatingImage = async (segment_number) => {
    try {
        const response = await axios.get(
            `/api/v1/getRatingImage/${props.analysis_id}/${segment_number}`,
            { responseType: 'arraybuffer' },
        );
        const blob = new Blob([response.data]);
        ratingImages.value.push(URL.createObjectURL(blob));
    } catch (error) {
        console.log(error);
    }
};

// Fetch rating image data for a segment
const getRatingImageData = async (segment_number) => {
    try {
        const response = await axios.get(
            `/api/v1/getSegmentedImageData/${props.analysis_id}/${segment_number}`,
        );
        ratingImagesData.value.push(response.data);
    } catch (error) {
        console.log(error);
    }
};

// Fetch analysis data
const getAnalysis = async () => {
    try {
        const response = await axios.get(
            `/api/v1/getAnalysis/${props.analysis_id}`,
        );
        analysis.value = response.data;
        ratingImages.value = [];
        ratingImagesData.value = [];
        segmentedImages.value = [];
        segmentedImagesData.value = [];
        summaryData.value.push({
            name: 'Submitted By',
            value: analysis.value.user_name,
        });
        summaryData.value.push({
            name: 'Time Started',
            value: analysis.value.time,
        });
        summaryData.value.push({
            name: 'Original Image',
            value: analysis.value.original_image,
        });
        summaryData.value.push({
            name: 'Segmentation Model',
            value: analysis.value.a1_model,
        });
        summaryData.value.push({
            name: 'Analysis Type',
            value: analysis.value.a2_type,
        });

        for (let x = 0; x < segmentedImages2.value.length; x++) {
            await getRatingImageData(segmentedImages2.value[x].segment_number);
            await getRatingImage(segmentedImages2.value[x].segment_number);
            await getSegmentedImageData(
                segmentedImages2.value[x].segment_number,
            );
            await getSegmentedImage(segmentedImages2.value[x].segment_number);
        }
    } catch (error) {
        console.log(error);
    }
};
const secondSummaryItem = computed(() => {
    return summaryData.value.slice(1, 2); // returns the first element
});
// Computed property for the first two elements of summaryData
const firstTwoSummaryItems = computed(() => {
    return summaryData.value.slice(0, 2); // returns the first two elements
});

// Computed property for every element except the first two
const remainingSummaryItems = computed(() => {
    return summaryData.value.slice(2); // returns a new array excluding the first two elements
});

// Fetch segmented image for a segment
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

// Fetch segmented images data
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

// Fetch segmented images
const getSegmentedImages = async () => {
    try {
        const response = await axios.get(
            `/api/v1/getSegmentedImages/${props.analysis_id}`,
        );
        segmentedImages2.value = response.data;
    } catch (error) {
        console.log(error);
    }
};
const capitalizeFirstLetter = (str) => {
    if (!str) return str; // Handle empty string case
    return str.charAt(0).toUpperCase() + str.slice(1);
};

// Mounted lifecycle hook
onMounted(() => {
    getSegmentedImages();
    getAnalysis();
    getMaskedImage();
    getOriginalImage();
    fetchResults();
    fetchSummary();
    selectedScale.value.key = 'Avg. Rating';
    selectedScale.value.value = 'rating';
});
</script>

<template>
    <Head title="Fruit Rating" />
    <AuthenticatedLayout>
        <Header title="Fruit Rating" :drawer="true" />
        <div class="content">
            <!-- card 1 -->
            <v-card
                class="d-flex flex-column card1 justify-between"
                color="accent"
            >
                <div class="text-h5 font-bold text-white">Data Summary</div>
                <v-card class="inner-card d-flex flex-column justify-between">
                    <div class="d-flex flex-column">
                        <v-table>
                            <tbody>
                                <tr v-for="data in secondSummaryItem">
                                    <td class="font text-accent w-50">
                                        {{ data.name }}
                                    </td>
                                    <td class="font text-accent">
                                        {{ data.value }}
                                    </td>
                                </tr>
                                <tr v-for="data in summaryCSV">
                                    <td
                                        v-if="data.key == 'TrayName'"
                                        class="font text-accent w-50"
                                    >
                                        Tray Name
                                    </td>
                                    <td
                                        v-if="data.key == 'TrayName'"
                                        class="font text-accent w-50"
                                    >
                                        {{ data.value }}
                                    </td>
                                </tr>
                                <tr class="ratings" v-for="data in summaryCSV">
                                    <td
                                        v-if="data.key == selectedScale.value"
                                        class="font text-accent w-50"
                                    >
                                        <v-menu v-model="menuOpen">
                                            <template
                                                v-slot:activator="{ props }"
                                            >
                                                <v-btn
                                                    size="small"
                                                    variant="text"
                                                    class="font text-accent text-capitalize mx-0 px-0"
                                                    color="white"
                                                    v-bind="props"
                                                    :append-icon="
                                                        menuOpen
                                                            ? 'mdi-chevron-up'
                                                            : 'mdi-chevron-down'
                                                    "
                                                >
                                                    {{ selectedScale.key }}
                                                </v-btn>
                                            </template>
                                            <v-list>
                                                <v-list-item
                                                    v-for="(
                                                        scale, index
                                                    ) in scales"
                                                    :key="index"
                                                    :value="scale"
                                                    @click="
                                                        selectedScale = scale;
                                                        menuOpen = false;
                                                    "
                                                >
                                                    <v-list-item-title
                                                        class="font text-accent"
                                                    >
                                                        {{ scale.key }}
                                                    </v-list-item-title>
                                                </v-list-item>
                                            </v-list>
                                        </v-menu>
                                    </td>
                                    <td
                                        v-if="data.key == selectedScale.value"
                                        class="font text-accent w-50"
                                    >
                                        {{ data.value }}
                                    </td>
                                </tr>
                                <tr v-for="data in remainingSummaryItems">
                                    <td class="font text-accent w-50">
                                        {{ data.name }}
                                    </td>
                                    <td
                                        v-if="data.name === 'Analysis Type'"
                                        class="font text-accent w-50"
                                    >
                                        {{ capitalizeFirstLetter(data.value) }}
                                    </td>
                                    <td v-else class="font text-accent">
                                        {{ data.value }}
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </div>
                    <div class="d-flex justify-between">
                        <v-btn
                            class="text-accent font"
                            color="secondary"
                            prepend-icon="mdi-file"
                            @click="dialogVisible = true"
                            >View Results CSV</v-btn
                        >
                        <Link :href="route('apples')">
                            <v-btn class="text-accent font" color="secondary"
                                >Done</v-btn
                            >
                        </Link>
                        <Dialog
                            v-model:visible="dialogVisible"
                            class="text-h5 bg-accent font-bold text-white"
                            header="Results"
                            :style="{ width: '90vw' }"
                            modal
                            :closable="false"
                            :contentStyle="{ height: '500px' }"
                        >
                            <template #header>
                                <div
                                    class="d-flex w-100 align-center justify-between"
                                >
                                    <div class="text-h5 font-bold text-white">
                                        Results
                                    </div>
                                    <v-btn
                                        color="secondary"
                                        variant="tonal"
                                        icon="mdi-close"
                                        @click="dialogVisible = false"
                                    ></v-btn>
                                </div>
                            </template>
                            <DataTable
                                ref="dt"
                                scrollable
                                sortField="Name"
                                :sortOrder="1"
                                scrollHeight="flex"
                                :value="resultsCSV.data"
                                showGridlines
                                size="small"
                                stripedRows
                                class="bg-white"
                            >
                                <Column
                                    class="text-accent font"
                                    sortable
                                    v-for="field in fields"
                                    :field="field"
                                    :header="field"
                                ></Column>
                            </DataTable>
                            <template #footer>
                                <v-btn
                                    class="text-accent font"
                                    color="secondary"
                                    prepend-icon="mdi-export"
                                    @click="exportCSV($event)"
                                    >Export</v-btn
                                >
                                <v-btn
                                    class="text-accent font"
                                    color="secondary"
                                    prepend-icon="mdi-check"
                                    @click="dialogVisible = false"
                                    >Done</v-btn
                                >
                            </template>
                        </Dialog>
                    </div>
                </v-card>
            </v-card>

            <!-- card 2 -->
            <v-card class="card2" color="accent">
                <div class="text-h5 card-title font-bold text-white">
                    Image Summary
                </div>
                <v-card
                    color="white"
                    class="inner-card d-flex flex-column justify-between"
                >
                    <v-tabs
                        v-model="tab"
                        align-tabs="center"
                        class="tabs"
                        slider-color="secondary"
                    >
                        <v-tab class="font text-accent" :value="1"
                            >Rating</v-tab
                        >
                        <v-tab class="font text-accent" :value="2"
                            >Segmentation</v-tab
                        >
                        <v-tab class="font text-accent" :value="3"
                            >Masked</v-tab
                        >
                        <v-tab class="font text-accent" :value="4"
                            >Original</v-tab
                        >
                    </v-tabs>

                    <v-tabs-window class="pa-0 ma-0" v-model="tab">
                        <v-tabs-window-item
                            class="pa-0 ma-0"
                            :key="1"
                            :value="1"
                        >
                            <v-container class="pa-0 ma-0 container" fluid>
                                <v-card class="segments-card">
                                    <v-card
                                        v-for="(
                                            image, index
                                        ) in ratingImagesData"
                                        class="segment-outer-card"
                                        color="white"
                                    >
                                        <img
                                            class="result-img"
                                            :src="ratingImages[index]"
                                            alt=""
                                        />
                                        <div class="font text-accent">
                                            Fruit {{ image.segment_number }}
                                        </div>
                                    </v-card>
                                </v-card>
                            </v-container>
                        </v-tabs-window-item>
                        <v-tabs-window-item :key="2" :value="2">
                            <v-container class="pa-0 ma-0" fluid>
                                <v-card class="segments-card">
                                    <v-card
                                        v-for="(
                                            image, index
                                        ) in segmentedImagesData"
                                        class="segment-outer-card"
                                        color="white"
                                    >
                                        <img
                                            class="result-img"
                                            :src="segmentedImages[index]"
                                            alt=""
                                        />
                                        <div class="text-p font text-accent">
                                            Fruit {{ image.segment_number }}
                                        </div>
                                    </v-card>
                                </v-card>
                            </v-container>
                        </v-tabs-window-item>
                        <v-tabs-window-item :key="3" :value="3">
                            <v-container class="pa-0 ma-0" fluid>
                                <v-card class="segments-card">
                                    <img
                                        class="image"
                                        :src="maskedImage"
                                        alt=""
                                    />
                                </v-card>
                            </v-container>
                        </v-tabs-window-item>
                        <v-tabs-window-item :key="4" :value="4">
                            <v-container class="pa-0 ma-0" fluid>
                                <v-card class="segments-card">
                                    <img
                                        class="image"
                                        :src="originalImage"
                                        alt=""
                                    />
                                </v-card>
                            </v-container>
                        </v-tabs-window-item>
                    </v-tabs-window>
                </v-card>
            </v-card>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.tabs {
    height: 60px;
}

.result-img {
    height: 55px;
    min-width: 55px;
    border-style: solid;
    border-width: 2px;
    border-color: rgb(var(--v-theme-secondary));
}
.rating-image:nth-child(6n) {
    margin-right: 0;
}

.csv-section {
    width: calc(100% - 2rem);
    margin-top: 1rem;
    margin-left: 1rem;
    margin-right: 1rem;
    height: 500px;
}
.section-title {
    height: 50px;
}
.section-card {
    height: 450px;
    padding: 10px;
}
.tab {
    display: flex;
    justify-content: center;
    align-items: center;
}
.image {
    height: 300px;
    width: auto;
    border-color: white;
    border-style: solid;
    border-width: 2px;
}
.ratings {
    background-color: white !important;
}
tr:nth-child(even) {
    background-color: rgba(var(--v-theme-secondary), 0.5);
}

tr:nth-child(odd) {
    background-color: #ffffff;
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
    height: calc(450px - 8px - 68px - 8px);
    width: 100%;
    background-color: rgb(var(--v-theme-secondary));
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    overflow-y: auto;
}
.segment-outer-card {
    padding: 4px;
    height: fit-content;
    width: calc(18% - 16px);
    margin: 2px 4px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
</style>
