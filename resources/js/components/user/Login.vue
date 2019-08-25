<style>
    
.overlay {
  position: fixed;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  background-color: #1111118f;
  z-index: 1111;
}
.loader{
   position: fixed;
  z-index: 999;
  height: 2em;
  width: 2em;
  overflow: visible;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}
</style>
<template>
    <div class="login-bg h-100 h-100" id="login-page1">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-5">
                    <div class="form-input-content">
                        <div class="card">
                            <div class="card-body">
                                <div v-if="(!verify2fa) && (!verifymailotp)">
                                    <div class="logo text-center">
                                        <a href="/">
                                            <img src="public/images/logo.png" alt="" class="img-fluid">
                                        </a>
                                    </div>
                                    <h2 class=" m-t-15 text-center">Login</h2>
                                       
                                       <img class="overlay" style="display:none;"   ></div> 
                                       <div class="loader" style="display:none"></div>
                                    <form class="m-t-10 m-b-10 labbledark" v-on:submit.prevent="login">
                                        <div class="form-group giveposition">
                                            <label>Username</label>
                                              <input type="text" id="email" name="username" placeholder="Enter User Name" v-model="email" class="{ error: errors.has('email') } form-control" v-validate="'required'">
                                            <div class="tooltip2" v-show="errors.has('username')">
                                                <div class="tooltip-inner">
                                                    <span v-show="errors.has('username')">{{ errors.first('username') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group giveposition">
                                            <label>Password</label>
                                           <!--  <input type="password" class="form-control" placeholder="Password"> -->
                                            <input type="password" id="password" name="password" placeholder="Enter Password" v-model="password" class="{ error: errors.has('password') } form-control" v-validate="'required'">
                                            <div class="tooltip2" v-show="errors.has('password')">
                                                <div class="tooltip-inner">
                                                    <span v-show="errors.has('password')">{{ errors.first('password') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                               <!--  <div class="form-check p-l-0">
                                                    <input class="form-check-input" type="checkbox" id="basic_checkbox_1">
                                                    <label class="form-check-label" for="basic_checkbox_1">Check me out</label>
                                                </div> -->
                                            </div>
                                            <div class="form-group col-md-6 text-right">
                                                <router-link tag="li" :to="{ name: 'forget-password' }">
                                                     <a>Forgot Password?</a>
                                                </router-link>
                                            </div>
                                            
                                        </div>
                                        <div class="text-center m-b-15 m-t-15">
                                            <button :disabled="errors.any() || !isComplete"  id="login" type="submit" class="btn btn-primary">Sign in</button>
                                      <!--  <button type="submit" class="btn btn-primary">Sign in</button>  -->
                                        </div>
                                    </form>
                                
                                    <div class="text-center">
                                        <p class="m-t-30">Dont have an account? 
                                            <router-link tag="li" :to="{ name: 'register' }">
                                                 <a>Register Now</a>
                                            </router-link>
                                        </p>
                                    </div>
                                </div>
                                <form class="form-horizontal m-t-20" v-show="verify2fa" v-on:submit.prevent="verify2Fa">                      
                                    <div class="form-group">    
                                        <div class="col-xs-12">
                                            <input class="{ error: errors.has('googleotpEnable') } form-control" type="password" required="" placeholder="Enter 2Fa Code" v-model="googleotp" name="googleotp" v-validate="'required|numeric|min:6|max:6'">
                                            <div class="tooltip2" style = "top:48" v-show="errors.has('googleotp')">
                                                <div class="tooltip-inner">
                                                    <span v-show="errors.has('googleotp')">{{ errors.first('googleotp') }}</span>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12"></div>
                                    </div>
                                    <div class="form-group text-center m-t-40">
                                        <div class="col-xs-12">
                                            <button class="btn btn-primary btn-block btn-lg waves-effect waves-light" type="submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
                                <form class="form-horizontal m-t-20" v-show="verifymailotp" v-on:submit.prevent="verifyOtp">                      
                                    <div class="form-group">    
                                        <div class="col-xs-12">
                                            <input class="{ error: errors.has('otp') } form-control" type="password" required="" placeholder="Enter Otp" v-model="otp" name="otp" v-validate="'required|numeric|min:6|max:6'">
                                            <div class="tooltip2" style = "top:48" v-show="errors.has('otp')">
                                                <div class="tooltip-inner">
                                                    <span v-show="errors.has('otp')">{{ errors.first('otp') }}</span>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12"></div>
                                    </div>
                                    <div class="form-group text-center m-t-40">
                                        <div class="col-xs-12">
                                            <button class="btn btn-primary btn-block btn-lg waves-effect waves-light" type="submit">Submit</button>
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
    import { redirect} from'../../config';
    export default {
        data(){
            return {
                email : '',
                password : '',
                success_msg:'',
                verify2fa : false,
                googleotp : null,
                token : null,
                verifymailotp : null,
                otp : null,
            }
        },
        computed: {
            isComplete() {
                return this.password && this.email;
            },
        },
        mounted(){ 
        },
        methods: {
            login(){
                $(".overlay").show();
                $(".loader").show();
                axios.post('login', {                  
                    user_id:this.email,
                    password:this.password,   
                    //otp:'mobile',                                   
                }).then(resp => {
                    /*if(response.data.code == 200) {
                        //Redirect to dashboard
                        localStorage.setItem('Authorization',true); 
                        window.location.href = apiHost;
                        this.flash(response.data.message, 'success', {
                          timeout: 500000,
                        });
                    } else {
                        localStorage.setItem('Authorization',false);           
                    }*/
                    if(resp != undefined){
                        if(resp.data.code == 200) {
                            let userinfo = resp.data.data;
                            if(resp.data.data.access_token){
                                this.token = resp.data.data.access_token;

                                //this.$toastr.success(resp.data.message);
                                if(userinfo.google2faauth == "TRUE"){
                                    this.verify2fa = true;
                                    this.verifymailotp = false;
                                }else if(userinfo.mailotp == "TRUE"){
                                    this.verifymailotp = true;
                                    this.verify2fa = false;
                                } else {
                                    localStorage.setItem('access_token', resp.data.data.access_token);
                                     localStorage.setItem('type', 'user'); 
                                    const d = new Date().getTime();
                                    const time = d + 1800000;
                                    localStorage.setItem('date', JSON.stringify(time));
                                    $(".overlay").hide();
                                    $(".loader").hide();
                                    window.location.href = redirect;
                                }
                                //this.$router.push({ path:'dashboard'});
                                //this.$toastr.success(resp.data.message);
                            } else {
                                this.$toastr.error(resp.data.message);
                            }
                        } else {
                            this.$toaster.error(resp.data.message);
                        }
                    }
                }).catch(error => {
                    this.$toastr.error("Something went wrong");
                });
            },
            
            verify2Fa(){
                axios.post('user/2fa/validatelogintoken',
                {
                    googleotp:this.googleotp
                },
                {
                    headers: { 'Authorization':  "Bearer "+this.token }
                }).then(resp => {
                    if(resp.data.code == 200) {
                        if(this.token){
                            this.sendotp = false;
                            localStorage.setItem('access_token', this.token);
                            window.location.href = redirect;
                        } else {
                            this.$toaster.success(response.data.message);
                        }
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    this.$toastr.error("Something went wrong");
                })
            },
            verifyOtp(){
                axios.post('checkotp',
                {
                    otp:this.otp
                },
                {
                    headers: { 'Authorization':  "Bearer "+this.token }
                }).then(resp => {
                    //store the token in local storage
                    if(resp.data.code == 200) {
                        if(this.token){
                            this.sendotp = false;
                            localStorage.setItem('access_token', this.token);
                            window.location.href = redirect;
                            this.$router.push({ path:'dashboard'});
                        } else {
                            this.$toaster.success(response.data.message);
                        }
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                    this.$toastr.error("Something went wrong");
                })
            }
        }
    }
</script>	