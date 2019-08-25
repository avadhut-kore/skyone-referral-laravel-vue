<template>
	 <div id="thankyou-panel"class="login-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-7">
                    <div class="form-input-content">
                        <div class="card">
                            <div class="card-body">
                             <div class="logo text-center">
                                   <a href="/" target="_blank">
                                        <img src="public/images/logo.png" alt="">
                                    </a>
                                </div> 
                                <h4 class="text-center m-t-15">Dear User,</h4>

                                <p class="text-center">We are glad to welcome you in our group..!!!! We have structured our concept in a way to meet your specific need at different stages of this phase. Here you will get all the relevant information, features and facilities about your business. </p>
                                <div class="row">
                                <div class="m-t-30 m-b-30 col-md-12">
                                    <div class=" row">
                                        <div class="col-md-12 text-center">
                                            <h3><strong>UserName : </strong> {{this.user_id}}</h3>
                                        </div>

                                        <!--  <div class="col-md-12 text-center">
                                            <h3><strong>Password : </strong> {{this.password}}</h3>
                                        </div> -->
                                      <!--   <label class="col-md-6 line-hght-40"> <strong> Your User Id </strong> </label>
                                        <div :html="user_id" class="form-control col-md-6 input-readonly " ></div> --> 
                                    </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div><br>
                                <div class="text-center col-md-12">
                                 <!--     <div class="text-center">
                                        <router-link tag="li" :to="{ name: 'login' }">
                                            <a class="btn btn-info waves-effect waves-light ">
                                             <i class="fa fa-mail-reply"></i> &nbsp;Login
                                            </a>
                                        </router-link>
                                     </div> -->
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
                msg:''
            }
        },
        mounted(){ 
            this.user_id = this.$route.params.user_id;
            if(this.user_id != undefined && this.user_id != null && this.user_id != ''){
                this.password = this.$route.params.password;
                this.msg = this.$route.params.msg;
                this.$toaster.success(this.msg);
            } else {
               this.$router.push({name: '403'});
            }
        },
        methods: {
            
        }
    }
</script>