<script setup>
import BackButton from '@/Components/BackButton.vue';
import { defineProps, ref, watch } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';

const drawer = ref(false);
const newAnalysisAlert = ref(false);
const segmentationAlert = ref(false);
const abortAlert = ref(false);
const group = ref(null);
const page = usePage();
const props = defineProps({
    title: {
        type: String,
    },
    location: {
        type: String,
    },
    drawer: {
        type: Boolean,
    },
});

// Function to fetch analyses data
const abortFromSegmentation = async (destination) => {
    try {
        const response = await axios.delete(
            'http://127.0.0.1:8001/api/v1/abortFromSegmentation/' +
                page.props.analysis_id,
        );
        console.log(response.data);
        router.visit(destination);
    } catch (error) {
        console.log(error);
    }
};
const abortFromFruitRating = async (destination) => {
    try {
        const response = await axios.delete(
            'http://127.0.0.1:8001/api/v1/abortFromSegmentation/' +
                page.props.analysis_id,
        );
        console.log(response.data);
        router.visit(destination);
    } catch (error) {
        console.log(error);
    }
};
watch(group, () => {
    drawer.value = false;
});
</script>

<template>
    <div class="header elevation-5">
        <BackButton v-if="props.location" :whereTo="props.location" />
        <v-btn
            v-if="props.drawer"
            icon="mdi-menu"
            color="white"
            variant="plain"
            @click="drawer = !drawer"
        ></v-btn>
        <h2 class="text-h4 title ml-2 font-bold text-white">
            {{ props.title }}
        </h2>
        <v-dialog v-model="newAnalysisAlert" width="auto">
            <v-card
                max-width="400"
                prepend-icon="mdi-restart"
                title="Return to New Analysis Page"
                class="font text-white"
                color="accent"
                text="Following through with this action will result in the deletion of your progress so far. Are you sure?"
            >
                <template v-slot:actions>
                    <v-btn
                        class="font"
                        variant="elevated"
                        color="secondary"
                        @click="newAnalysisAlert = false"
                        >Cancel</v-btn
                    >
                    <v-btn
                        variant="elevated"
                        color="error"
                        class="font ml-2"
                        v-if="page.component == 'SegmentationPage'"

                        @click="abortFromSegmentation('/new-analysis')"
                        >Continue</v-btn
                    >
                    <v-btn
                        variant="elevated"
                        color="error"
                        class="font ml-2"
                        v-if="page.component == 'FruitRatingPage'"
                        @click="abortFromFruitRating('/new-analysis')"
                        >Continue</v-btn
                    >
                </template>
            </v-card>
        </v-dialog>
        <v-dialog v-model="abortAlert" width="auto">
            <v-card
                max-width="400"
                prepend-icon="mdi-restart"
                title="Return to Apples Dashboard"
                class="font text-white"
                color="accent"
                text="Following through with this action will result in the deletion of your progress so far. Are you sure?"
            >
                <template v-slot:actions>
                    <v-btn
                        class="font"
                        variant="elevated"
                        color="secondary"
                        @click="abortAlert = false"
                        >Cancel</v-btn
                    >
                    <v-btn
                        variant="elevated"
                        color="error"
                        class="font ml-2"
                        v-if="page.component == 'SegmentationPage'"
                        @click="abortFromSegmentation('/apples')"
                        >Continue</v-btn
                    >
                    <v-btn
                        variant="elevated"
                        color="error"
                        class="font ml-2"
                        v-if="page.component == 'FruitRatingPage'"
                        @click="abortFromFruitRating('/apples')"
                        >Continue</v-btn
                    >
                </template>
            </v-card>
        </v-dialog>

        <v-layout>
            <v-navigation-drawer
                v-model="drawer"
                location="left"
                temporary
                class="nav-menu"
            >
                <div class="w-100 d-flex my-5 justify-center">
                    <div class="img-bg">
                        <img
                            src="@/public/assets/granny_logo.png"
                            class="img"
                            alt=""
                        />
                    </div>
                </div>

                <v-divider
                    class="divider opacity-100"
                    :thickness="5"
                ></v-divider>
                <v-list>
                    <v-btn
                        variant="plain"
                        class="text-accent w-100 font-bold"
                        @click="newAnalysisAlert = !newAnalysisAlert"
                    >
                        New Analysis
                    </v-btn>
                    <v-btn
                        variant="plain"
                        class="text-accent w-100 font-bold"
                        @click="abortAlert = !abortAlert"
                    >
                        Abort
                    </v-btn>
                </v-list>
            </v-navigation-drawer>
        </v-layout>
    </div>
</template>
<style scoped>
.nav-menu {
    background-color: rgb(var(--v-theme-flesh));
    display: flex;
    flex-direction: column;
    align-items: center !important;
    justify-content: center !important;
}
.img {
    height: 200px;
    width: 200px;
}
.img-bg {
    height: 200px;
    width: 200px;
    border-radius: 50%;
    background-color: white;
    box-shadow: -2px 2px 2px rgb(var(--v-theme-accent));
}
</style>
