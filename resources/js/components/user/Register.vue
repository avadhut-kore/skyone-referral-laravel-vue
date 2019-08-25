<template>
    <div class="login-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-8">
                    <div class="form-input-content">
                        <div class="card">
                            <div class="card-body">
                                <div class="logo text-center">
                                    <a href="/">
                                        <img src="public/images/logo.png" alt="">
                                    </a>
                                </div>
                                <h2 class=" m-t-15">Personal details</h2>
                                <hr class="myclhr" />  
                                <form class="m-t-30 row labbledark">
                                    <div class="form-group col-md-4 col-12">
                                        <label>First Name</label>
                                        <input type="text" id="first_name" name="first_name" placeholder="Enter first_name" v-model="first_name" class="{ error: errors.has('first_name') } form-control" v-validate="'required|alpha_spaces'" autocomplete="off">
                                        <div class="tooltip2" v-show="errors.has('first_name')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('first_name')">{{ errors.first('first_name') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Last Name</label>
                                        <input type="text" id="last_name" name="last_name" placeholder="Enter last_name" v-model="last_name" class="{ error: errors.has('last_name') } form-control" v-validate="'required|alpha_spaces'" autocomplete="off">
                                        <div class="tooltip2" v-show="errors.has('last_name')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('last_name')">{{ errors.first('last_name') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>E-mail address</label>
                                        <input type="text" id="email" name="email" placeholder="Enter Email Id" v-model="email" class="{ error: errors.has('email') } form-control" v-validate="'required|email|max:50'" autocomplete="off">
                                        <div class="tooltip2" v-show="errors.has('email')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('email')">{{ errors.first('email') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                  
                                    <div class="form-group col-md-4 col-12">
                                        <label>Mobile Number</label>
                                        <input type="text" id="mobile_no" name="mobile_no" placeholder="Enter Mobile no." v-model="mobile_no" class="{ error: errors.has('mobile_no') } form-control" v-validate="'required|digits:10'" autocomplete="off">
                                        <div class="tooltip2" v-show="errors.has('mobile_no')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('mobile_no')">{{ errors.first('mobile_no') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4 col-12">
                                        <label>Profession</label>
                                        <input type="text" id="profession" name="profession" placeholder="Enter profession no." v-model="profession" class="{ error: errors.has('profession') } form-control" v-validate="'required'" autocomplete="off">
                                        <div class="tooltip2" v-show="errors.has('profession')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('profession')">{{ errors.first('profession') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4 col-12">
                                        <label>City</label>
                                        <input type="text" id="city" name="city" placeholder="Enter city no." v-model="city" class="{ error: errors.has('city') } form-control" v-validate="'required'" autocomplete="off">
                                        <div class="tooltip2" v-show="errors.has('city')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('city')">{{ errors.first('city') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-4 col-12">
                                        <label> Password</label>
                                        <input type="password" id="password" name="password" placeholder="Enter Password" v-model="password" class="{ error: errors.has('password') } form-control" v-validate="'required'" v-on:keyup="onPasswordClick" autocomplete="off">

                                        <div v-if="flag_for_hide_validation_messege" class="tooltip2">
                                            <div class="  error-msg-size tooltip-inner ">
                                                <span class="validation-heading">Password must be:</span>
                                                <ul class="tooltipwidth">
                                                    <li id="length" v-bind:class="greater_than_6" >Be at least 8 characters minimum and 15 maximum
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tooltip2" v-show="errors.has('password')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('password')">{{ errors.first('password') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Confirm Password</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" v-model="password_confirmation" class="{ error: errors.has('password_confirmation') } form-control" v-validate="'required'" v-on:keyup="matchpassword" autocomplete="off">
                                        <div class="tooltip2" v-show="errors.has('password_confirmation')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('password_confirmation')">{{ errors.first('password_confirmation') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                 
                                    <div class="tags_clear"></div> 
                                    <div class="text-center col-md-12">
                                        <button v-on:click="register" id="register" type="button" class="btn btn-primary">Sign Up</button>
                                    </div>
                                </form>
                                <div class="text-center">
                                    <p class="m-b-0 m-t-10">Have an account?
                                    <router-link tag="li" :to="{ name: 'login' }"><a>Login</a>
                                    </router-link>
                                    </p>
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
        data() {
            return {
                email: '',
                password: '',
                first_name: '',
                last_name: '',
                user_id: '',
                mobile_no: '',
                profession: '',
                password_confirmation: '',
                country: 'IN',
                countries: [],
                userexistmsg: '',
                one_letter: '',
                one_capital_letter: '',
                special_char: '',
                starting_with_letter: '',
                greater_than_6: '',
                one_number: '',
                flag_for_hide_validation_messege: '',
                success_msg: '',
                optVerified :false,
                otpSent : false,
                otp : '',
                otpVerified : false,
                useractive : true,
                usermsg : "",
                city: "",
            }
        },
        computed: {
            
        },
        mounted(){
            if((this.$route.query.ref_id != undefined) && (this.$route.query.ref_id != '')){
                this.ref_user_id  = this.$route.query.ref_id;
                this.checkuserexist();
            }

            if(this.$route.params.pin){
                this.pin_number = this.$route.params.pin;
            }
            // Validation variables
            this.one_letter = 'invalid',
            this.greater_than_6 = 'invalid',
            this.one_capital_letter = 'invalid',
            this.special_char = 'invalid',
            this.one_number = 'invalid',
            this.starting_with_letter = 'invalid',
            this.flag_for_hide_validation_messege = false
            
        },
        created() {
            //this.fetchCountries();
        },
        methods: {
            register() {
                axios.post('register', {
                    first_name: this.first_name,
                    last_name: this.last_name,
                    email: this.email,
                    mobile_no: this.mobile_no,
                    country: 'IN',
                    user_id: this.user_id,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    city : this.city,
                    profession:this.profession
                }).then(response => {
                   
                   if (response.data.code == 201 || response.data.code == 200) {
                        this.success_msg = response.data.message;
                        this.$toaster.success(this.success_msg);
                        this.$router.push({name: 'login'});
                    }else{
                    
                        this.$toaster.error(response.data.message);
                         //this.message = '';
                       
                    }

                }).catch(error => {
                       this.$toaster.error(response.data.message);
                 alert();
                    
                });
            },
            fetchCountries() {
                axios.get('country').then(response => {
                    this.countries = response.data.data;
                }).catch(error => {
                    this.message = '';
                    this.flash(this.message, 'error', {
                        timeout: 500000,
                    });
                });
            },
            checkuserexist() {
                axios.post('checkuserexist', {
                    request_type : 'register',
                    user_id: this.ref_user_id,
                }).then(response => {
                    if (response.data.code == 200) {
                        this.useractive=true;
                    }else{
                        this.useractive=false;
                        this.usermsg=response.data.message;
                    }
                }).catch(error => {
                    this.message = '';
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
                if (this.password.substring(0, 1) == this.password.match(/[A-z]/)) {
                    this.starting_with_letter = 'valid';
                }
                if ((this.password.length >= 8) && (this.password.length <= 15)) {
                    this.greater_than_6 = 'valid';
                }
                if (this.password.match(/[a-z]/)) {
                    this.one_letter = 'valid';
                }
                if (this.password.match(/[A-Z]/)) {
                    this.one_capital_letter = 'valid';
                }
                if (this.password.match(/\d/)) {
                    this.one_number = 'valid';
                }
                if (this.password.match(/[^a-zA-Z0-9\-\/]/)) {
                    this.special_char = 'valid';
                }
                if (this.password.match(/\s/g)) {
                    this.special_char = 'invalid';
                }
                if (this.greater_than_6 === 'valid') {
                    this.flag_for_hide_validation_messege = false;
                } else {
                    this.errors.add({
                        field: 'password',
                        //msg: 'password not valid'
                    });
                }
            },
            matchpassword() {
                if (this.password !== this.password_confirmation) {
                    this.errors.add({
                        field: 'password_confirmation',
                        msg: 'Confirm password does not match with password'
                    });
                } else {
                }
            },
        }
    }
</script>   