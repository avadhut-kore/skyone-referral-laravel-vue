<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Manage Product</h4>
            </div>
        </div>

        <div class="page-content-wrapper">
            <div class="container">
                <form id="searchForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-body">
                                    <div class="">
                                       <!--  <div class="col-md-3"></div> -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>From Date</label>
                                                <div>
                                                    <div class="input-group">
                                                        <DatePicker :bootstrap-styling="true" name="frm_date" :format="fromDateFormat" :disabledDates="startstate.disabledDates"  placeholder="From Date" id="frm-date"></DatePicker>
                                                        <span class="input-group-addon bg-custom b-0">
                                                            <i class="mdi mdi-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>To Date</label>
                                                <div>
                                                    <div class="input-group">
                                                       <DatePicker :bootstrap-styling="true" name="to_date" :format="toDateFormat" :disabledDates="endstate.disabledDates" 
                                                       placeholder="To Date" id="to-date" v-model="to_date"></DatePicker>
                                                       <span class="input-group-addon bg-custom b-0">
                                                            <i class="mdi mdi-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Product</label>
                                                <select class="form-control" id="product_id">
                                                     <option  value="" >Select Product</option>
                                                     <option v-for="(product, index) in products" :value="product.id"
                                                        v-bind:key="index">{{ product.name }}
                                                     </option>
                                                </select>   
                                            </div>
                                        </div> -->

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
                                                    <button type="button" class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick">Reset</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- panel-body -->
                            </div><!-- panel -->
                        </div><!-- col -->
                    </div>
                </form>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                            <table id="manage-user-report" class="table table-striped table-bordered dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Product Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Product Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
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
                length: 10,
                start: 0,
                //state : null
                endstate : {
                    disabledDates: {
                        to: null,
                    }
                },
                startstate : {
                    disabledDates: {
                        from: null,
                    }
                },
                to_date : null
            }
        },
        components: {
            DatePicker
        },
        mixins: [formatDates],
        mounted() {
            
            this.manageProductReport();
        },
        methods: {
            fromDateFormat(date) {
                return this.formatStartDt(date);
            },
            toDateFormat(date) {
                return this.formatEndDt(date);
            },
            manageProductReport(){
                /*let i = 0;*/
                let i;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    //debugger;
                    let i = 0;
                    const table = $('#manage-product-report').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100, 500, 1000, 5000, 10000], [10, 50, 100, 500, 1000, 5000, 10000]],
                        retrieve: true,
                        destroy: true,
                        processing: false,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                         dom: 'Bfrtip',  
                          buttons: [
                            'pageLength',
                            'csv', 'excel' 
                        ],
                        ajax: {
                            url: apiAdminHost+'products',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;
                                let params = {
                                    frm_date: $('#frm-date').val(),
                                    to_date: $('#to-date').val(),
                                    id: $('#product_name').val(),
                                };
                                Object.assign(d, params);
                                return d;
                            },
                            headers: {
                              'Authorization': 'Bearer ' + token
                            },
                            dataSrc: function (json) {
                                if (json.code === 200) {
                                    i = 0;
                                    i = parseInt(json.data.start) + 1;
                                    json['recordsFiltered'] = json.data.recordsFiltered;
                                    json['recordsTotal'] = json.data.recordsTotal;
                                    return json.data.records;
                                } else {
                                    json['draw'] = 0;
                                    json['recordsFiltered'] = 0;
                                    json['recordsTotal'] = 0;
                                    return json;
                                }
                            }
                        },
                        columns: [
                            {
                                render: function (data, type, row, meta) {
                                    return i++;
                                }
                            },
                            { data: 'name' },
                            { data: 'name' },
                            
                        ]
                    });

                    $('#onSearchClick').click(function () {
                        table.ajax.reload();
                    });

                    $('#onResetClick').click(function () {
                        that.endstate.disabledDates.to = null;
                        that.startstate.disabledDates.from =null;
                        $('#searchForm').trigger("reset");
                        table.ajax.reload();
                    });

                },0);
            },
             
        }
    }
</script>