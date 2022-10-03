import './bootstrap';

import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import Routes from './routes.js';
import MenuWithRouterView from './App.vue';



const app = createApp(MenuWithRouterView);
//const app = createApp({});

const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
});

app.use(router);
app.mount('#app');