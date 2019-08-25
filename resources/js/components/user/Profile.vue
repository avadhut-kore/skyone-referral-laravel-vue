<template>
    <div class="container-fluid">
        <usertitle></usertitle>

        <div class="modal fade" id="editBankDetailsmodal" role="dialog">
                  <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title">Enter Otp</h4>
                          <button type="button" class="close" data-dismiss="modal">X</button>
                        </div>
                        <div class="modal-body">
                          <div class="form-group">
                            <div class="row">
                              <div class="col-md-12">
                                <!--  <label for="btc_address">Otp </label> -->
                                <input type="text" name="otp" class="form-control" placeholder="Enter OTP" v-model="otp" v-validate="'required'">
                                <div class="tooltip2" v-show="errors.has('otp')">
                                  <div class="tooltip-inner"> <span v-show="errors.has('otp')">{{ errors.first('otp') }}</span>
                                  </div>
                                </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="col-md-12" style="margin-top:10px;">
                                <center>
                                  <button @click="checkOtp(true)" type="button" class="btn btn-primary text-dark">Submit</button>
                                </center>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                  </div>
                </div>

<!--         <div class="vld-parent">

                 
        <div class="vld-parent">

            <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="fullPage" color="#b06ab3"></loading>
        </div> -->



        <div class="row justify-content-center m-t-20 mb-40">
            <div class="col-lg-12">
                <div class="profile">
                    <div class="profile-head">
                        <div class="profile-info p-0 m-0">
                            <flash-message></flash-message>


