import LoginUsers from './components/users/Login.vue';
import RegisterUsers from './components/users/Register.vue';
import RestrictedUsers from './components/users/Restricted.vue';
import PageNotFound from './components/PageNotFound.vue';

export default [
    {
        path: '/users/login',
        name: 'users.login',
        component: LoginUsers,
    },
    {
        path: '/users/register',
        name: 'users.register',
        component: RegisterUsers,
    },
    {
        path: '/users/restricted',
        name: 'users.restricted',
        component: RestrictedUsers,
    },
    {
        path: '/users/:pathMatch(.*)*',
        name: 'pagenotfound',
        component: PageNotFound,
    },
]