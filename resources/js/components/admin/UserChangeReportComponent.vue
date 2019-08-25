<template>
    <div class="content">
	    <div class="">
	        <div class="page-header-title">
	            <h4 class="page-title">Edit User Profile Report</h4>
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
	                                    <form novalidate="" id="search-form">
	                                        <div class="row">
	                                            <div class="col-md-4">
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
                                                <div class="col-md-4">
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

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>User Id</label>
                                                        <div>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" placeholder="user Id" id="user_id">
                                                              
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
	                                        </div>
	                                        <div class="text-center">
	                                            <button class="btn btn-primary waves-effect waves-light" id="search-btn" type="button">Search</button>
	                                            <button class="btn btn-dark waves-effect waves-light mt-4" id="reset-btn" type="button">Reset</button>
	                                        </div>
	                                    </form>

	                                </div>

	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="panel panel-primary">
	                        <div class="panel-body">
	                            <table class="table table-striped table-bordered dt-responsive" id="user-log-report">
	                                <thead>
	                                    <tr>
	                                        <th>Sr.No</th>
	                                        <th>User Id</th>
	                                        <th>Full Name</th>
	                                        <th>Mobile</th>
                                            <th>Tez No</th>
                                            <th>Paytm No</th>
                                            <!-- <th>BTC Address</th> -->
                                            <th>More Details</th>                           
                                           <!--  <th>Action</th> -->
	                                    </tr>
	                                </thead>
	                            </table>
	                        </div>
	                    </div>
	                </div>
	            </div>

	        </div>
	    </div>
        <!-- Start of detail popup -->
        <div class="modal fade" id="user-details-popup" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">More Details</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-bordered dt-responsive">
                            <thead>
                                <tr>
                                    <th>Country</th>
                                    <th>State</th>
                                    <th>City</th>
                                    <th>Sponsor Id</th>
                                    <th>IP</th>
                                    <th>Updated By</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{userDetails.country}}</td>
                                    <td>{{userDetails.state}}</td>
                                    <td>{{userDetails.city}}</td>
                                    <td>{{userDetails.sponser_id }}</td>
                                    <td>{{userDetails.ip}}</td>
                                    <td>{{userDetails.updated_by}}</td>
                                    <td>{{userDetails.created_at | formatDate}}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer hidden">
                        <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of detail popup -->
   </div>
</template>
<script>
    import moment from 'moment'; 
    import DatePicker from 'vuejs-datepicker';
    import { formatDates } from'./../../helper'; 
    import { apiAdminHost } from'./../../config'; 
    export default {
        data() {
            return {
                userDetails:[],
            }
        },
        components: {
            DatePicker
        },
        mixins: [formatDates],
        mounted() {
            this.userLogReport();
        },
        methods: {
            fromDateFormat(date) {
                return this.formatStartDt(date);
            },
            toDateFormat(date) {
                return this.formatEndDt(date);
            },
            userLogReport(){
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                	let i = 0;
                    const table = $('#user-log-report').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100, 500, 1000, 5000, 10000], [10, 50, 100, 500, 1000, 5000, 10000]],
                        retrieve: true,
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        stateSave: false,
                        ordering: false,
                        ajax: {
                            url: apiAdminHost+'get-user-logs',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
                                	frm_date : $('#frm-date').val(),
                                    to_date  : $('#to-date').val(),
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
                                    //json['draw'] = json.data.draw;
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
                            { data: 'mobile' },
                            { data: 'tez_no' },
                            { data: 'paytm_no' },
                            /*{ data: 'btc_address' },*/
                            {
                              render: function (data, type, row, meta) {
                                    return `<label class="text-success waves-effect" id="onDetailClick1">Detail</label>`;
                                }
                             },  
                        ]
                    });
                    $('#search-btn').unbind('click').click(function () {
                    	table.ajax.reload();
                    });

                    $('#reset-btn').unbind('click').click(function () {
                    	$('#search-form').trigger("reset");
                        table.ajax.reload();
                    });
                    /*  User details*/
                    $('#user-log-report tbody').on('click', '#onDetailClick1', function (){
                        if(table.row($(this).parents('tr')).data() !== undefined) {
                            var data = table.row($(this).parents('tr')).data();
                            that.onDetailClick(data);
                        } else {
                            var data = table.row($(this)).data();
                            that.onDetailClick(data);
                        }
                    });
                },0);
            },
            onDetailClick(data){
                $('#user-details-popup').modal();               
                this.userDetails = data;
            },
        }
    }
</script>