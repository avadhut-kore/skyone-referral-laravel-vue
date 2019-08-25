<template>
    <div class="content">

        <div class="">
            <div class="page-header-title">
                <h4 class="page-title"> Edit Profile </h4>
            </div>
        </div>

        <div class="page-content-wrapper ">

            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-3">
                        <div class="panel panel-primary">
                            <div class="panel-body add-new">
                                <a class="btn btn-primary waves-effect waves-light pull-right" href="skyoneadmin#/total-user-report">
                                    <i class="fa fa-mail-reply"></i> &nbsp;Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <h4 class="m-t-0 m-b-30"> Personal Information </h4>
                                <form class="form-horizontal" v-on:submit.prevent="onUpdateUserClick">
                                    <input type="hidden" name="id" v-model="editUser.id">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label">
                                                User Id
                                                <!-- <span class="madatoryStar text-danger text-danger">*</span> -->
                                            </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="User ID" readonly type="text" v-model="editUser.user_id">
                                            </div>
                                        </div>
                                    </div>

                                  <!--   <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email"> Sponsor Id
                                                 <span class="madatoryStar text-danger"> *</span> 
                                            </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="User ID" readonly type="text" v-model="editUser.sponsor_id">
                                            </div>
                                        </div>
                                    </div> -->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> Full Name
                                                <span class="madatoryStar text-danger"> *</span>
                                            </label>
                                            <div class="col-md-7">
                                                <input name="fullname" class="form-control" placeholder="Update Full Name" type="text" v-model="editUser.fullname" v-validate="'required|alpha_spaces'">
                                                <div class="tooltip2" v-show="errors.has('fullname')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('fullname')">{{ errors.first('fullname') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email"> DOB
                                                <span class="madatoryStar text-danger"> *</span>
                                            </label>
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    <DatePicker :bootstrap-styling="true" name="dob" :format="dateFormat" placeholder="DOB" id="dob" v-model="editUser.dob" v-validate="'required'"></DatePicker>
                                                    <div class="tooltip2" v-show="errors.has('dob')">
                                                        <div class="tooltip-inner">
                                                            <span v-show="errors.has('dob')">{{ errors.first('dob') }}</span>
                                                        </div>
                                                    </div>
                                                    <span class="input-group-addon bg-custom b-0">
                                                        <i class="mdi mdi-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->

                                    <!-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email"> Email
                                                <span class="madatoryStar text-danger"> *</span>
                                            </label>
                                            <div class="col-md-7">
                                                <input class="form-control" name="email" placeholder="Update Email" type="text" v-model="editUser.email" v-validate="'required|email'">
                                                <div class="tooltip2" v-show="errors.has('email')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('email')">{{ errors.first('email') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-mobile"> Mobile
                                                <span class="madatoryStar text-danger"> *</span>
                                            </label>
                                            <div class="col-md-7">
                                                <input name="mobile" class="form-control" id="mobile" placeholder="Enter Mobile" required type="text" v-model="editUser.mobile" v-validate="'required'">
                                                <div class="tooltip2" v-show="errors.has('mobile')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('mobile')">{{ errors.first('mobile') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> Nominee Name </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update Nominee Name" type="text" v-model="editUser.nominee_name">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> Nominee Relation </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update Nominee Name" type="text" v-model="editUser.relation">
                                            </div>
                                        </div>
                                    </div> -->

                                   <!--  <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> Country <span class="madatoryStar text-danger"> *</span></label>
                                            <div class="col-md-7">
                                                <select class="form-control" v-model="editUser.country" @change="onChangeCountry($event.target.value)">
                                                    <option :value="null">Select Country</option>
                                                    <option v-for="cntry in arrCountry" :selected="(cntry.country===editUser.country) ? true : false" :value="cntry.iso_code" >{{cntry.country}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> -->
<!-- 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> State <span class="madatoryStar text-danger"> *</span></label>
                                            <div class="col-md-7">
                                                <select class="form-control" v-model="editUser.state">
                                                    <option selected="" value="">Select State</option>
                                                    <option :value="sta.name" v-for="sta in arrStates">{{sta.name}}</option>
                                                </select> 
                                                <input class="form-control" placeholder="Update State" type="text" v-model="editUser.state" name="state" v-validate="'alpha_spaces'" >
                                                <div class="tooltip2" v-show="errors.has('state')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('state')">{{ errors.first('state') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email"> City <span class="madatoryStar text-danger"> *</span></label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update City" type="text" v-model="editUser.city" name="city" v-validate="'alpha_spaces'" >
                                                <div class="tooltip2" v-show="errors.has('city')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('city')">{{ errors.first('city') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> Btc Address </label>

                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update Btc Address" type="text" v-model="editUser.btc_address" >
                                            </div>

                                        </div>
                                    </div>

                                    <div class="clearfix"></div>
                                    <hr>

                                    <h4 class="m-t-0 m-b-30"> Bank Information </h4>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> Account No </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update Account No" type="text" v-model="editUser.account_no" name="accountno" v-validate="'numeric'" >
                                                 <div class="tooltip2" v-show="errors.has('accountno')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('accountno')">{{ errors.first('accountno') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> A/C Name </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update A/C Name" type="text" v-model="editUser.holder_name" name="accountholder" v-validate="'alpha_spaces'" >
                                                <div class="tooltip2" v-show="errors.has('accountholder')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('accountholder')">{{ errors.first('accountholder') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> Bank Name </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update Bank Name" type="text" v-model="editUser.bank_name" name="bankname" v-validate="'alpha_spaces'" >
                                                <div class="tooltip2" v-show="errors.has('bankname')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('bankname')">{{ errors.first('bankname') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email"> Branch Name </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update Branch Name" name="branchname" type="text" v-model="editUser.branch_name" v-validate="'alpha_spaces'">
                                                <div class="tooltip2" v-show="errors.has('branchname')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('branchname')">{{ errors.first('branchname') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                   <!--  <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> PAN Number </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update PAN Number" type="text" v-model="editUser.pan_no">
                                            </div>
                                        </div>
                                    </div> -->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email">IFSC Code</label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update IFSC Code" type="text" v-model="editUser.ifsc_code" name="ifsccode" v-validate="'alpha_num|max:15'">
                                                <div class="tooltip2" v-show="errors.has('ifsccode')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('ifsccode')">{{ errors.first('ifsccode') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label"> Tez Number </label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update Tez Number" type="text" v-model="editUser.tez_no" v-validate="'numeric|min:10|max:10'" name="tezno">
                                                <div class="tooltip2" v-show="errors.has('tezno')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('tezno')">{{ errors.first('tezno') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 m-b-30  m-b-30">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email">Paytm No</label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update Paytm No" type="text" v-model="editUser.paytm_no" v-validate="'numeric|min:10|max:10'" name="paytmno">
                                                <div class="tooltip2" v-show="errors.has('paytmno')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('paytmno')">{{ errors.first('paytmno') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                         

                                         <div class="col-md-6 m-b-30  m-b-30">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email">PhonePe No</label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update phonepe No" type="text" v-model="editUser.phonepe_no" v-validate="'numeric|min:10|max:10'" name="phonepe_no">
                                                <div class="tooltip2" v-show="errors.has('phonepe_no')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('phonepe_no')">{{ errors.first('phonepe_no') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 

                                     <!-- <div class="col-md-6 m-b-30  m-b-30">
                                        <div class="form-group">
                                            <label class="col-md-5 control-label" for="example-email">Mobikwik No</label>
                                            <div class="col-md-7">
                                                <input class="form-control" placeholder="Update mobikwik No" type="text" v-model="editUser.mobikwik_no"v-validate="'numeric|min:10|max:10'" name="mobikwik_no">
                                                <div class="tooltip2" v-show="errors.has('mobikwik_no')">
                                                    <div class="tooltip-inner">
                                                        <span v-show="errors.has('mobikwik_no')">{{ errors.first('mobikwik_no') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  -->
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-5 col-md-6">
                                            <div class="col-md-6">
                                                <button :disabled="errors.any()" class="btn btn-info col-md-12" name="submit" type="submit">
                                                    <i class="ace-icon fa fa-check bigger-110"></i>Submit
                                                </button>
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
    </div>
</template>

<script>
    import { apiAdminHost } from'./../../config';
    import Swal from 'sweetalert2';
    import DatePicker from 'vuejs-datepicker';
    import moment  from 'moment';

    export default {

        data() {
            return {
                arrCountry: [],
                arrStates: [],
                editUser: {
                    id: '',
                    user_id: '',
                    sponsor_id: '',
                    fullname: '',
                    dob: '',
                    nominee_name: '',
                    relation: '',
                    // email: '',
                    country: '',
                    contact_number: '',
                    state: '',
                    city: '',
                    account_no: '',
                    holder_name: '',
                    bank_name: '',
                    branch_name: '',
                    pan_no: '',
                    ifsc_code: '',
                    paytm_no: '',
                    payzapp_no: '',
                    insurance_amt: '',
                    mobile: '',
                    tez_no: '',
                    paytm_no: '',
                    // mobikwik_no:'',
                    phonepe_no:'',
                }
            }
        },
        components: {
            DatePicker
        },
        computed: {

        },
        mounted() {
            this.getUserDetails();
            this.getCountry();
        },
        methods: {
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY');
            },
            getUserDetails() {
                axios.post('/viewprofile', {
                    id: this.$route.params.id,
                }).then(resp => {
                    if (resp.data.code === 200) {
                        this.editUser = {
                            id: resp.data.data.id,
                            user_id: resp.data.data.user_id,
                            sponsor_id: resp.data.data.sponser_id,
                            fullname: resp.data.data.fullname,
                            dob: resp.data.data.dob,
                            mobile: resp.data.data.mobile,
                            nominee_name: resp.data.data.nominee_name,
                            relation: resp.data.data.relation,
                            // email: resp.data.data.email,
                            country: resp.data.data.iso_code,
                            contact_number: resp.data.data.contact_number,
                            state: resp.data.data.state,
                            city: resp.data.data.city,
                            account_no: resp.data.data.account_no,
                            holder_name: resp.data.data.holder_name,
                            bank_name: resp.data.data.bank_name,
                            branch_name: resp.data.data.branch_name,
                            pan_no: resp.data.data.pan_no,
                            ifsc_code: resp.data.data.ifsc_code,
                            btc_address: resp.data.data.btc_address,
                            paytm_no: resp.data.data.paytm_no,
                            tez_no: resp.data.data.tez_no,
                            // mobikwik_no:resp.data.data.mobikwik_no,
                             phonepe_no:resp.data.data.phonepe_no,
                        }
                        if ((this.editUser.country != '') || (this.editUser.country != undefined) || (this.editUser.country != null)) {
                            //this.onChangeCountry(this.editUser.country);
                        }
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    this.$toaster.error(err);
                })
            },
            onUpdateUserClick() {
                Swal({
                    title: 'Are you sure?',
                    text: `You want to update this user`,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        axios.post('/update-user', this.editUser)
                                .then(resp => {
                                    if (resp.data.code === 200) {
                                        this.$router.push({name: 'total-user-report'});
                                        this.$toaster.success(resp.data.message);
                                    } else {
                                        this.$toaster.error(resp.data.message);
                                    }
                                }).catch(err => {
                            this.$toaster.error(err.resp.data.message);
                        })
                    }
                });
            },
            getCountry() {
                axios.get('../country')
                        .then(resp => {
                            if (resp.data.code === 200) {
                                this.arrCountry = resp.data.data;
                            } else {
                                this.$toaster.error(resp.data.message);
                            }
                        }).catch(err => {
                    this.$toaster.error(err);
                })
            },
            onChangeCountry(countryName) {
                axios.post('../getStateByCountry', {
                    country: countryName
                }).then(resp => {
                    if (resp.data.code === 200) {
                        this.arrStates = resp.data.data;
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    this.$toaster.error(err);
                })
            },
            isValidPanNo() {
                const regex = /[A-Z]{5}[0-9]{4}[A-Z]{1}/;
                if ((!regex.test(this.editUser.pan_no)) || (this.editUser.pan_no.length > 10 || this.editUser.pan_no.length < 10)) {
                    this.errors.add({field: 'pan_no', msg: 'Enter valid PAN No.'});
                } else {
                    this.errors.remove('pan_no');
                }
            },
        }
    }
</script>