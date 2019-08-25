<template>
    <div class="login-bg h-100">
        <div class="container h-100">
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
                                <h4 class="text-center m-t-15">Reset Password</h4>
                                <form class="m-t-10 m-b-10">
                                    <div class="form-group giveposition">
                                        <label>New Password</label>
                                        <input type="password" id="password" name="password" placeholder="Enter Password" v-model="password" class="{ error: errors.has('password') } form-control" v-validate="'required'" v-on:keyup="onPasswordClick">
                                        <div v-if="flag_for_hide_validation_messege" class="tooltip2">
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
                                                    <li id="length" v-bind:class="greater_than_6" >Be at least 8 characters minimum and 15 maximum
                                                    </li>
                                                   <!--  <li id="space" v-bind:class="special_char" >be use [~,!,@,#,$,%,^,&,*,-,=,.,;,']
                                                    </li> -->
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tooltip2" v-show="errors.has('password')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('password')">{{ errors.first('password') }}</span>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="form-group giveposition">
                                        <label>Confirm Password</label>
                                       <!--  <input type="password" class="form-control" placeholder="Password"> -->
                                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Enter Confirm Password" v-model="password_confirmation" class="{ error: errors.has('password_confirmation') } form-control" v-on:keyup="matchpassword" v-validate="'required'">
                                        <div class="tooltip2" v-show="errors.has('password_confirmation')">
                                            <div class="tooltip-inner">
                                                <span v-show="errors.has('password_confirmation')">{{ errors.first('password_confirmation') }}</span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-row">


                                    </div>
                                    <div class="text-center m-b-15 m-t-15">
                                        <button :disabled="errors.any() || !isComplete" @click="resetpassword" id="login" type="button" class="btn btn-primary">Reset Password</button>
                                        <!--   <button type="submit" class="btn btn-primary">Sign in</button> -->
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
        data() {

            return {
                password: '',
                password_confirmation: '',
                userexistmsg: '',
                one_letter: '',
                one_capital_letter: '',
                special_char: '',
                starting_with_letter: '',
                greater_than_6: '',
                one_number: '',
                flag_for_hide_validation_messege: '',
                resettoken : null
            }
        },
        computed: {
            isComplete() {
                return this.password && this.password_confirmation;
            }
        },
        mounted(){   
            if((this.$route.query.resettoken == undefined) || (this.$route.query.resettoken == '')){
                this.$toaster.error("Invalid route");
                this.$router.replace({ name:'forget-password' });
            }
            // Validation variables
            this.one_letter = 'invalid',
            this.greater_than_6 = 'invalid',
            this.one_capital_letter = 'invalid',
            this.special_char = 'invalid',
            this.one_number = 'invalid',
            this.starting_with_letter = 'invalid',
            this.flag_for_hide_validation_messege = false;
        },
        created() {
        },
        methods: {
            resetpassword() {
                axios.post('reset-password', {
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                    resettoken: this.$route.query.resettoken,
                }).then(response => {
                    if(response.data.code == 200) {
                        this.$toaster.success(response.data.message);
                        this.$router.replace({ name:'login' });
                    } else {
                        this.errmessage  = response.data.message;
                    }
                }).catch(error => {
                    this.message = '';
                    
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
                // tslint:disable-next-line:max-line-length
                /* if (this.one_letter === 'valid' && this.greater_than_6 === 'valid' && this.one_capital_letter === 'valid' && this.special_char === 'valid' && this.one_number === 'valid' && this.starting_with_letter === 'valid') {
                    this.flag_for_hide_validation_messege = false;
                } */
                if (this.greater_than_6 === 'valid' ) {
                    this.flag_for_hide_validation_messege = false;
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