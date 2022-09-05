import ListEmployees from './components/employees/ListEmployees.vue';
import CreateEmployee from './components/employees/CreateEmployee.vue';

export default [
    {
        path: '/employees/list',
        name: 'employees.list',
        component: ListEmployees,
    },
    {
        path: '/employees/create',
        name: 'employees.create',
        component: CreateEmployee,
    },
]