import LoginUsers from './components/users/Login.vue';
import RegisterUsers from './components/users/Register.vue';

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
]