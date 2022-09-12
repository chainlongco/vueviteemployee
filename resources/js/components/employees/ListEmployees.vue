<template>
    <div class="card" id="employeeCard" style="visibility: hidden;">
        <div class="card-header text-center">
            <h3>Employees Information<button @click="addEmployee" type="button" class="btn btn-primary" style="float:right;">Add New Employee</button></h3>
        </div>
        <div class="card-body">
            <div class='table-responsive'> <!-- to solve Jquery Datatables issue when resizing - columns not lineup, 
                https://stackoverflow.com/questions/61557675/jquery-datatables-issue-when-resizing-->
                <table class="table table-striped table-hover cell-border" id="employeesDatatable" style="padding: 10px; visibility: hidden;">
                    <thead>
                        <tr style="border-top: 1px solid #000;">
                            <th class="text-center">Photo</th>
                            <th class="text-center">First Name</th>
                            <th class="text-center">Last Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Position</th>
                            <!-- <th class="text-center">Address</th>
                            <th class="text-center">Address 2</th>
                            <th class="text-center">City</th>
                            <th class="text-center">State</th>
                            <th class="text-center">Zip</th> -->
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(employee, index) in employees_data" :key="index">
                            <td class="align-middle text-center">
                                <!-- <div class="" style="max-height:100px; width:100px;"> -->
                                    <img :src="retrieveEmployeePhoto(employee.img)" alt="" class="img-fluid rounded"  style="max-height:100px;">
                                <!-- </div> -->
                            </td>
                            <td class="align-middle"> {{ employee.first_name }} </td>
                            <td class="align-middle"> {{ employee.last_name }} </td>
                            <td class="align-middle"> {{ employee.email }} </td>
                            <td class="align-middle"> {{ employee.phone }} </td>
                            <td class="align-middle"> {{ employee.position }} </td>
                            <!-- <td class="align-middle"> {{ employee.address }} </td>
                            <td class="align-middle"> {{ employee.address2}} </td>
                            <td class="align-middle"> {{ employee.city }} </td>
                            <td class="align-middle"> {{ employee.state }} </td>
                            <td class="align-middle"> {{ employee.zip }} </td> -->
                            <td class="align-middle">
                                <div class="row justify-content-around" style="margin:auto;">
                                    <a href="#" @click="editEmployee(employee.id)" class="col-md-5 btn btn-primary" title="Edit"><span class="bi-pencil-fill"></span></a>
                                    <a href="#" @click="deleteEmployee(employee.id)" class="col-md-5 btn btn-danger" title="Delete" onclick="if(!confirm('Are you sure?')){return false;}"><span class="bi-x-lg"></span></a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-center">Photo</th>
                            <th class="text-center">First Name</th>
                            <th class="text-center">Last Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Position</th>
                            <!-- <th class="text-center">Address</th>
                            <th class="text-center">Address 2</th>
                            <th class="text-center">City</th>
                            <th class="text-center">State</th>
                            <th class="text-center">Zip</th> -->
                            <th class="text-center">Actions</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
        
    <div class="modal fade" id="employeeFormModal" data-toggle="modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span v-if="editing">Edit Employee</span>
                        <span v-else>Add New Employee</span>
                    </h5>
                    <button @click="hideModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form enctype="multipart/form-data">
                <!-- <Form ref="form" @submit="handleSubmit" :validation-schema="editing ? editUserSchema : createUserSchema" v-slot="{ errors }" :initial-values="formValues"> -->
                    <div class="modal-body">
                        <input v-model="form.id" class="form-control" type="hidden"/>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="firstName">First Name</label>
                                <!-- <input v-model="form.firstName" name="firstName" type="text" class="form-control" :class="{'is-invalid': errors.firstName }" id="firstName"
                                    aria-describedby="firstameHelp" placeholder="Enter first name" /> -->
                                <input v-model="form.first_name" name="firstName" type="text" class="form-control" id="firstName"
                                    aria-describedby="firstameHelp" placeholder="Enter first name" />    
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                            <div class="col-md-4">
                                <label for="middleName">Middle Name</label>
                                <input v-model="form.middle_name" name="middleName" type="text" class="form-control" id="middleName"
                                    aria-describedby="middleNameHelp" placeholder="Enter middle name" />
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                            <div class="col-md-4">
                                <label for="lastName">Last Name</label>
                                <input v-model="form.last_name" name="lastName" type="text" class="form-control" id="lastName"
                                    aria-describedby="lastNameHelp" placeholder="Enter last name" />
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="email">Email</label>
                                <input v-model="form.email" name="email" type="email" class="form-control " id="email"
                                    aria-describedby="emailHelp" placeholder="Enter email" />
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                            <div class="col-md-4">
                                <label for="phone">Phone</label>
                                <input v-model="form.phone" name="phone" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false" class="form-control " id="phone"
                                    aria-describedby="phoneHelp" placeholder="Enter phone" />
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                            <div class="col-md-4">
                                <label for="birthday">Birthday</label>
                                <input v-model="form.birthday" name="birthday" type="date" class="form-control " id="birthday"
                                    aria-describedby="birthdayHelp" placeholder="Enter birthday" />
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="ssn">SSN</label>
                                <input v-model="form.ssn" name="ssn" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false" class="form-control " id="ssn"
                                    aria-describedby="ssnHelp" placeholder="Enter SSN" />
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                            <div class="col-md-3">
                                <div>
                                    <p>Gender
                                        <select v-model="form.gender" id="gender" style="width: 100%; margin: 0px 0px; height: 37px; border: 1px solid #ced4da; border-radius: .375rem; padding: 0.375rem 0.75rem;">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="position">Position</label>
                                <input v-model="form.position" name="position" type="text" class="form-control " id="position"
                                    aria-describedby="positionHelp" placeholder="Enter position" />
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                            <div class="col-md-3">
                                <label for="salary">Salary</label>
                                <input v-model="form.salary" name="salary" type="text" class="form-control " id="salary"
                                    aria-describedby="salaryHelp" placeholder="Enter salary" />
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-7">
                                <label for="address">Address</label>
                                <input v-model="form.address" name="address" type="text" class="form-control " id="address"
                                    aria-describedby="addressHelp" placeholder="Enter address" />
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                            <div class="col-md-5">
                                <label for="address2">Address2</label>
                                <input v-model="form.address2" name="address2" type="text" class="form-control " id="address2"
                                    aria-describedby="address2Help" placeholder="Enter address2" />
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="city">City</label>
                                <input v-model="form.city" name="city" type="text" class="form-control " id="city"
                                    aria-describedby="cityHelp" placeholder="Enter city" />
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                            <div class="col-md-4">
                                <label for="state">State</label>
                                <input v-model="form.state" name="state" type="text" class="form-control " id="state"
                                    aria-describedby="stateHelp" placeholder="Enter state" />
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                            <div class="col-md-4">
                                <label for="zip">Zip Code</label>
                                <input v-model="form.zip" name="zip" type="text" class="form-control "  id="zip"
                                    aria-describedby="zipHelp" placeholder="Enter zip code" />
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="startDate">Hire Date</label>
                                <input v-model="form.start_date" name="startDate" type="date" class="form-control " id="startDate"
                                    aria-describedby="startDateHelp" placeholder="Enter hire date" />
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                            <div class="col-md-3">
                                <label for="endDate">Date Leave</label>
                                <input v-model="form.end_date" name="endDate" type="date" class="form-control " id="endDate"
                                    aria-describedby="endDateHelp" placeholder="Enter leave date" />
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                            <div class="col-md-4">
                                <label for="img">Image</label>
                                <input name="img" type="file" @change="imageSelected" class="form-control" id="img"
                                    aria-describedby="imgHelp" />
                                <span class="invalid-feedback">{{  }}</span>
                            </div>
                            <div class="col-md-2">
                                <div v-if="form.img" class="mt-2">
                                    <!-- <img :src="imagePreview" alt="Image File not Found" class="figure-img img-fluid rounded"  style="max-height:100px;"> -->
                                    <img :src="imagePreview" alt="Image File not Found" class="figure-img img-fluid rounded"  style="max-height:100px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button @click="hideModal" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button @click="handleSubmit" type="button" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>    
