<template>
</template>

<script>
    import FormErrors from './FormErrors.vue';
    export default {
        name: 'Form',
        mixins: [FormErrors],
        data() {
            return {
            }
        },
        methods: {
            resetFormData() {
                for(let field in this.formData){
                    this.formData[field] = '';
                }
            },
        }
    }
</script>
