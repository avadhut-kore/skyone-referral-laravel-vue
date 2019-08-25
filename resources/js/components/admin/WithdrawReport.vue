<template>
    <div class="content">
	    <div class="">
	        <div class="page-header-title">
	            <h4 class="page-title">All Withdraw List Report</h4>
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
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label></label>
                                                        <div>
                                                            <div class="input-group">
                                                               <button class="btn btn-primary waves-effect waves-light" id="search-btn" type="button">Search</button>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label></label>
                                                        <div>
                                                            <div class="input-group">
                                                               <button class="btn btn-dark waves-effect waves-light" id="reset-btn" type="button">Reset</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
	                            <table class="table table-striped table-bordered dt-responsive" id="withdraw-report">
	                                <thead>
	                                    <tr>
	                                        <th>Sr.No</th>
	                                        <th>User Id</th>
	                                        <th>Fullname</th>
	                                        <th>Date</th>
	                                        <th>Amount</th>
	                                        <th>Type</th>
	                                        <th>Status</th>
	                                        <th>More Details</th>
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
        <div class="modal fade" id="withdraw-popup" role="dialog">
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
                                    <th>Request No</th>
                                    <th>Received Amount </th>
                                    <th>Withdraw Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{withdrawDetailData.req_no}}</td>
                                    <td>{{withdrawDetailData.got_amount}}</td>
                                    <td>{{withdrawDetailData.withdraw_type}}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer hidden">
                        <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
                    </div>
                </div>all-withdraw-list
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
                withdrawDetailData : {}
            }
        },
        mounted() {
            this.withdrawReport();
        },
        components: {
            DatePicker
        },
        mixins: [formatDates],
        methods: {
            fromDateFormat(date) {
                return this.formatStartDt(date);
            },
            toDateFormat(date) {
                return this.formatEndDt(date);
            },
            withdrawReport(){
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                	let i = 0;
                    const table = $('#withdraw-report').DataTable({
                        lengthMenu: [[10, 50, 100, 500, 1000, 5000, 10000], [10, 50, 100, 500, 1000, 5000, 10000]],
                        retrieve: true,
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                        ajax: {
                            url: apiAdminHost+'all-withdraw-list',
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
                            {
                                render: function (data, type, row, meta) {
                                    if (row.withdraw_date === null || row.withdraw_date === undefined || row.withdraw_date === '') {
                                      return `-`;
                                    } else {

                                        return row.withdraw_date;
                                        //return moment(String(row.withdraw_date)).format('YYYY/MM/DD');
                                    }
                                }
                            },
                            { data: 'amount' },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.type === '') {
                                      return `User`;
                                    } else {
                                      return `${row.type}`;
                                    }
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    if (row.status === 'Confirm') {
                                      return `<label class="text-info"> ${row.status}</label>`;
                                    } else if (row.status === 'Pending') {
                                      return `<label class="text-warning"> ${row.status}</label>`;
                                    } else {
                                      return `<label class="text-danger"> ${row.status}</label>`;
                                    }
                                }
                            },
                            {
                                render: function (data, type, row, meta) {
                                    return `<label class="text-success waves-effect" id="withdraw-detail">Detail</label>`;
                                }
                            }
                        ]
                    });
                    $('#search-btn').unbind('click').click(function () {
                    	table.ajax.reload();
                    });

                    $('#reset-btn').unbind('click').click(function () {
                    	$('#search-form').trigger("reset");
                        table.ajax.reload();
                    });
                    $('body').on('click','#withdraw-detail', function(e){
                        e.preventDefault();
                        if(table.row($(this).parents('tr')).data() !== undefined) {
                            var data = table.row($(this).parents('tr')).data();
                            that.withdrawDetail(data);
                        } else {
                            var data = table.row($(this)).data();
                            that.withdrawDetail(data);
                        }
                    });
                },0);
            },
            // method to open the model
            withdrawDetail(detailData){
                this.withdrawDetailData  = detailData;
                $('#withdraw-popup').modal();               
            }
        }
    }
</script>