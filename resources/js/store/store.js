import axios from 'axios';
//import Vue from 'vue';
//import Vuex from 'vuex';
import { createStore } from 'vuex';
import auth from './auth';

//Vue.use(Vuex);

//export default new Vuex.Store({
export default createStore({
    state: {
        users: {},
    },
    mutations: {
        //set_users: (state, data) => {
        //    state.users = data
        //}
    },
    actions: {
        /*get_users: (context) => {
            axios.get('api/users').then((response) => {
                context.commit('set_users', response.data)
            }).catch((errors) => {
                console.log(errors)
            });
        }*/
    },
    modules: {
        auth
    }
})