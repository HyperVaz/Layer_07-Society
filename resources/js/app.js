import './bootstrap';
import {createApp} from 'vue';
import App from "./App.vue";
import Footer from "./Footer.vue";
import router from './router';

const app = createApp(App);
app.use(router)
app.component('Footer', Footer);
app.mount('#app');
