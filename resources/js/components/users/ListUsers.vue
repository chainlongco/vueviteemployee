<template>
    <div class="container">
        <br>
        <h2 class="text-center">All Users</h2>
        <br>
        <div id="useralert"></div>
        <div id="userslist">
            <table class="table table-striped table-hover cell-border" id="usersDatatable" style="padding: 10px;">
                <thead>
                    <tr>
                        <th rowspan="2" class="align-middle text-center">Name</th>
                        <th rowspan="2" class="align-middle text-center">Email</th>
                        <th colspan="3" class="text-center">Roles</th>
                        <th rowspan="2" class="align-middle text-center">Actions</th>
                    </tr>
                    <tr>
                        <th class="text-center">Admin</th>
                        <th class="text-center">Manager</th>
                        <th class="text-center">Employee</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(user, index) in users_data" :key="index">
                        <td class="align-middle">{{ user.name }}</td>
                        <td class="align-middle">{{ user.email }}</td>
                        <td class="align-middle text-center" >
                            <input type="checkbox" class="roleadmin" :id="`roleadmin` + user.id" style="height:20px; width:20px;" :checked="user.admin">
                            <!--<input type="checkbox" class="roleadmin" :id="`roleadmin` + user.id" style="height:20px; width:20px;" v-else>-->
                        </td>
                        <td class="align-middle text-center">
                            <input type="checkbox" class="rolemanager" :id="(`rolemanager` + user.id)" style="height:20px; width:20px;" :checked="user.manager">
                            <!--<input type="checkbox" class="rolemanager" :id="(`rolemanager` + user.id)" style="height:20px; width:20px;" v-else>-->
                        </td>
                        <td class="align-middle text-center">
                            <input type="checkbox" class="roleemployee" :id="`roleemployee` + user.id" style="height:20px; width:20px;" :checked="user.employee">
                            <!--<input type="checkbox" class="roleemployee" :id="`roleemployee` + user.id" style="height:20px; width:20px;" v-else>-->
                        </td>
                        <td>
                            <div class="row justify-content-around" style="margin:auto;">
                                <!--<a href="" :id="`usersave` + user.id" class="col-md-5 btn btn-primary usersave" title="Save"><span class="bi bi-save"></span></a>-->
                                <button @click="editUser(user.id)" type="button" class="col-md-5 btn btn-primary usersave"><span class="bi bi-save"></span></button>
                                <!--<a href="" @click="deleteUser(user.id)" :id="`userdelete` + user.id" class="col-md-5 btn btn-danger userdelete" title="Delete"><span class="bi-x-lg"></span></a>-->
                                <button @click="deleteUser(user.id)" type="button" class="col-md-5 btn btn-danger userdelete"><span class="bi-x-lg"></span></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Admin</th>
                        <th class="text-center">Manager</th>
                        <th class="text-center">Employee</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ListUser',
        data() {
            return {
                users_data: [],
                userListError: false,
            }        
        },
        mounted() {
            this.getUsers();
        },
        methods: {
            async getUsers() {
                $("#usersDatatable").DataTable().destroy();
                await axios.get('/api/usersWithRoles')
                    .then(response => {
                        this.users_data = response.data
                        //alert("go");
                        setTimeout(() => {
                            $(document).ready(function(){
                                document.getElementById("usersDatatable").style.visibility = "visible";
                                if (!($.fn.dataTable.isDataTable('#usersDatatable'))) { // if datatable has not been initilized
                                    $("#usersDatatable").DataTable({
                                        scrollCollapse: true,
                                        "columnDefs": [
                                            {targets: [2,3,4,5], orderable: false},
                                        ]
                                    });
                                }
                                document.getElementById("userslist").style.visibility = "visible";
                            });
                        }, 0);
                    })
                    .catch(error => {
                        document.getElementById("userslist").style.visibility = "visible";
                        console.log(error);
                        this.userListError = error;
                    })
            },
            async deleteUser(id) {
                if(confirm("Are you sure to delete?")){
                    try {
                        let response = await axios.post('/api/users/delete/'+id)
                        let status = await (this.getSubmitStatus(response));
                        if (status == 1) {  
                            //message += '<div class="alert alert-success">';
                            let msg = await (this.getSubmitMessage(response));
                            alert(msg);
                            var baseurl = window.location.protocol + "//" + window.location.host;
                            var url = baseurl + '/employees/listusers';
                            document.location.href=url;
                        } else if (status == 0) {
                            var message = "";
                            message += '<div class="alert alert-danger">';
                            let msg = await (this.getSubmitMessage(response));
                            message += msg;
                            message += '</div>';
                            $('#useralert').html(message);
                        }
                    } catch (e){
                        console.log(e);
                        var message = "";
                        message += '<div class="alert alert-danger">';
                        message += e.message;
                        message += '</div>';
                        $('#useralert').html(message);
                    } 
                }
            },
            async getSubmitStatus(response) {
                const status = (await response.data).status;
                return status;
            },
            async getSubmitMessage(response) {
                const message = (await response.data).msg;
                return message;
            },
            async editUser(id) {
                //alert('id: ' + id);
                var admin = $('#roleadmin' + id).is(":checked");
                //alert('admin: ' + admin);
                var manager = $('#rolemanager' + id).is(":checked");
                //alert('manager: ' + manager);
                var employee = $('#roleemployee' + id).is(":checked");
                //alert('employee: ' + employee);
                try {
                    const data = new FormData();
                    data.append('id', id);
                    data.append('admin', admin);
                    data.append('manager', manager);
                    data.append('employee', employee);
                    let response = await axios.post('/api/users/edit', data);
                    let status = await (this.getSubmitStatus(response));
                    if (status == 1) {
                        var message = "";
                        message += '<div class="alert alert-success">';
                        let msg = await (this.getSubmitMessage(response));
                        message += msg;
                        message += '</div>';
                        $('#useralert').html(message);
                        //var baseurl = window.location.protocol + "//" + window.location.host;
                        //var url = baseurl + '/employees/listusers';
                        //document.location.href=url;
                    } else if (status == 0) {
                        var message = "";
                        message += '<div class="alert alert-danger">';
                        let msg = await (this.getSubmitMessage(response));
                        message += msg;
                        message += '</div>';
                        $('#useralert').html(message);
                    }
                } catch (e){
                    console.log(e);
                    var message = "";
                    message += '<div class="alert alert-danger">';
                    message += e.message;
                    message += '</div>';
                    $('#useralert').html(message);
                } 
            }
            /*$(document).on('click', '.usersave', function(e){
                e.preventDefault();
                var id = retrieveId("usersave", this.id);
                var admin = $('#roleadmin' + id).is(":checked");
                var manager = $('#rolemanager' + id).is(":checked");
                var employee = $('#roleemployee' + id).is(":checked");
                $.ajax({
                    type:'GET',
                    url:'/user-edit',
                    data: {'id':id, 'admin': admin, 'manager':manager, 'employee':employee},
                    success: function(response) {
                        //swal(response.msg);
                        alert(response.msg);
                    }
                });
            });*/
        },
    }
    
</script>