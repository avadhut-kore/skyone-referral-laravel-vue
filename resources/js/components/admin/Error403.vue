<template>
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">403</h4>
            </div>
        </div>
        <div class="page-content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import moment from 'moment'; 
    import { apiAdminHost } from'./../../config'; 
    export default {
        mounted() {
            localStorage.clear();
            this.$router.push({ path: '/login'});
        }
    }
</script>