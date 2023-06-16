import './bootstrap';
import './../sass/app.scss'

import {createApp} from 'vue';

import App from "./App.vue";
import router from './router'

const app = createApp(App);

app.use(router);

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

app.mount('#app');