<!--                                     <div class="row" id="new-pro">
                <div class="col-md-12">
                    <div class="main-timeline">
                        <div class="timeline">
                            <a class="timeline-content">
                                <div class="timeline-icon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <h3 class="title1">User Id</h3>
                                <p class="description">{{this.user.user_id}}</p>
                            </a>
                        </div>
                        <div class="timeline">
                            <a class="timeline-content">
                                <div class="timeline-icon">
                                    <i class="fa fa-globe"></i>
                                </div>
                                <h3 class="title1">Full Name</h3>
                                <p class="description">{{this.user.fullname}}</p>
                            </a>
                        </div>

                        <div class="timeline">
                            <a class="timeline-content">
                                <div class="timeline-icon">
                                    <i class="fa fa-rocket"></i>
                                </div>
                                <h3 class="title1">Email Id</h3>
                                <p class="description">{{this.user.email}}</p>
                            </a>
                        </div>

                        <div class="timeline">
                            <a class="timeline-content">
                                <div class="timeline-icon">
                                    <i class="fa fa-briefcase"></i>
                                </div>
                                <h3 class="title1">Mobile No.</h3>
                                <p class="description">{{this.user.mobile}}</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
 -->
                                     <div class="row" id="my-profile">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="counter">
                                                <h3>user Id</h3>
                                                <span class="counter-value">{{this.user.user_id}} </span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="counter yellow">
                                                <h3>Full Name</h3>
                                                <span class="counter-value">{{this.user.fullname}}</span>
                                            </div>
                                        </div>
                                      <div class="col-md-3 col-sm-6">
                                            <div class="counter green">
                                                <h3>City</h3>
                                                <span class="counter-value"> {{city}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="counter blue">
                                                <h3>Mobile No</h3>
                                                <span class="counter-value">{{this.user.mobile}}</span>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-6">
                <div class="card b-r-all-15 phbox-main">
                    <div class="card-body p-0">
                        <div class="profile-statistics">
                            <div class="text-center p-b-15">
                                <div class="row">
                                    <div class="col">
                                        <h3 class="m-b-0 label-light profile-change-pwd f-w-600 left-site-phbox b-r-l-r-15"> Change Password </h3>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div  class="profile-blog col-lg-12 p-t-15 border-bottom-1 p-b-5">
                            <form  v-on:submit.prevent="sendOTP()" class="form-valide" action="#" method="post" novalidate="novalidate" id="change-password-form">
                                <div class="form-group row">
                                    <label class="col-lg-6 col-form-label line-height-30" for="val-username"> Feed-in the current password
                                    </label>
                                    <div class="col-lg-6">
                                       <input type="password" id="current_pwd" name="current_pwd" placeholder="Feed-in the current password" v-model="current_pwd" class="{ error: errors.has('current_pwd') } form-control" v-validate="'required'" autocomplete="off">
                                        <div class="tooltip2" style= "top:40px" v-show="errors.has('current_pwd')" data-vv-as="Current Password">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('current_pwd')">{{ errors.first('current_pwd') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-6 col-form-label line-height-30" for="val-username"> Mention the new password
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="password" id="new_password" name="new_password" placeholder="Mention the new password" v-model="new_password" class="{ error: errors.has('new_password') } form-control" v-validate="'required|min:6|max:30'" data-vv-as="New Password" autocomplete="off">
                                        <div v-if="flag_for_hide_validation_messege" class="tooltip2" style= "top:40px"  >
                                            <div class="  error-msg-size tooltip-inner ">
                                                <span class="validation-heading">Password must be:</span>
                                                <ul class="tooltipwidth">
                                                    <!-- <li id="letter" v-bind:class="one_letter" >At least one small letter
                                                    </li>
                                                    <li id="capital" v-bind:class="one_capital_letter" >At least one capital letter
                                                    </li>
                                                    <li id="capital" v-bind:class="starting_with_letter" >Be starting with alphabets
                                                    </li>
                                                    <li id="number" v-bind:class="one_number" >At least one number
                                                    </li> -->
                                                    <li id="length" v-bind:class="greater_than_6" >Be at least 8 characters Tezminimum and 15 maximum
                                                    </li>
                                                    <!-- <li id="space" v-bind:class="special_char" >be use [~,!,@,#,$,%,^,&,*,-,=,.,;,']
                                                    </li> -->
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tooltip2" style= "top:40px" v-show="errors.has('new_password')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('new_password')">{{ errors.first('new_password') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-6 col-form-label line-height-30" for="val-username"> Mention the new password Again
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Mention the new password Again" v-model="password_confirmation" class="{ error: errors.has('password_confirmation') } form-control" v-validate="'required'" v-on:keyup="matchpassword" autocomplete="off">
                                        <div class="tooltip2" style= "top:40px" v-show="errors.has('password_confirmation')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('password_confirmation')">{{ errors.first('password_confirmation') }}</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group row text-center">
                                    <div class="col-lg-12 ml-auto">
                                        <button :disabled="errors.any() || !isComplete "  type="submit" class="btn btn-primary text-dark">Change Password </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card b-r-all-15 phbox-main">
                    <div class="card-body p-0">
                        <div class="profile-statistics">
                            <div class="text-center p-b-15">
                                <div class="row">
                                    <div class="col">
                                        <h3 class="m-b-0 label-light profile-change-pwd f-w-600 left-site-phbox b-r-l-r-15">Bank Detail</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-blog col-lg-12 border-bottom-1 p-b-5">
                            <form class="form-valide" action="#" method="post"  data-vv-scope="form-2">
                                <div class="form-group row">
                                    <label class="col-lg-6 col-form-label" for="val-username"> Account Number
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" id="val-account-no" name="val-accountno" placeholder="Account Number" v-model="account_no"  v-bind:readonly="profileUpdated ==1" v-validate="'required|numeric'" />
                                          <div class="tooltip2" style= "top:40px" v-show="errors.has('form-2.val-accountno')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('form-2.val-accountno')">{{ errors.first('form-2.val-accountno') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-6 col-form-label" for="val-username"> A/C Holder Name
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" placeholder="A/c Holder Name" v-model="holder_name" name="holder_name"  readonly v-validate="'required|alpha_spaces'" />
                                      <div class="tooltip2" style= "top:40px" v-show="errors.has('form-2.holder_name')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('form-2.holder_name')">{{ errors.first('form-2.holder_name') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-6 col-form-label" for="val-username"> Bank Name
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" id="val-bank-name" name="val-bankname" placeholder="Bank Name" v-model="bank_name"  v-bind:readonly="profileUpdated ==1" v-validate="'required|alpha_spaces'" />
                                         <div class="tooltip2" style= "top:40px" v-show="errors.has('form-2.val-bankname')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('form-2.val-bankname')">{{ errors.first('form-2.val-bankname') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-6 col-form-label" for="val-username"> Branch Name
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" id="val-branch-name" name="val-branch_name" placeholder="Branch Name" v-model="branch_name"  v-bind:readonly="profileUpdated ==1" v-validate="'required|alpha_spaces'" />
                                        <div class="tooltip2" style= "top:40px" v-show="errors.has('form-2.val-branch_name')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('form-2.val-branch_name')">{{ errors.first('form-2.val-branch_name') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-6 col-form-label" for="val-username"> IFSC Code
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" id="val-ifsc-code" name="val-ifsc_code" placeholder="IFSC Code" v-model="ifsc_code"  v-bind:readonly="profileUpdated ==1" v-validate="'required|alpha_num|max:15'"/>
                                        <div class="tooltip2" style= "top:40px" v-show="errors.has('form-2.val-ifsc_code')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('form-2.val-ifsc_code')">{{ errors.first('form-2.val-ifsc_code') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-6 col-form-label" for="val-username"> PayTm Number
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" id="val-patytm-no" name="val-patytm-no" placeholder="Paytm Number" v-model="paytm"   v-validate="'numeric|min:10|max:10'" v-bind:readonly="profileUpdated ==1"  />
                                          <div class="tooltip2" style= "top:40px" v-show="errors.has('form-2.val-patytm-no')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('form-2.val-patytm-no')">{{ errors.first('form-2.val-patytm-no') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                   <div class="form-group row">
                                    <label class="col-lg-6 col-form-label" for="val-username"> GooglePay Number
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" id="val-tez-no" name="val-tez-no" placeholder="GooglePay Number" v-model="tez"   v-validate="'numeric|min:10|max:10'" v-bind:readonly="profileUpdated ==1" />
                                        <div class="tooltip2" style= "top:40px" v-show="errors.has('form-2.val-tez-no')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('form-2.form-2.val-tez-no')">{{ errors.first('form-2.form-2.val-tez-no') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                 <!-- <div class="form-group row">
                                    <label class="col-lg-6 col-form-label" for="val-username"> Mobikwik Number
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" id="val-mobikwik-no" name="val-mobikwik-no" placeholder="Mobikwik Number" v-model="mobikwik_no" v-validate="'numeric|min:10|max:10'" v-bind:readonly="profileUpdated ==1"/>
                                        <div class="tooltip2" style= "top:40px" v-show="errors.has('val-mobikwik-no')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('val-mobikwik-no')">{{ errors.first('form-2.val-mobikwik-no') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                 <div class="form-group row">
                                    <label class="col-lg-6 col-form-label" for="val-username"> Phonepe Number
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" id="val-phonepe-no" name="val-phonepe-no" placeholder="PhonePe Number" v-model="phonepe_no"  v-validate="'numeric|min:10|max:10'" v-bind:readonly="profileUpdated ==1" />
                                        <div class="tooltip2" style= "top:40px" v-show="errors.has('form-2.val-phonepe-no')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('form-2.val-phonepe-no')">{{ errors.first('form-2.val-phonepe-no') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row text-center" v-if="bank_detail">
                                    <div class="col-lg-12 ml-auto">


                                        <!--   -->
                                         
                                        <div v-if="profileUpdated ==null">

                                                <button :disabled="errors.any()" v-on:click="sendOTP" type="button" class="btn btn-primary m-t-4 m-b-13 text-dark">Update Bank Details</button>

                                           
                                        </div>
                                        
                                        <div v-if="profileUpdated ==1">

                                            <button :disabled="errors.any()" type="button" class="btn btn-primary m-t-4 m-b-13 text-dark">already your updated bank details</button>
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
</template>
<script>
    import usertitle from './UserTitle.vue';
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
        import Swal from 'sweetalert2';

    export default {
        components: {
            usertitle,
            Loading
        },
        data() {
            return {
                user: {
                    user_id: '',
                    fullname: '',
                    sponser_id: '',
                    email: '',
                    country: '',
                    btc_address: '',
                    account_no : '',
                    holder_name : '',
                    bank_name : '',
                    branch_name : '',
                    ifsc_code : '',
                    state : '',
                    city : '',
                    tez_no:'',
                    pay_no:'',
                    flag:'',

                },
                account_no : null,
                holder_name : null,
                bank_name : null,
                branch_name : null,
                ifsc_code : null,
                password_confirmation: null,
                current_pwd: null,
                new_password: null,
                btc_address: null,
                one_letter: '',
                one_capital_letter: '',
                special_char: '',
                starting_with_letter: '',
                greater_than_6: '',
                one_number: '',
                flag_for_hide_validation_messege: '',
                bank_detail : true,
                update_btc_address : null,
                isLoading: true,
                fullPage: true,
                paytm:'',
                tez:'',
                mobikwik_no:'',
                phonepe_no:'',
                profileUpdated:'',
                patym_readonly:false,   
                tez_readonly:false,
                otpstatus: false,
                otp:null,
                city:''
            }
        },
        computed: {
            isComplete() {
                return this.current_pwd && this.new_password && this.password_confirmation;
            },
            isAddrComplete(){
                return this.update_btc_address;
            }
        },
        mounted(){
            this.getProfileDetails();
            this.one_letter = 'invalid',
            this.greater_than_6 = 'invalid',
            this.one_capital_letter = 'invalid',
            this.special_char = 'invalid',
            this.one_number = 'invalid',
            this.starting_with_letter = 'invalid',
            this.flag_for_hide_validation_messege = false
    
           
        },
        methods: {
            getProfileDetails() {
                axios.get('profile')
                .then(response => {
                    if(response.data.code == 200){

                    //   console.log(response.data.data);

                        this.user = response.data.data;
                        this.btc_address = this.user.btc_address;
                        this.bank_detail = true;
                        this.account_no = this.user.account_no;
                        this.holder_name = this.user.holder_name;
                        this.bank_name = this.user.bank_name;
                        this.branch_name = this.user.branch_name;
                        this.ifsc_code = this.user.ifsc_code;
                        this.profileUpdated=this.user.flag;
                        this.phonepe_no=this.user.phonepe_no;
                        this.city=this.user.city;
                        // this.mobikwik_no=this.user.mobikwik_no;
                        //alert(this.profileUpdated);

                        if(this.user.paytm_no != null){
                            
                        this.patym_readonly = true;  
                        }
                         if(this.user.tez_no != null){
                          
                        this.tez_readonly = true;  
                        }

                        this.paytm = this.user.paytm_no;
                        this.tez = this.user.tez_no;

                        this.isLoading = false;
                    }
                });
            },
            changeAddress() {
                this.errmessage = '';
                axios.post('change-address', {
                    btc_address: this.update_btc_address,
                }).then(response => {
                    if (response.data.code == 200) {
                        this.user.btc_address = this.update_btc_address;
                        this.btc_address = this.update_btc_address;
                        this.$toaster.success(response.data.message);
                    } else {
                        this.errmessage = response.data.message;
                        this.flash(this.errmessage, 'warning', {
                            timeout: 100000,
                        });
                    }
                }).catch(error => {
                    this.reset();
                    this.message = error.response.data.message;
                    this.flash(this.message, 'error', {
                        timeout: 500000,
                    });
                });
            },
            updateBankDetail(){

                            axios.post('update-bank-detail', {
                            account_no:(this.account_no != null ? this.account_no : this.user.account_no),
                            holder_name:(this.holder_name != null ? this.holder_name : this.user.holder_name),
                            bank_name:(this.bank_name != null ? this.bank_name : this.user.bank_name),
                            branch_name:(this.branch_name != null ? this.branch_name : this.user.branch_name),
                            ifsc_code:(this.ifsc_code != null ? this.ifsc_code : this.user.ifsc_code),
                            paytm_no:(this.paytm != null ? this.paytm : this.user.paytm_no),
                            tez_no:(this.tez != null ? this.tez : this.user.tez_no),
                            phonepe_no:(this.phonepe_no != null ? this.phonepe_no : this.user.phonepe_no),
                            // mobikwik_no:(this.mobikwik_no != null ? this.mobikwik_no : this.user.mobikwik_no),
                            flag:'1',
                            city:this.city,

                            amount : this.amount
                        }).then(response => {
                            if (response.data.code == 200) {
                                this.getProfileDetails();
                                this.$toaster.success(response.data.message);
                                this.bank_detail = false;
                            } else {
                                this.$toaster.error(response.data.message);
                            }
                        }).catch(error => {
                            this.message = '';
                            this.flash(this.message, 'error', {
                                timeout: 500000,
                            });
                        });                                    
            },
            changePassword() {
                this.errmessage = '';
                axios.post('change-password', {
                    password_confirmation: this.password_confirmation,
                    new_password: this.new_password,
                    current_pwd: this.current_pwd,
                }).then(response => {
                    if (response.data.code == 200) {
                       $('#change-password-form').trigger("reset");
                        this.$toaster.success(response.data.message);
                    } else {
                        this.$toaster.error(response.data.message);
                    }
                }).catch(error => {
                    this.reset();
                    this.message = error.response.data.message;
                    this.flash(this.message, 'error', {
                        timeout: 500000,
                    });
                });
            },
            onPasswordClick() {
                this.one_letter = 'invalid';
                this.greater_than_6 = 'invalid';
                this.one_capital_letter = 'invalid';
                this.special_char = 'invalid';
                this.one_number = 'invalid';
                this.starting_with_letter = 'invalid';
                this.flag_for_hide_validation_messege = true;

                if (this.new_password.substring(0, 1) == this.new_password.match(/[A-z]/)) {
                    this.starting_with_letter = 'valid';
                }
                if ((this.new_password.length >= 8) && (this.new_password.length <= 15)) {
                    this.greater_than_6 = 'valid';
                }
                if (this.new_password.match(/[a-z]/)) {
                    this.one_letter = 'valid';
                }
                if (this.new_password.match(/[A-Z]/)) {
                    this.one_capital_letter = 'valid';
                }
                if (this.new_password.match(/\d/)) {
                    this.one_number = 'valid';
                }
                if (this.new_password.match(/[^a-zA-Z0-9\-\/]/)) {
                    this.special_char = 'valid';
                }
                if (this.new_password.match(/\s/g)) {
                    this.special_char = 'invalid';
                }
              /*   if (this.one_letter === 'valid' && this.greater_than_6 === 'valid' && this.one_capital_letter === 'valid' && this.special_char === 'valid' && this.one_number === 'valid' && this.starting_with_letter === 'valid') {
                    this.flag_for_hide_validation_messege = false;
                } */ 
                 if (this.greater_than_6 === 'valid'){
                       this.flag_for_hide_validation_messege = false;
                }else {
                    this.errors.add({
                        field: 'new_password',
                        msg: 'password not valid'
                    });
                }
            },
            sendOTP() {
                    this.$validator.validateAll('form-2').then((result) => {
                        if (result) {
                            this.otp = '';
                                axios.post('send-profile-otp',{
                                    user_id : this.user.user_id,
                                    mobile : this.user.mobile,
                                    country : this.user.country,
                                }).then(response => {
                        
                                    if (response.data.code == 200) {
                                        //console.log(response);
                                        this.$toaster.success(response.data.message);
                                        //this.statedata=response.data.data.message;

                                        $('#editBankDetailsmodal').modal('show');

                                        } else {
                                        this.$toaster.error(response.data.message);
                                        }
                                    }).catch(error => {});
                        }
                    });
                },


                checkOtp(chngpswd=false) {
                     this.$validator.validate('otp',this.otp).then((result) => {
                        if (result) {
                          axios.post('verify-profile-otp', {
                            otp: this.otp,
                            user_id: this.user.user_id,
                            mobile: this.user.mobile,
                           
                          }).then(response => {
                              this.otpstatus = true;
                              this.otp = '';
                            if (response.data.code == 200) {
                                if(chngpswd){
                                    this.changePassword();
                                }
                                else{
                                    this.updateBankDetail();
                                }
                                  this.statedata=response.data.data;
                                  
                                  $('#editBankDetailsmodal').modal('hide');
                                    $('#editBankDetailsmodal').modal('toggle');
                                    $("#editBankDetailsmodal .close").click()
                                  
                                  $('#editBankDetails1').modal('show');
                                 this.$toaster.success(response.data.message);

                                } else {
                                  this.$toaster.error(response.data.message);
                                }
                              }).catch(error => {});
                        }
                     });
                    },
            matchpassword() {
                if (this.new_password !== this.password_confirmation) {
                    this.errors.add({
                        field: 'password_confirmation',
                        msg: 'password does not match'
                    });
                }
            }
        }
    }
</script>