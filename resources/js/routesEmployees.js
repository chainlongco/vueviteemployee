import ListEmployees from './components/employees/ListEmployees.vue';
import CreateEditEmployee from './components/employees/CreateEditEmployee.vue';

//let isEdit = false;

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
        props: route => ({ isEdit: false }),
        /*beforeEnter (to, from, next) {
            if ((from.name === 'employees.edit') && (to.name === 'employees.create')) {
                //isEdit = false;
                alert('to create');
                //window.location.reload();
                next();
            }
            //next();
          }*/
    },
    {
        path: "/employees/:id",
        name: "employees.edit",
        component: CreateEditEmployee,
        props: route => ({ isEdit: true }),
        /*beforeEnter (to, from, next) {
            if (to.name == 'employees.edit') {
                isEdit = true;
                alert('to edit');
                next();
            }
            
          }*/
    },
]