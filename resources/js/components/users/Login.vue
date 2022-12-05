<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>User Log in</h3>
                    </div>
                    <div class="card-body">
                        <div id="loginalert">
                        </div>
                    
                        <!--<form method="POST" action="{{ route('login-submit') }}" id="login_form">-->
                        <form id="login_form">    
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email address</label>
                                    <input v-model="formData.email" type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" @blur="validate('email')" @keyup="validate('email')">
                                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                                    <span class="text-danger error-text email_error"></span>
                                    <span class="text-danger" v-if="hasServerError('email')">{{ getServerError('email') }}</span>
                                    <span class="text-danger" v-if="hasClientError('email')">{{ getClientError('email') }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <input v-model="formData.password" type="password" class="form-control" name="password" id="password" @blur="validate('password')" @keyup="validate('password')">
                                    <span class="text-danger error-text password_error"></span>
                                    <span class="text-danger" v-if="hasServerError('password')">{{ getServerError('password') }}</span>
                                    <span class="text-danger" v-if="hasClientError('password')">{{ getClientError('password') }}</span>
                                </div>
                            </div>
                            <!--<div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Check me out</label>
                            </div>-->   
                            <div>
                                <div style="float:left; display:block;">               
                                    <!--<a id="noUserAccount" href="{{ route('auth.register') }}">I don't have an account, create new</a>-->
                                    <router-link :to="{ name: 'users.register' }" class="" id="noUserAccount">I don't have an account, create new</router-link>
                                </div>
                                <div style="float:right; display:block;">               
                                    <!--<button type="submit" class="btn btn-primary" id="submitLogin">Sign In</button>-->
                                    <button @click="handleSubmit" type="button" class="btn btn-primary ml-2">Sign In</button>
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
    import { mapActions } from 'vuex';

    const schema = yup.object().shape({
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
                    'email':'', 'password':''
                },
            }        
        },
        methods: {
            ...mapActions({
                signIn: 'auth/signIn'
            }),

            validate(fieldName) {
                this.clearServerError(fieldName);
                this.clearUsersServerErrors();
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
                this.clearUsersServerErrors();

                let data = new FormData;
                data.append('email', this.formData.email);
                data.append('password', this.formData.password);
                let response = this.signIn(this.formData);  // from auth.js
                let status = await (this.getSubmitStatus(response));
                if (status == 2) {
                    let user = await (this.getSubmitUser(response));
                    //alert(user.name);
                    var baseurl = window.location.protocol + "//" + window.location.host;
                    var url = baseurl + '/employees/list';
                    document.location.href=url;
                } else if (status == 1 || status == 3) {
                    let msg = await (this.getSubmitMessage(response));
                    var message = "";
                    message += '<div class="alert alert-danger">';
                    message += msg;
                    message += '</div>';
                    $('#loginalert').html(message);
                } else if (status == 0) {
                    let error = await (this.getSubmitError(response));
                    $.each(error, function(prefix, value) {
                        $('span.' + prefix + '_error').text(value[0]);
                    });
                }               
            },
            async getSubmitStatus(response) {
                const status = (await response).status;
                return status;
            },
            async getSubmitUser(response) {
                const user = (await response).user;
                return user;
            },
            async getSubmitMessage(response) {
                const message = (await response).msg;
                return message;
            },
            async getSubmitError(response) {
                const error = (await response).error;
                return error;
            },
            handleSubmitBackup() {
                this.clearClientErrors();
                this.clearUsersServerErrors();

                let data = new FormData;
                data.append('email', this.formData.email);
                data.append('password', this.formData.password);
                axios.post('/api/login', data)
                    .then(response => {
                        if (response.data.status==0) {
                            $.each(response.data.error, function(prefix, value) {
                                $('span.' + prefix + '_error').text(value[0]);
                            });
                        } else if (response.data.status==1) {
                            //alert(response.data.msg);
                            var message = "";
                            message += '<div class="alert alert-danger">';
                            message += response.data.msg;
                            message += '</div>';
                            $('#loginalert').html(message);
                            //$('span.match_error').text(data.msg);
                        } else {
                            //alert(response.data.status);
                            var baseurl = window.location.protocol + "//" + window.location.host;
                            var url = baseurl + '/employees/list';
                            document.location.href=url; 
                        }
                    }).catch(error => {
                        if (error.response.status == 422) {
                            this.serverErrors = error.response.data.errors;
                        }
                    })
            },
            //clearUsersServerErrors() {
            //    $(document).find('span.error-text').text('');
            //}
        },
    }
</script>

<style scoped>
</style>