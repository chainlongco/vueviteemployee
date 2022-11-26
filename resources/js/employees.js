import './bootstrap';

import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import Routes from './routesEmployees.js';
import MenuWithRouterView from './AppEmployees.vue';
import store from './store/store.js';


const app = createApp(MenuWithRouterView);
//const app = createApp({});

const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
});

app.use(router);
app.use(store);
app.mount('#app');