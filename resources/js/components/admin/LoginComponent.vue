<template>
	<div>
		<!-- Start content -->
	    <div class="accountbg"></div>
	    <div class="wrapper-page accountbg">
	        <div class="panel panel-color panel-primary panel-pages">
	            <div class="panel-body">
	                <h3 class="text-center m-t-0 m-b-15">
	                    <a href="#" class="logo logo-admin">
	                        <img src="public/admin/images/loginLogo.png" alt="logo">
	                    </a>
	                </h3>

	                <h4 class="text-muted text-center m-t-0">
	                    <b>Admin</b>
	                </h4>

	                <form class="form-horizontal m-t-20" v-show="sendotp == true" v-on:submit.prevent="login" id="loginform">
	                    <div class="form-group">
	                        <div class="col-xs-12">
	                            <input name="user_id" class="form-control" type="text" required placeholder="Enter Username" v-model="user.user_id">
	                        </div>
	                    </div>
	                    <div class="form-group">    
	                        <div class="col-xs-12">
	                            <input name="password" class="form-control" type="password" required="" placeholder="Enter Password" v-model="user.password">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <div class="col-xs-12"></div>
	                    </div>
	                    <div class="form-group text-center m-t-40">
	                        <div class="col-xs-12">
	                            <button class="btn btn-primary btn-block btn-lg waves-effect waves-light" type="submit">Log In</button>
	                        </div>
	                    </div>

	                </form>

	                <form class="form-horizontal m-t-20" v-show="sendotp == false" v-on:submit.prevent="checkotp" id="otpform">	                   
	                    <div class="form-group">    
	                        <div class="col-xs-12">
	                            <input name="otp" class="form-control" type="otp" required="" placeholder="Enter otp" v-model="otp">
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

	                <form class="form-horizontal m-t-20" v-show="google2fa == true" v-on:submit.prevent="checkotp" id="faform">	                   
	                    <div class="form-group">    
	                        <div class="col-xs-12">
	                            <input name="g2fa" class="form-control" type="g2fa" required="" placeholder="Enter google otp" v-model="g2fa">
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
	    <!-- Begin page -->
    </div>
</template>

<script>
	import axios from 'axios';
	import { adminHost} from'../../config';
    export default {
        data(){
           return {
                user:{
                    user_id:'',
                    password:''
                },
				sendotp:'',
				otp:'',
				messsage:'',
				token:'',
				masterpassword:'',
				google2fa:'',
				otpmode:'',
				g2fa:'',
            } 
        },
        mounted() {

        	if(this.sendotp == undefined || this.sendotp == '' ){
        		this.sendotp = true;

        	}
        	this.google2fa = false;
         /*   this.message = this.$route.params.msg;
            this.sendotp = this.$route.params.sendotp;
            console.log(this.sendotp);
            console.log(this.message);

        	if(this.sendotp == undefined || this.sendotp == '' ){
        		this.sendotp = true;
        	}else{
        		this.sendotp = false;
        	}
        	if(this.message == undefined || this.message == ''){

        	}else{
        		this.message = this.$route.params.msg,
        		this.token = this.$route.params.tok,
                this.$toaster.success(this.message)
        	}*/
        },
        methods: {
            login(){
            	localStorage.setItem('typelogin', 'Admin');
                axios.post('login',{
                    user_id:this.user.user_id,
                    password:this.user.password,
                    admin : "admin",
                    //otp:'mobile',

                }).then(resp => {
                   
                	//store the token in local storage
	                if(resp.data.code == 200) {
	                	//console.log(resp.data.data.google2faauth);
	                    if(resp.data.data.access_token){

	                    	 if(resp.data.data.mailotp == 'TRUE'){
                            this.sendotp = false;

	                    	this.token = resp.data.data.access_token;
                            this.$toaster.success(response.data.message);
	                    	 }else if(resp.data.data.google2faauth == 'TRUE'){
	                    	 	//console.log('hii');
                                    this.sendotp = false;

                                    this.google2fa = true;
                                    this.sendotp = 'none';



	                    	 }else{
	                    	 	 localStorage.setItem('access_token', resp.data.data.access_token);
	                    	     window.location.href = adminHost;	                    	
	                    	     this.$router.push({ path:'login',params: {sendotp: false,msg:resp.data.message,tok:resp.data.data.access_token}});
	                   		      this.$toastr.success(resp.data.message);
	                    	 }
	                    
                             
                    		//localStorage.setItem('access_token', resp.data.data.access_token);
	                    	//window.location.href = adminHost;
	                    	
	                    	/*this.$router.push({ path:'login',params: {sendotp: false,msg:resp.data.message,tok:resp.data.data.access_token}});*/
	                   		//this.$toastr.success(resp.data.message);
	                    } else {
							 this.$toaster.success(response.data.message);
	                    }
	                }else {
                         
	                	this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                	this.$toastr.error("Something went wrong");
                })
            },
            checkotp(){
		     	axios.post('checkotpadminlogin',
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
	                    	window.location.href = adminHost;
	                    	this.$router.push({ path:'dashboard'});
	                    	 this.$toaster.success(response.data.message);
	                   		//this.$toastr.success(resp.data.message);
	                    } else {
	                    	 this.$toaster.success(response.data.message);
							//this.$toastr.error(resp.data.message);
	                    }
	                } else {
	                	this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                	this.$toastr.error("Something went wrong");
                })
            },
            check2fa(){

     axios.post('2fa/validatelogintoken',
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
	                    	window.location.href = adminHost;
	                    	this.$router.push({ path:'dashboard'});
	                    	 this.$toaster.success(response.data.message);
	                   		//this.$toastr.success(resp.data.message);
	                    } else {
	                    	 this.$toaster.success(response.data.message);
							//this.$toastr.error(resp.data.message);
	                    }
	                } else {
	                	this.$toaster.error(resp.data.message);
                    }
                }).catch(err => {
                	this.$toastr.error("Something went wrong");
                })
            },
            reset() {
                this.user.user_id = '';
                this.user.password = '';
            }
        }
    }
</script>