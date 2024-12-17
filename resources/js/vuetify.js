// Vuetify
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import 'vuetify/styles';

const customTheme = {
    dark: false,
    colors: {
        primary: '#94D33Cff',
        secondary: '#C0E047ff',
        tertiary: '#d2f4c6',
        flesh: '#f8f0da',
        accent: '#654a09',
        error: '#FF5252',
        info: '#2196F3',
        success: '#4CAF50',
        warning: '#FFC107',
    },
};

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'customTheme',
        themes: {
            customTheme,
        },
    },
});

export default vuetify;
