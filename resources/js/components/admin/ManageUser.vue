<template>
    <div class="content">
    <div class="">
        <div class="page-header-title">
            <h4 class="page-title">Manage User Account </h4>
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
                                    <form novalidate="" ng-reflect-form="[object Object]" class="ng-untouched ng-pristine ng-invalid">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>From Date</label>
                                                    <div>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" placeholder="From Date" id="datepicker">
                                                            <span class="input-group-addon bg-custom b-0">
                                                                <i class="mdi mdi-calendar"></i>
                                                            </span>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>To Date</label>
                                                    <div>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" placeholder="To Date" id="datepicker-autoclose">
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
                                                    <input class="form-control ng-untouched ng-pristine ng-invalid" id="userId" placeholder="Enter User Id" required="" type="text">
                                                </div>

                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Sponsor Id</label>
                                                    <input class="form-control ng-untouched ng-pristine ng-invalid" id="sponsor" placeholder="Enter Sponsor Id" required="" type="text">
                                                </div>

                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label> Select Product </label>
                                                    <select class="form-control ng-pristine ng-valid ng-touched" name="selectProduct">
                                                        <option selected="" value="">Select Product</option>
                                                        <option value="0">0</option>
                                                        <option value="0">0</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label> Status </label>
                                                    <select class="form-control ng-pristine ng-valid ng-touched" name="selectProduct">
                                                        <option selected="" value="">Status</option>
                                                        <option value="0">0</option>
                                                        <option value="0">0</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button class="btn btn-primary waves-effect waves-light" id="onSearchClick" type="submit">Search</button>
                                            <button class="btn btn-info waves-effect waves-light" type="button">Export To Excel</button>
                                            <button class="btn btn-dark waves-effect waves-light mt-4" id="onResetClick" type="button">Reset</button>
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
                            <table class="table table-striped table-bordered dt-responsive" id="user-report">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>User Id</th>
                                        <th>Full Name </th>
                                        <th>Sponsor</th>
                                        <th>Position </th>
                                        <th>Mobile</th>
                                        <th>Joining Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
</template>
<script>
    import { apiAdminHost } from'./../../config';
    import moment from 'moment';  
    export default {
        data() {
            return {
                users : {}
            }
        },
        mounted() {
            this.userReport();
        },
        methods: {
            userReport(){
                //console.log("Users");
                let i = 0;
                let that = this;
                let token = localStorage.getItem('access_token');
                setTimeout(function(){
                    $('#user-report').DataTable({
                        responsive: true,
                        lengthMenu: [[10, 50, 100, 500, 1000, 5000, 10000], [10, 50, 100, 500, 1000, 5000, 10000]],
                        retrieve: true,
                        destroy: true,
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        stateSave: false,
                        ordering: false,
                        ajax: {
                            url: apiAdminHost+'users',
                            type: 'POST',
                            data: function (d) {
                                i = 0;
                                i = d.start + 1;

                                let params = {
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
                            { data: 'sponser_id' },
                            {
                              render: function (data, type, row, meta) {
                                if (row.position === 0) {
                                  return 'Left';
                                } else {
                                  return 'Right';
                                }
                              }
                            },
                            { data: 'mobile' },
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
                                if (row.status === 'Active') {
                                  return `<span class="label label-secondary"> ${row.status}</span>`;
                                } else {
                                  return `<span class="label label-danger"> ${row.status}</span>`;
                                }
                              }
                            }
                        ]
                    });
                },0);
            }
        }
    }
</script>