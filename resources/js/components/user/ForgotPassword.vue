<template>
	 <div class="login-bg h-100">
        <div class="container h-100">
            <div class="modal fade" id="editBankDetailsmodal" role="dialog">
                  <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div v-show="otpstatus==false">
                        <div class="modal-header">
                          <h4 class="modal-title">Enter Otp</h4>
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
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
                              <div class="col-md-12">
                                <center>
                                  <button @click="checkOtp()" type="button" class="btn btn-primary text-dark">Submit</button>
                                </center>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
            <div class="row justify-content-center h-100">
                <div class="col-xl-5">
                    <div class="form-input-content">
                        <div class="card">
                            <div class="card-body">
                                <div class="logo text-center">
                                    <a href="/">
                                        <img src="public/images/logo.png" alt="">
                                    </a>
                                </div>
                                <h2 class="m-t-15">Forgot Password </h2>
                               
                                <form class="m-t-10 m-b-10 labbledark">
                                    <div class="form-group giveposition" >
                                        <label>Enter your username below to reset your password.</label>
                                       <!--  <input type="user_id" class="form-control" placeholder="Username"> -->
                                       <input type="text" id="user_id" name="user_id" placeholder="Enter Username" v-model="user_id" class="{ error: errors.has('user_id') } form-control" v-validate="'required'">
                                       <div class="tooltip2" v-if="errors.has('user_id')">
                                            <div class="tooltip-inner">
                                            <span v-show="errors.has('user_id')">{{ errors.first('user_id') }}</span>
                                            </div>
                                        </div>
                                       <div v-else-if='!useractive' class="tooltip2">
                                        <span class=" text-danger error-msg-size tooltip-inner"> {{ this.usermsg }}</span>
                                      </div>
                                            
                                    </div>

                                    <div class="text-center m-b-15 m-t-15">
                                       <button :disabled="errors.any()" @click="checkuserexist" id="forgot_password" type="button" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                                <div class="text-center">
                                   <router-link tag="li" :to="{ name: 'login' }"><a>Login</a>
                                    </router-link>
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
    export default {
        data(){
            return {
                user_id : '',
                password : '',
                useractive:true,
                usermsg:'',
                otpstatus:false,
                otp:'',
                user_mobile:'',
                user_country:''

            }
        },
        mounted(){ 
            //console.log("TEST Forgot password");
        },
        methods: {
               checkuserexist() {
                 this.$validator.validate('user_id',this.user_id).then((result) => {
                        if (result) {
                    axios.post("checkuserexist", {
                        user_id: this.user_id
                        })
                        .then(response => {
                        if (response.data.code == 200) {
                            this.user_mobile=response.data.data.mobile;
                            this.user_country=response.data.data.country;
                            this.useractive = true;
                            this.sendOTP(this.user_mobile,this.user_country);
                        } else {
                            this.useractive = false;
                            this.usermsg = response.data.message;
                        }
                        }).catch(error => {});
                 }
                 });

                },
                sendOTP(mobile,country) {
                        // alert(mobile+country)
                        axios.post('send-registration-otp',{
                            user_id : this.user_id,
                            mobile : mobile,
                            country : country,
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
                        
                },
                checkOtp() {
                     this.$validator.validate('otp',this.otp).then((result) => {
                        if (result) {
                          axios.post('verify-registration-otp', {
                            otp: this.otp,
                            user_id: this.user_id,
                            mobile: this.user_mobile,
                           
                          }).then(response => {
                            if (response.data.code == 200) {
                                  this.forgotPassword();
                                  this.statedata=response.data.data;
                                  this.otp = '';
                                  $('#editBankDetailsmodal').modal('hide');
                                    $('#editBankDetailsmodal').modal('toggle');
                                    $("#editBankDetailsmodal .close").click()
                                  this.otpstatus = true;
                                  $('#editBankDetails1').modal('show');
                                 this.$toaster.success(response.data.message);

                                } else {
                                  this.$toaster.error(response.data.message);
                                }
                              }).catch(error => {});
                        }
                     });
                    },
            forgotPassword(){
                //console.log('hiiihfddd'); 
                axios.post('forgot-password',{                  
                    user_id:this.user_id,
                }).then(response => {
                    if(response.data.code == 200) {
                        this.$toaster.success(response.data.message);
                        this.$router.replace({ name:'login' });
                    } else {
                        this.$toaster.error(response.data.message);
                    }
                }).catch(error => {
                    this.message  = '';
                    this.flash(this.message, 'error', {
                      timeout: 500000,
                    });
                });
            },
          
        }
        
    }
</script>