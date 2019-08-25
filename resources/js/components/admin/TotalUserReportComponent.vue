<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Manage User</h4>
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
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>User Id</label>
                                                <input class="form-control"  placeholder="Enter user id" type="text" id="user_id" f>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Sponser Id</label>
                                                <input class="form-control"  placeholder="Enter Sponser id" type="text" id="sponser_id" f>
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

                                          <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">Status</label>
                                                <select class="form-control" id="status">
                                                    <option  value="" >Select status</option>
                                                    <option  value="" >All</option>
                                                    <option  value="Inactive" >Block</option>
                                                    <option  value="Active" >Unblock</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" id="onSearchClick">Search</button>
                                                   <!--  <button type="button" class="btn btn-info waves-effect waves-light" @click="ExportToExcel">Export To Excel</button> -->
                                                    <!-- <button type="button" class="btn btn-info waves-effect waves-light">Export To Excel</button> -->
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
                                            <th>User Id</th>
                                            <th>Full Name</th>
                                            <th>Sponser</th>
                                           <!--  <th>Position</th>
                                            <th>Upline</th> -->
                                            <th>Mobile</th>
                                            <th>Tez No</th>
                                            <th>Paytm No</th>
                                            <th>Joining Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>User Id</th>
                                            <th>Full Name</th>
                                            <th>Sponser</th>
                                            <!-- <th>Position</th>
                                            <th>Upline</th> -->
                                            <th>Mobile</th>
                                            <th>Tez No</th>
                                            <th>Paytm No</th>
                                            <th>Joining Date</th>
                                            <th>Status</th>
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
    /*var state = {
                disabledDates: {
                    to: new Date(2019, 8, 8),
                }
            }*/
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
            
            this.manageUserReport();
        },
        methods: {
            fromDateFormat(date) {
                return this.formatStartDt(date);
            },
            toDateFormat(date) {
                return this.formatEndDt(date);
            },
            manageUserReport(){
                /*let i = 0;*/
                let i;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    //debugger;
                    let i = 0;
                    const table = $('#manage-user-report').DataTable({
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
                            url: apiAdminHost+'users',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;
                                let params = {
                                    frm_date: $('#frm-date').val(),
                                    to_date: $('#to-date').val(),
                                    id: $('#user_id').val(),
                                    sponser_id:$('#sponser_id').val(),
                                    status: $('#status').val(),
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
                                    /*json['draw'] = json.data.draw;*/
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
                            { data: 'user_id' },
                            { data: 'fullname' },
                            { data: 'sponser_id' },
                           /* { data: 'position' },
                            { data: 'virtual_parent_id' },*/
                            { data: 'mobile' },
                            { data: 'tez_no' },
                            { data: 'paytm_no' },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.entry_time === null || row.entry_time === undefined || row.entry_time === '') {
                                      return `-`;
                                    } else {
                                        return moment(String(row.entry_time)).format('YYYY/MM/DD');
                                    }
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return '<label class="text-info">'+row.status+'</label>';
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return `<a class="myProfile" title="Profile" data-id="${row.id}">
                                                <i class="fa fa-user font-16"></i>
                                            </a>&nbsp;
                                            <a class="editmyProfile" data-id="${row.id}" title="Edit">
                                                <i class="fa fa-pencil font-16"></i>
                                            </a>&nbsp
                                            <label class="${(row.status == 'Active')?'text-info':'text-warning'} waves-effect" id="changeStatus" data-id="${row.id}" data-status="${row.status}">${(row.status == 'Active')?'Block':'Unblock'}
                                            </label>&nbsp
                                            <label class="${(row.google2fa_status == 'enable')?'text-info':'text-warning'} waves-effect" id="disable2fa" data-id="${row.id}" data-status="${row.google2fa_status}">${(row.google2fa_status == 'enable')?'Disable2fa':''}
                                            </label>`;
                                }
                            }
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

                    $('#manage-user-report').on('click','#changeStatus',function () {
                        that.changeStatus($(this).data("id"),$(this).data("status"), table);
                    });

                    $('#manage-user-report').on('click','#disable2fa',function () {
                        that.disable2FA($(this).data("id"),$(this).data("google2fa_status"),table);
                    });

                    $('#manage-user-report').on('click','.editmyProfile',function () {
                        that.$router.push({
                            name:'edituserprofile',
                            params:{
                                id: $(this).data('id'),
                            }
                        });
                    });

                    $('#manage-user-report').on('click','.myProfile',function () {
                        that.$router.push({
                            name:'userprofile',
                            params:{
                                id: $(this).data('id'),
                            }
                        });
                    });

                },0);
            },
            changeStatus(id, status, table){
                Swal({
                    title: 'Are you sure?',
                    text: `You want to change status of this user`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/block-unblock-user',{
                            id: id,
                            status: status,
                        }).then(resp => {
                            if(resp.data.code == 200){
                                this.$toaster.success(resp.data.message);
                                table.ajax.reload();
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {

                        })
                    }
                });
            },
            disable2FA(id,status,table){
                Swal({
                    title: 'Are you sure?',
                    text: `You want to disable the 2fa of this user`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/update2fastatus',{
                            id: id,
                            //status: status,
                        }).then(resp => {
                            if(resp.data.code == 200){
                                this.$toaster.success(resp.data.message);
                                table.ajax.reload();
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {

                        })
                    }
                });
            },
            ExportToExcel(){
                /*var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
                var textRange; var j=0;var tab;var sa;
                tab = document.getElementById('manage-user-report'); // id of table

                for(j = 0 ; j < tab.rows.length ; j++) 
                {     
                tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
                //tab_text=tab_text+"</tr>";
                }

                tab_text=tab_text+"</table>";
                tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
                tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
                tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

                var ua = window.navigator.userAgent;
                var msie = ua.indexOf("MSIE "); 

                if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
                {
                txtArea1.document.open("txt/html","replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus(); 
                sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
                }  
                else  
                var sa               //other browser not tested on IE 11
                sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

                return (sa);*/
               
            }
        }
    }
</script>