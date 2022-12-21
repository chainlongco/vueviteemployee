<template>   
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>User Register</h3>
                    </div>
                    <div class="card-body">
                        <div id="registeralert">
                        </div>
                        <form id="register_form">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">User Name</label>
                                <input v-model="formData.name" type="text" class="form-control" name="name" id="name" @blur="validate('name')" @keyup="validate('name')">
                                <span class="text-danger error-text name_error"></span>
                                <span class="text-danger" v-if="hasServerError('name')">{{ getServerError('name') }}</span>
                                <span class="text-danger" v-if="hasClientError('name')">{{ getClientError('name') }}</span>
                            </div>
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input v-model="formData.email" type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" @blur="validate('email')" @keyup="validate('email')">
                                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                                <span class="text-danger error-text email_error"></span>
                                <span class="text-danger" v-if="hasServerError('email')">{{ getServerError('email') }}</span>
                                <span class="text-danger" v-if="hasClientError('email')">{{ getClientError('email') }}</span>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input v-model="formData.password" type="password" class="form-control" name="password" id="password" @blur="validate('password')" @keyup="validate('password')">
                                <span class="text-danger error-text password_error"></span>
                                <span class="text-danger" v-if="hasServerError('password')">{{ getServerError('password') }}</span>
                                <span class="text-danger" v-if="hasClientError('password')">{{ getClientError('password') }}</span>
                            </div>
                            <div>
                                <div style="float:left; display:block;">
                                    <!-- <a href="{{ route('auth.login') }}">I already have an account, sign in</a> -->
                                    <router-link :to="{ name: 'users.login' }">I already have an account, sign in</router-link>
                                </div>
                                <div style="float:right; display:block;">
                                    <!-- <button type="submit" class="btn btn-primary" id="submitRegister">Sign Up</button> -->
                                    <button @click="handleSubmit" type="button" class="btn btn-primary ml-2">Sign Up</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>            
    </div>
</template>

<script>
    //import axios from 'axios';
    import * as yup from 'yup';
    import Form from '../form/Form.vue';

    const schema = yup.object().shape({
        name: yup.string()
                    .required("The user name field is required (client)."),
        email: yup.string()
                    .required("The email field is required (client).")
                    .email("The email must be a valid email address (client)."),
        password: yup.string()
                    .required("The password field is required (client)."),
    });

    export default {
        mixins: [Form],
        data() {
            return {
                'formData': {
                    'name':'', 'email':'', 'password':''
                },
            }        
        },
        methods: {
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
            async handleSubmit() {
                this.clearClientErrors();
                this.clearUsersOrEmployeesServerErrors();

                let data = new FormData;
                data.append('name', this.formData.name);
                data.append('email', this.formData.email);
                data.append('password', this.formData.password);
                //let response = this.signup(this.formData);  // from auth.js
                let response = await axios.post('/api/signup', data);
                let status = await (this.getSubmitStatus(response));
                //alert(status);
                if (status == 1) {
                    let msg = await (this.getSubmitMessage(response));
                    var message = "";
                    message += '<div class="alert alert-success">';
                    message += msg;
                    message += '</div>';
                    $('#registeralert').html(message);
                } else if (status == 0) {
                    let error = await (this.getSubmitError(response));
                    $.each(error, function(prefix, value) {
                        $('span.' + prefix + '_error').text(value[0]);
                    });
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
            async getSubmitError(response) {
                const error = (await response.data).error;
                return error;
            },
        },
    }
</script>

<style scoped>
</style>