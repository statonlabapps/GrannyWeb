<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
// Declare reactive state
const isMobile = ref(false);
// Mobile check function
const mobileCheck = () => {
    if (window.innerWidth <= 1000) {
        isMobile.value = true;
    } else {
        isMobile.value = false;
    }
};

// Lifecycle hooks
onMounted(() => {
    mobileCheck(); // Run initial check when component mounts
    window.addEventListener('resize', mobileCheck);
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', mobileCheck);
});
</script>

<template>
    <Head title="Welcome" />
    <v-sheet color="flesh" class="main">
        <v-toolbar
            density="compact"
            color="transparent"
            style="position: absolute; top: 0; left: 0; z-index: 1"
        >
            <v-spacer></v-spacer>
            <div v-if="canLogin">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')">
                    <v-btn variant="outlined" color="secondary" class="font">
                        Dashboard
                    </v-btn>
                </Link>

                <div v-else>
                    <Link :href="route('login')">
                        <v-btn
                            class="font mr-2"
                            variant="outlined"
                            color="secondary"
                        >
                            Log in
                        </v-btn>
                    </Link>

                    <Link v-if="canRegister" :href="route('register')">
                        <v-btn
                            class="font mr-2"
                            color="secondary"
                            variant="elevated"
                            v-if="canRegister"
                        >
                            Register
                        </v-btn>
                    </Link>
                </div>
            </div>
        </v-toolbar>
        <div class="main-header">
            <div class="logo-bg">
                <img
                    src="@/public/assets/granny_logo.png"
                    alt="Granny App Logo"
                    class="logo"
                />
            </div>
            <div class="text-h3 font title text-white">Granny</div>
        </div>
        <div class="d-flex w-100 flex-row">
            <div class="d-flex flex-column align-center w-50">
                <v-card class="welcome-card pa-5" color="white">
                    <v-card-title class="text-h4 text-accent font-bold"
                        >Welcome to Granny</v-card-title
                    >
                    <v-divider
                        class="divider border-opacity-100"
                        :thickness="5"
                    ></v-divider>
                    <v-card-text class="font text-accent">
                        Quality assessment of pome fruits (i.e. apples and
                        pears) is crucial for determining the optimal harvest
                        time, and the progression of fruit-quality attributes
                        during storage. This evaluation often includes careful
                        visual assessments of fruit for apparent defects and
                        physiological symptoms. A general best practice for
                        quality assessment is to rate fruit using the same
                        individual rater or group of individuals raters to
                        reduce bias.
                        <br />
                        <br />
                        Granny, is a designed for rating fruit using
                        machine-learning and image-processing to address rater
                        bias and improve resolution. Granny also provides
                        ratings compatible with long-established standards and
                        references, promoting continuity.
                        <br />
                        <br />
                        Development of Granny was funded by the Washington Tree
                        Fruit Research Commission under project AP-22-101A and
                        USDA ARS internal appropriation funds.
                    </v-card-text>
                    <v-card-actions class="d-flex align-center justify-end">
                        <v-btn
                            prepend-icon="mdi-file-document-multiple"
                            variant="elevated"
                            color="secondary"
                            class="font btn"
                            target="_blank"
                            href="https://granny.readthedocs.io/en/latest/index.html"
                            >View the Docs</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </div>
            <div
                class="d-flex flex-column justify-space-between align-center w-50"
            >
                <v-card class="mini-card px-2" color="white">
                    <div class="d-flex align-center">
                        <div class="d-flex flex-column">
                            <v-icon
                                class="bg-secondary pa-5 rounded-full"
                                size="large"
                                color="accent"
                                icon="mdi-clipboard-list"
                            ></v-icon>
                        </div>
                        <div class="d-flex w-100 flex-column">
                            <v-card-title class="text-accent font-bold"
                                >What Does Granny Provide?</v-card-title
                            >
                            <v-divider
                                class="divider border-opacity-100"
                                :thickness="5"
                            ></v-divider>

                            <v-card-text class="font text-accent">
                                Current Granny ratings include the following:
                                <ul style="list-style-type: disc">
                                    <li>Starch content assessment.</li>
                                    <li>Incidence of superficial scald.</li>
                                    <li>Pear color.</li>
                                    <li>Level of blush in pears.</li>
                                </ul>
                            </v-card-text>
                        </div>
                    </div>
                </v-card>
                <v-card class="mini-card px-2" color="white">
                    <div class="d-flex align-center">
                        <div class="d-flex flex-column">
                            <v-icon
                                class="bg-secondary pa-5 rounded-full"
                                size="large"
                                color="accent"
                                icon="mdi-percent"
                            ></v-icon>
                        </div>
                        <div class="d-flex w-100 flex-column">
                            <v-card-title class="text-accent font-bold"
                                >How Good is Granny?</v-card-title
                            >
                            <v-divider
                                class="divider border-opacity-100"
                                :thickness="5"
                            ></v-divider>

                            <v-card-text class="font text-accent">
                                All of Granny’s current rating modules show high
                                levels of concordance with expert human raters.
                                You can find specific results in the manuscript
                                listed in the Citing Granny section below.
                            </v-card-text>
                        </div>
                    </div>
                </v-card>
                <v-card class="mini-card px-2" color="white">
                    <div class="d-flex align-center">
                        <div class="d-flex flex-column">
                            <v-icon
                                size="large"
                                color="accent"
                                class="bg-secondary pa-5 rounded-full"
                                icon="mdi-pencil"
                            ></v-icon>
                        </div>
                        <div class="d-flex w-100 flex-column">
                            <v-card-title class="text-accent font-bold"
                                >Citing Granny</v-card-title
                            >
                            <v-divider
                                class="divider border-opacity-100"
                                :thickness="5"
                            ></v-divider>

                            <v-card-text class="font text-accent">
                                If you would like to cite Granny, please use the
                                following: Nhan H. Nguyen, Joseph Michaud, Rene
                                Mogollon, Huiting Zhang, Heidi Hargarten, Rachel
                                Leisso, Carolina A. Torres, Loren Honaas,
                                Stephen Ficklin. Rating Pome Fruit Quality
                                Traits Using Deep Learning and Image Processing.
                                bioRxiv 2024.04.03.588000
                            </v-card-text>
                        </div>
                    </div>
                </v-card>
            </div>
        </div>
    </v-sheet>
</template>

<style scoped>
.btn {
    color: rgb(var(--v-theme-accent)) !important;
}

.main-header {
    background-image: url('@/public/assets/orchard.png');
    background-position: center;
    background-size: cover;
    width: 100%;
    height: 250px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin-bottom: 8px;
}

.main {
    min-height: 100vh;
    width: 100vw;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-bottom: 8px;
}
.logo {
    height: 150px;
    width: 150px;
}
.logo-bg {
    height: 150px;
    width: 150px;
    border-radius: 50%;
    background-color: white;
    box-shadow: -4px 4px 4px black;
}
.title {
    text-shadow: -4px 4px 4px black;
}
.welcome-card {
    height: 100%;
    width: calc(100% - 12px);
    margin-left: 8px;
    margin-right: 4px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.mini-card {
    height: fit-content;
    width: calc(100% - 12px);
    margin-left: 4px;
    margin-right: 8px;
}
.mini-card:not(:last-child) {
    margin-bottom: 8px;
}
ul {
    padding-left: 15px;
}
</style>
