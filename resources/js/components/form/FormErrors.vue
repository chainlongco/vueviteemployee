<template>        
</template>

<script>
    export default {
        name: 'FormErrors',
        mixins: [],
        data() {
            return {
                serverErrors: {},
                clientErrors: {},
            }
        },
        methods:{
            clearServerErrors(){
                this.serverErrors = {};
            },
            clearServerError(fieldName) {
                this.serverErrors[fieldName] = '';
            },
            hasServerError(fieldName){
                return (fieldName in this.serverErrors);
            },
            getServerError(fieldName) {
                if (Object.keys(this.serverErrors).length > 0) {
                    if (this.serverErrors[fieldName]) {
                        return this.serverErrors[fieldName][0];
                    }
                }
                return "";    
            },
            clearClientErrors() {
                this.clientErrors = {};
            },
            clearClientError(fieldName) {
                this.clientErrors[fieldName] = '';
            },
            hasClientError(fieldName){
                return (fieldName in this.clientErrors);
            },
            getClientError(fieldName) {
                if (Object.keys(this.clientErrors).length > 0) {
                    if (this.clientErrors[fieldName]) {
                        return this.clientErrors[fieldName];
                    }
                }
                return "";    
            },
            clearUsersServerErrors() {
                $(document).find('span.error-text').text('');
            }
        },
    }
</script>