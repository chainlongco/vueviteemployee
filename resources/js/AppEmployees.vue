<template>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 d-none d-sm-inline">Chinamax</span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                        <li>
                            <!--<a href="#submenu1" data-bs-toggle="collapse" class="nav-link px-0 align-middle">-->
                            <a data-bs-toggle="collapse" class="nav-link px-0 align-middle">    
                                <i class="fs-4 bi-speedometer2"></i> <span class="ms-1 d-none d-sm-inline">Employees</span>
                            </a>
                            <ul class="collapse show nav flex-column ms-1" id="submenu1" data-bs-parent="#menu">
                                <li class="w-100">
                                    <router-link to="/employees/list" class="nav-link px-0"> <span class="d-none d-sm-inline"></span>List</router-link>
                                    <!--<a class="nav-link px-0" href="/employees/list">List</a>-->
                                </li>
                                <li>
                                    <!--<router-link to="/employees/create" :editMode=false class="nav-link px-0"> <span class="d-none d-sm-inline"></span>New</router-link>-->
                                    <router-link :to="{ name: 'employees.create' }" class="nav-link px-0"> <span class="d-none d-sm-inline"></span>New</router-link>
                                    <!--<a class="nav-link px-0" href="/employees/create">New</a>-->
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                        <!--<a href="#submenu0" data-bs-toggle="collapse" class="nav-link align-middle px-0">-->
                            <a data-bs-toggle="collapse" class="nav-link align-middle px-0">    
                                <i class="fs-4 bi-people"></i> 
                                    <span class="ms-1 d-none d-sm-inline" v-if="user">Login User: {{ user.name }}</span>
                            </a>
                            <ul class="collapse show nav flex-column ms-1" id="submenu0" data-bs-parent="#menu">
                                <li>
                                    <router-link to="/employees/listusers" class="nav-link px-0"> <span class="d-none d-sm-inline"></span>List</router-link>
                                    <!--<a class="nav-link px-0" href="/api/logout">Logout</a>-->
                                </li>
                                <li>
                                    <!--<router-link to="/users/register" class="nav-link px-0"> <span class="d-none d-sm-inline"></span>Register</router-link>-->
                                    <a class="nav-link px-0" href="/api/logout">Logout</a>
                                </li>
                            </ul>
                        </li>          
                    </ul>

                    <!-- Footer -->
                    <footer class="text-white px-3 pt-2">   
                        &copy; 2022 ChainLong 
                    </footer>
                    <!-- End of Footer -->
                </div>
            </div>
            <!--<div class="col py-3">
                Content area...
            </div>-->
            <div class="col py-3">
                <router-view></router-view>
                <!-- <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2">
                    <router-view></router-view>
                </div> -->
            </div>
        </div>
    </div>  
</template>

<script>
    import { mapGetters } from 'vuex';
    import { mapActions } from 'vuex';

    export default {
        name: 'App',
        computed: {
            ...mapGetters({
                user: 'auth/user',
            })
        },
        mounted() {
            this.retrieveSessionUser();
        },
        methods: {
            ...mapActions({
                loadUser: 'auth/loadUser'
            }),
            async retrieveSessionUser() {
                var userId = document.getElementById('userId').value;
                if (userId != "") {
                    let response = await axios.get('/api/user/' + userId);
                    this.loadUser(response.data);
                }
                
            },
        }
    };
</script>