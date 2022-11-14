import './bootstrap';

import { createApp } from 'vue';
import Vuex from "vuex";
import { createRouter, createWebHistory } from 'vue-router';
import Routes from './routesUsers.js';
import MenuWithRouterView from './App.vue';

const app = createApp(MenuWithRouterView);
//const app = createApp({});

const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
});

app.use(router, Vuex);
app.mount('#app');