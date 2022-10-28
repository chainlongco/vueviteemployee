<template>   
    <div class="card" id="employeeCard">
        <div class="card-header text-center">
            <h3>
                <span v-if="isEdit">Edit Employee</span>
                <span v-else>Add New Employee</span>
                <router-link :to="{ name: 'employees.list' }" class="btn btn-primary" style="float:right;">Employee List</router-link>
            </h3>
            <!-- <h3>Add New Employee<router-link :to="{ name: 'employees.list' }" class="btn btn-primary" style="float:right;">Employee List</router-link></h3> -->
        </div>
        <div class="card-body">
            <div class="">
                <form enctype="multipart/form-data">
                    <div class="">
                        <input v-model="formData.id" class="form-control" type="hidden"/>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="firstName">First Name</label>
                                <!-- <input v-model="form.firstName" name="firstName" type="text" class="form-control" :class="{'is-invalid': errors.firstName }" id="firstName"
                                    aria-describedby="firstameHelp" placeholder="Enter first name" /> -->
                                <input v-model="formData.first_name" name="firstName" type="text" class="form-control" id="firstName" @keydown="clearServerError('first_name')" @blur="validate('first_name')" @keypress="validate('first_name')"/>    
                                <span class="text-danger" v-if="hasServerError('first_name')">{{ getServerError('first_name') }}</span>
                                <span class="text-danger" v-if="hasClientError('first_name')">{{ getClientError('first_name') }}</span>
                            </div>
                            <div class="col-md-4">
                                <label for="middleName">Middle Name</label>
                                <input v-model="formData.middle_name" name="middleName" type="text" class="form-control" id="middleName"/>
                            </div>
                            <div class="col-md-4">
                                <label for="lastName">Last Name</label>
                                <input v-model="formData.last_name" name="lastName" type="text" class="form-control" id="lastName" @keydown="clearServerError('last_name')" @blur="validate('last_name')" @keypress="validate('last_name')"/>
                                <span class="text-danger" v-if="hasServerError('last_name')">{{ getServerError('last_name') }}</span>
                                <span class="text-danger" v-if="hasClientError('last_name')">{{ getClientError('last_name') }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="email">Email</label>
                                <input v-model="formData.email" name="email" type="email" class="form-control" id="email" @keydown="clearServerError('email')" @blur="validate('email')" @keypress="validate('email')"/>
                                <span class="text-danger" v-if="hasServerError('email')">{{ getServerError('email') }}</span>
                                <span class="text-danger" v-if="hasClientError('email')">{{ getClientError('email') }}</span>
                            </div>
                            <div class="col-md-4">
                                <label for="phone">Phone</label>
                                <input v-model="formData.phone" name="phone" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false" class="form-control" id="phone" @keydown="clearServerError('phone')" @blur="validateAndFormatPhone" @keypress="validate('phone')"/>
                                <span class="text-danger" v-if="hasServerError('phone')"> {{getServerError('phone')}}</span>
                                <span class="text-danger" v-if="hasClientError('phone')">{{ getClientError('phone') }}</span>
                            </div>
                            <div class="col-md-4">
                                <label for="birthday">Birthday</label>
                                <input v-model="formData.birthday" name="birthday" type="date" class="form-control" id="birthday" @keydown="clearServerError('birthday')" @change="calendarChange('birthday')" @blur="validate('birthday')" @keypress="validate('birthday')"/>
                                <span class="text-danger" v-if="hasServerError('birthday')">{{ getServerError('birthday') }}</span>
                                <span class="text-danger" v-if="hasClientError('birthday')">{{ getClientError('birthday') }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="ssn">SSN</label>
                                <input v-model="formData.ssn" name="ssn" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" onpaste="return false" class="form-control" id="ssn" @keydown="clearServerError('ssn')" @blur="validateAndFormatSSN" @keypress="validate('ssn')"/>
                                <span class="text-danger" v-if="hasServerError('ssn')">{{ getServerError('ssn') }}</span>
                                <span class="text-danger" v-if="hasClientError('ssn')">{{ getClientError('ssn') }}</span>
                            </div>
                            <div class="col-md-3">
                                <div>
                                    <p>Gender
                                        <select v-model="formData.gender" id="gender" style="width: 100%; margin: 0px 0px; height: 37px; border: 1px solid #ced4da; border-radius: .375rem; padding: 0.375rem 0.75rem;" @keydown="clearServerError('gender')" @blur="validate('gender')" @keypress="validate('gender')">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <span class="text-danger" v-if="hasServerError('gender')">{{ getServerError('gender') }}</span>
                                        <span class="text-danger" v-if="hasClientError('gender')">{{ getClientError('gender') }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="position">Position</label>
                                <input v-model="formData.position" name="position" type="text" class="form-control" id="position" @keydown="clearServerError('position')" @blur="validate('position')" @keypress="validate('position')"/>
                                <span class="text-danger" v-if="hasServerError('position')">{{ getServerError('position') }}</span>
                                <span class="text-danger" v-if="hasClientError('position')">{{ getClientError('position') }}</span>
                            </div>
                            <div class="col-md-3">
                                <label for="salary">Salary</label>
                                <input v-model="formData.salary" name="salary" type="number" inputmode="decimal" step="0.01" class="form-control" id="salary" @keydown="clearServerError('salary')" @blur="validate('salary')" @keypress="validate('salary')"/>
                                <span class="text-danger" v-if="hasServerError('salary')">{{ getServerError('salary') }}</span>
                                <span class="text-danger" v-if="hasClientError('salary')">{{ getClientError('salary') }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-7">
                                <label for="address">Address</label>
                                <input v-model="formData.address" name="address" type="text" class="form-control" id="address" @keydown="clearServerError('address')" @blur="validate('address')" @keypress="validate('address')"/>
                                <span class="text-danger" v-if="hasServerError('address')">{{ getServerError('address') }}</span>
                                <span class="text-danger" v-if="hasClientError('address')">{{ getClientError('address') }}</span>
                            </div>
                            <div class="col-md-5">
                                <label for="address2">Address2</label>
                                <input v-model="formData.address2" name="address2" type="text" class="form-control" id="address2"/>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="city">City</label>
                                <input v-model="formData.city" name="city" type="text" class="form-control" id="city" @keydown="clearServerError('city')" @blur="validate('city')" @keypress="validate('city')"/>
                                <span class="text-danger" v-if="hasServerError('city')">{{ getServerError('city') }}</span>
                                <span class="text-danger" v-if="hasClientError('city')">{{ getClientError('city') }}</span>
                            </div>
                            <div class="col-md-4">
                                <p>State
                                    <select v-model="formData.state" id="state" style="width: 100%; margin: 0px 0px; height: 37px; border: 1px solid #ced4da; border-radius: .375rem; padding: 0.375rem 0.75rem;" @keydown="clearServerError('state')" @blur="validate('state')" @keypress="validate('state')">
                                        <option v-for="(state, index) in states" :key="index" :selected="formData.state === state.abbrivation">{{ state.name }}</option>
                                    </select>
                                    <span class="text-danger" v-if="hasServerError('state')">{{ getServerError('state') }}</span>
                                    <span class="text-danger" v-if="hasClientError('state')">{{ getClientError('state') }}</span>
                                </p>    
                            </div>
                            <div class="col-md-4">
                                <label for="zip">Zip Code</label>
                                <input v-model="formData.zip" name="zip" type="text" class="form-control" id="zip" onkeypress="return event.charCode >= 48 && event.charCode <= 57" @keydown="clearServerError('zip')" @blur="validateAndFormatZipCode" @keypress="validate('zip')"/>
                                <span class="text-danger" v-if="hasServerError('zip')">{{ getServerError('zip') }}</span>
                                <span class="text-danger" v-if="hasClientError('zip')">{{ getClientError('zip') }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="startDate">Hire Date</label>
                                <input v-model="formData.start_date" name="startDate" type="date" class="form-control" id="startDate" @keydown="clearServerError('start_date')" @change="calendarChange('start_date')" @blur="validate('start_date')" @keypress="validate('start_date')"/>
                                <span class="text-danger" v-if="hasServerError('start_date')">{{ getServerError('start_date') }}</span>
                                <span class="text-danger" v-if="hasClientError('start_date')">{{ getClientError('start_date') }}</span>
                            </div>
                            <div class="col-md-3">
                                <label for="endDate">Date Leave</label>
                                <input v-model="formData.end_date" name="endDate" type="date" class="form-control" id="endDate"/>
                            </div>
                            <div class="col-md-4">
                                <label for="img">Image</label>
                                <input name="img" type="file" @change="imageSelected" class="form-control" id="img"/>
                            </div>
                            <div class="col-md-2">
                                <div v-if="formData.img" class="mt-2">
                                    <!-- <img :src="imagePreview" alt="Image File not Found" class="figure-img img-fluid rounded"  style="max-height:100px;"> -->
                                    <img :src="imagePreview" alt="Image File not Found" class="figure-img img-fluid rounded"  style="max-height:100px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="float: right">
                        <router-link :to="{ name: 'employees.list' }" class="btn btn-secondary">Cancel</router-link>&nbsp;
                        <button @click="handleSubmit" type="button" class="btn btn-primary ml-2">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
    import * as yup from 'yup';
    import Form from '../form/Form.vue';
    import UsaStates from 'usa-states';

    const schema = yup.object().shape({
        first_name: yup.string()
                    .required("The first name field is required (client).")
                    .min(2, "The first name must be at least 2 characters (client)."),
        last_name: yup.string()
                    .required("The last name field is required (client).")
                    .min(2, "The last name must be at least 2 characters (client)."),   
        email: yup.string()
                    .required("The email field is required (client).")
                    .email("The email must be a valid email address (client)."),
        phone: yup.string()
                    .required("The phone field is required (client)."),
        birthday: yup.string()
                    .required("The birthday field is required (client)."),
        ssn: yup.string()
                    .required("The ssn field is required (client)."),
        gender: yup.string()
                    .required("The gender field is required (client)."),            
        position: yup.string()
                    .required("The position field is required (client)."),
        salary: yup.string()
                    .required("The salary field is required (client)."),
        address: yup.string()
                    .required("The address field is required (client)."),
        city: yup.string()
                    .required("The city field is required (client)."),
        state: yup.string()
                    .required("The state field is required (client)."),
        zip: yup.string()
                    .required("The zip field is required (client)."),
        start_date: yup.string()
                    .required("The start date field is required (client)."),
    });

    export default {
        name: 'CreateEditEmployee',
        mixins: [Form],
        data() {
            return {
                'formData': {
                    'id':'', 'first_name':'', 'middle_name':'', 'last_name':'', 'email':'', 'phone':'', 'birthday':'', 'ssn':'', 'gender':'', 'position':'', 'salary':'', 'address':'', 'address2':'', 'city':'', 'state':'', 'zip':'', 'img':'', 'start_date':'', 'end_date':''
                },
                imagePreview: null,
                image: null,
                states: [],
                isEdit: false,
            }        
        },
        created() {
        },
        mounted() {
            this.retrieveAllStates();
            //alert(this.$route.params.id);
            if (!Number.isNaN(this.$route.params.id * 1)) {
                //alert('inside: ' + this.$route.params.id);
                this.isEdit = true;
                this.editEmployee(this.$route.params.id);
            } else {
                this.isEdit = false;
            }
        },
        methods: {
            handleSubmit() {
                //this.handleSubmitClientErrors();  // commented this line out. Just would like to know how to handle all client validation errors in submit button
                this.clearClientErrors();

                // Method 1 (Passing image as an object):
                let data = new FormData;
                data.append('image', this.image);
                data.append('id', this.formData.id);
                data.append('first_name', this.formData.first_name);
                data.append('middle_name', this.formData.middle_name);
                data.append('last_name', this.formData.last_name);
                data.append('email', this.formData.email);
                data.append('phone', this.formData.phone);
                data.append('birthday', this.formData.birthday);
                data.append('ssn', this.formData.ssn);
                data.append('gender', this.formData.gender);
                data.append('position', this.formData.position);
                data.append('salary', this.formData.salary);
                data.append('address', this.formData.address);
                data.append('address2', this.formData.address2);
                data.append('city', this.formData.city);
                data.append('state', this.formData.state);
                data.append('zip', this.formData.zip);
                data.append('start_date', this.formData.start_date);
                data.append('end_date', this.formData.end_date);
                axios.post('/api/employees', data)

                // Method 2 (Passing img as a string): 
                //axios.post('/api/employees', this.formData)


                    .then(response => {
                        //document.location.href="{!! route('employeeList'); !!}";
                        var baseurl = window.location.protocol + "//" + window.location.host;
                        var url = baseurl + '/employees/list';
                        document.location.href=url; 
                    }).catch(error => {
                        //console.log(error.response.status);
                        if (error.response.status == 422) {
                            this.serverErrors = error.response.data.errors;
                        }
                        //this.clearFormData();
                    })
            },
            clearFormData() {
                this.resetFormData();
                $("#img").val('');
                //document.getElementById("img").value = '';
            },
            imageSelected(e){
                /* There are two methods to pass the image file:
                    1. file object: need to use: let data = new FormData; and pass data into axios. Needs to add this to form html: enctype="multipart/form-data"
                    2. file path
                    Needs to Install Intervention Image Package -- using composer require intervention/image in https://appdividend.com/2022/02/28/laravel-image-intervention/
                */
                // Method 1 (Passing image as an object):
                this.image = e.target.files[0];
                this.formData.img = e.target.files[0];
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
                    this.formData.img = e.target.result;
                    this.imagePreview = e.target.result;
                };*/
            },
            editEmployee(id) {
                //alert(this.isEdit);
                axios.get('/api/employees/edit/' + id)
                    .then(response => {
                        this.editing = true;
                        this.formData.id = response.data.id;
                        //alert(this.formData.id);
                        this.formData.first_name = response.data.first_name;
                        this.formData.middle_name = response.data.middle_name;
                        this.formData.last_name = response.data.last_name;
                        this.formData.email = response.data.email;
                        this.formData.phone = response.data.phone;
                        this.formData.birthday = response.data.birthday;
                        this.formData.ssn = response.data.ssn;
                        this.formData.gender = response.data.gender;
                        this.formData.position = response.data.position;
                        this.formData.salary = response.data.salary;
                        this.formData.address = response.data.address;
                        this.formData.address2 = response.data.address2;
                        this.formData.city = response.data.city;
                        this.formData.state = response.data.state;
                        this.formData.zip = response.data.zip;
                        this.formData.img = response.data.img;
                        if (this.formData.img != null) {
                            // https://stackoverflow.com/questions/66419471/vue-3-vite-dynamic-img-src
                            this.imagePreview = new URL('/public/images/' + this.formData.img, import.meta.url);
                            //alert(this.imagePreview);
                        }
                        this.formData.start_date = response.data.start_date;
                        this.formData.end_date = response.data.end_date;
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
            calendarChange(fieldName) {
                this.clearServerError(fieldName);
                this.clearClientError(fieldName);
            },
            validate(fieldName) {
                this.clearServerError(fieldName);
                schema.validateAt(fieldName, this.formData)
                    .then(() => {
                        this.clearClientError(fieldName);
                    })    
                    .catch((err) => {
                        this.clientErrors[err.path] = err.message;
                    });
            },
            validateAndFormatPhone() {
                schema.validateAt('phone', this.formData)
                    .then(() => {
                        var phone = document.getElementById("phone").value;
                        phone = phone.replace("-", "")
                        if (phone.length == 10) {
                            document.getElementById("phone").value = phone.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
                        }
                    })
                    .catch((err) => {
                        this.clientErrors[err.path] = err.message;
                    });
            },
            validateAndFormatSSN() {
                schema.validateAt('ssn', this.formData)
                    .then(() => {
                        var phone = document.getElementById("ssn").value;
                        phone = phone.replace("-", "")
                        if (phone.length == 9) {
                            document.getElementById("ssn").value = phone.replace(/(\d{3})(\d{2})(\d{4})/, "$1-$2-$3");
                        }
                    })
                    .catch((err) => {
                        this.clientErrors[err.path] = err.message;
                    });
            },
            validateAndFormatZipCode() {
                schema.validateAt('zip', this.formData)
                    .then(() => {
                        var phone = document.getElementById("zip").value;
                        phone = phone.replace("-", "")
                        if (phone.length == 9) {
                            document.getElementById("zip").value = phone.replace(/(\d{5})(\d{4})/, "$1-$2");
                        }
                    })
                    .catch((err) => {
                        this.clientErrors[err.path] = err.message;
                    });
            },
            handleSubmitClientErrors() {    // Not used but leaves here for example
                schema.validate(this.formData, { abortEarly: false })
                .then(() => {
                    //const submittedData = `${this.article.title} ${this.article.description}`;
                    //alert(submittedData);
                })
                .catch((err) => {
                    err.inner.forEach((error) => {
                        this.clientErrors = { ...this.clientErrors, [error.path]: error.message };
                    });
                });
            },
            retrieveAllStates() {
                
                var usaStates = new UsaStates.UsaStates({exclude: ['DC']}).states;
                //alert(usaStates.states[0]['name']);
                for (let i=0; i<usaStates.length; i++) {
                    this.states.push(usaStates[i]);
                }
                //alert(this.states.length);
                //alert(this.states[0]['abbreviation']);
                //alert(this.states[0]['name']);
            }
        }
    }
</script>
<style scoped>
    #gender:focus, #state:focus {
        border-color: #dfe2e6;
        box-shadow: 0 0 0 0.3rem rgba(9, 138, 243, 0.25);
    }
</style>