<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Add New Product</h4>
            </div>
        </div>

        <div class="page-content-wrapper">
            <div class="container">
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                            
                            </div>
                        </div>
                    </div>
                </div><!-- end row -->
            </div><!-- container -->
        </div><!-- Page content Wrapper -->
    </div><!-- content -->
</template>

<script>
    import { apiAdminHost } from'./../../config'; 
    import moment from 'moment';
    import Swal from 'sweetalert2';
    import { formatDates } from'./../../helper'; 
    import DatePicker from 'vuejs-datepicker';
    
    export default {
        data() {
            return {
                
            }
        },
        components: {
            DatePicker
        },
        mixins: [formatDates],
        mounted() {
            
        },
        methods: {
            addProduct(){
                
               
            },
        }
    }
</script>