<template>
    <div class="container-fluid">
        <usertitle></usertitle>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <flash-message></flash-message>
                        <div class="row justify-content-center">
                            <div class="clearfix"></div>
                            <div class="form-validation col-lg-6 col-12 box-shadow-inner p-0 b-r-all-15">
                                <h4 class="card-title col-12 p-0 body-section-heading b-r-l-r-15 left-site-phbox">
                                    <p class="provide-help-heading"> Page Not Found</p>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import usertitle from './UserTitle.vue';  
    export default {
        components:{
            usertitle
        },
        data(){
            return {
            }
        },
        mounted() {
        },
        methods: {
        }
    }
</script>