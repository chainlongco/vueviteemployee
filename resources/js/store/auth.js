import axios from 'axios';

export default {
    namespaced: true,
    state: {
        user: null,
    },
    getters: {
      user(state) {
        return state.user;
      }
    },
    mutations: {
        SET_USER (state, user) {
            state.user = user;
        }
    },
    actions: {
        async signIn({ dispatch, commit }, credentials) {
            try {
                let response = await axios.post('/api/login', credentials);
                dispatch('loadUser', response.data.user);
                return response.data;
            } catch (e) {
                commit('SET_USER', null);
            }
        },
        async loadUser({ commit }, user) {
            //alert('Auth: ' + user.name);
            commit('SET_USER', user);
        },
    }
}