</template>
<script>
    import axios from 'axios';
    
    //import $ from "jquery";
    //import $ from "jquery/dist/jquery.min.js"

    //import "jquery/dist/jquery.min.js";
    //import "bootstrap/dist/css/bootstrap.min.css";
    //import "datatables.net-dt/js/dataTables.dataTables.js";
    //import "datatables.net-dt/css/jquery.dataTables.min.css";
    //import "datatables.net-dt/js/dataTables.dataTables";
    //import "datatables.net-dt/css/jquery.dataTables.min.css";
    //import axios from "axios";
    //import $ from "jquery";
    import {reactive, ref} from 'vue'

    export default {
        data() {
            return {
                editing: false,
                employees_data: [],
                'form': {
                    'id':'', 'first_name':'', 'middle_name':'', 'last_name':'', 'email':'', 'phone':'', 'birthday':'', 'ssn':'', 'gender':'Male', 'position':'', 'salary':'', 'address':'', 'address2':'', 'city':'', 'state':'', 'zip':'', 'img':'', 'start_date':'', 'end_date':''
                },
                imagePreview: null,
                image: null
            }        
        },
        created() {
            //this.getEmployees();
        },
        mounted() {
            this.getEmployees();
        },
        methods: {
            getEmployees() {
                axios.get('/api/employees')
                    .then(response => {
                        this.employees_data = response.data
                        setTimeout(() => {
                            $(document).ready(function(){
                                document.getElementById("employeesDatatable").style.visibility = "visible";
                                if (!($.fn.dataTable.isDataTable('#employeesDatatable'))) { // if datatable has not been initilized
                                    $("#employeesDatatable").DataTable({
                                        scrollCollapse: true,
                                        "columnDefs": [
                                            {targets: [6], orderable: false},
                                            {targets: [6], width: "150px"}
                                        ]
                                    });
                                }
                                document.getElementById("employeeCard").style.visibility = "visible";
                            });
                        }, 0);
                    }).catch(error => {
                        console.log(error);
                    })
            },
            addEmployee() {
                $('#employeeFormModal').modal('show');
            },
            handleSubmit() {
                // Method 1 (Passing image as an object):
                let data = new FormData;
                data.append('image', this.image);
                data.append('id', this.form.id);
                data.append('first_name', this.form.first_name);
                data.append('middle_name', this.form.middle_name);
                data.append('last_name', this.form.last_name);
                data.append('email', this.form.email);
                data.append('phone', this.form.phone);
                data.append('birthday', this.form.birthday);
                data.append('ssn', this.form.ssn);
                data.append('gender', this.form.gender);
                data.append('position', this.form.position);
                data.append('salary', this.form.salary);
                data.append('address', this.form.address);
                data.append('address2', this.form.address2);
                data.append('city', this.form.city);
                data.append('state', this.form.state);
                data.append('zip', this.form.zip);
                data.append('start_date', this.form.start_date);
                data.append('end_date', this.form.end_date);
                axios.post('/api/employees', data)

                // Method 2 (Passing img as a string): 
                //axios.post('/api/employees', this.form)


                    .then(response => {
                        this.clearForm();
                        this.hideModal();
                        this.getEmployees();
                    }).catch(error => {
                        console.log(error);
                        this.clearForm();
                    })
            },
            clearForm() {
                this.form.id = '';
                this.form.first_name = '';
                this.form.middle_name = '';
                this.form.last_name = '';
                this.form.email = '';
                this.form.phone = '';
                this.form.birthday = '';
                this.form.ssn = '';
                this.form.gender = 'Male';
                this.form.position = '';
                this.form.salary = '';
                this.form.address = '';
                this.form.address2 = '';
                this.form.city = '';
                this.form.state = '';
                this.form.zip = '';
                this.form.img = '';
                this.form.start_date = '';
                this.form.end_date = '';
                $("#img").val('');
                //document.getElementById("img").value = '';
            },
            hideModal() {
                this.clearForm();
                $('#employeeFormModal').modal('hide');
            },
            imageSelected(e){
                /* There are two methods to pass the image file:
                    1. file object: need to use: let data = new FormData; and pass data into axios. Needs to add this to form html: enctype="multipart/form-data"
                    2. file path
                    Needs to Install Intervention Image Package -- using composer require intervention/image in https://appdividend.com/2022/02/28/laravel-image-intervention/
                */
                // Method 1 (Passing image as an object):
                this.image = e.target.files[0];
                this.form.img = e.target.files[0];
                let reader = new FileReader();
                reader.readAsDataURL(this.image);
                reader.onload = e => {
                    this.imagePreview = e.target.result;
                    //alert(this.imagePreview);
                };
                // Method 2 (Passing img as a string):
                /*this.image = e.target.files[0];
                let reader = new FileReader();
                reader.readAsDataURL(this.image);
                reader.onload = e => {
                    this.form.img = e.target.result;
                    this.imagePreview = e.target.result;
                };*/
            },
            deleteEmployee(id) {
                axios.post('/api/employees/delete/' + id)
                    .then(response => {
                        this.clearForm();
                        this.hideModal();
                        this.getEmployees();
                    }).catch(error => {
                        console.log(error);
                        this.clearForm();
                    })
            },
            editEmployee(id) {
                axios.get('/api/employees/edit/' + id)
                    .then(response => {
                        this.editing = true;
                        this.form.id = response.data.id;
                        this.form.first_name = response.data.first_name;
                        this.form.middle_name = response.data.middle_name;
                        this.form.last_name = response.data.last_name;
                        this.form.email = response.data.email;
                        this.form.phone = response.data.phone;
                        this.form.birthday = response.data.birthday;
                        this.form.ssn = response.data.ssn;
                        this.form.gender = response.data.gender;
                        this.form.position = response.data.position;
                        this.form.salary = response.data.salary;
                        this.form.address = response.data.address;
                        this.form.address2 = response.data.address2;
                        this.form.city = response.data.city;
                        this.form.state = response.data.state;
                        this.form.zip = response.data.zip;
                        this.form.img = response.data.img;
                        // https://stackoverflow.com/questions/66419471/vue-3-vite-dynamic-img-src
                        this.imagePreview = new URL('/public/images/' + this.form.img, import.meta.url);
                        //alert(this.imagePreview);
                        this.form.start_date = response.data.start_date;
                        this.form.end_date = response.data.end_date;

                        $('#employeeFormModal').modal('show');
                    }).catch(error => {
                        console.log(error);
                    })
            },
            retrieveEmployeePhoto(employeeImg) {
                if (employeeImg) {
                    return new URL('/public/images/' + employeeImg, import.meta.url);
                } else {
                    return '';
                }               
            },
        }
    }
</script>
<style scoped>
    #gender:focus {
        border-color: #dfe2e6;
        box-shadow: 0 0 0 0.3rem rgba(9, 138, 243, 0.25);
    }
</style>
