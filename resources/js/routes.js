import ListEmployees from './components/employees/ListEmployees.vue';
import CreateEditEmployee from './components/employees/CreateEditEmployee.vue';

export default [
    {
        path: '/employees/list',
        name: 'employees.list',
        component: ListEmployees,
    },
    {
        path: '/employees/create',
        name: 'employees.create',
        component: CreateEditEmployee,
    },
    {
        path: "/employees/:id",
        name: "employees.create",
        component: CreateEditEmployee,
    }
]