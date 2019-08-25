<template>
    <div class="container-fluid">
        <div class="row page-titles">
            <usertitle></usertitle>
            {{ cat }}
            <div class="col col-12 p-0 float-sm-left">
                <ol class="breadcrumb">
                   <li class="breadcrumb-item"><a href="index.php"> Dashboard  </a> </li>
                   <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                   <li class="breadcrumb-item active">Dashboard</li>
                   </ol>
            </div>
        </div>

        <flash-message></flash-message>

        <div class="row mt-20 mtb-40">
            <div class="col-md-3 col-sm-6">
               
            </div>
            
        </div>
      
    </div>
<!-- #/ container -->
</template>
<script>
    import Vue from 'vue';
    import Swal from 'sweetalert2';
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
    import usertitle from './UserTitle.vue';  
    import moment from 'moment';  
    export default {
        components:{
            usertitle,
            Loading
        },
        data() {
            return {
                dashboard: {
                    user_name: null,
                    userid: null,
                    ref_url: null,
                },
                display_userid: '',
                fullname: '',
               
                categories: [],
                
                
            }
        },
        mounted() {
            this.cat = this.$route.params.category;
        },
        methods: {
            getDashboardData() {
                axios.get('userdashboard')
                .then(response => {
                    this.categories = response.data.data.categories;
                }).catch(error => {
                });
            },
        },
    }
</script>