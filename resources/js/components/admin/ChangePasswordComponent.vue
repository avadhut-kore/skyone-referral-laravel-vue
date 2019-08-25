<template>
    <!-- Start content -->
    <div class="content">
        <div class="">
            <div class="page-header-title">
                <h4 class="page-title">Change Password</h4>
            </div>
        </div>

        <div class="page-content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form v-on:submit.prevent="changePass" id="change-password-form">
                                            <div class="row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label> User Id </label>
                                                        <input type="text" id="user_id" name="user_id" placeholder="Enter User Id" v-model="user_id" class="{ error: errors.has('user_id') } form-control" v-validate="'required'" v-on:input="checkuserexist">
                                                        <div class="tooltip2" v-show="errors.has('user_id')">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('user_id')">{{ errors.first('user_id') }}</span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label> Fullname </label>
                                                        <input type="text" id="name" name="name" placeholder="Enter User Id" v-model="fullname" class="{ error: errors.has('name') } form-control" readonly>


                                                    </div>
                                                    <div class="form-group" style="position: relative;">
                                                        <label> New Password </label>
                                                        <input type="password" id="password" name="password" placeholder="Enter New password" v-model="password" class="{ error: errors.has('password') } form-control" v-validate="'required'" v-on:input="onPasswordClick">
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
                                                  <!-- <input class="form-control" type="password" name="password" placeholder="Enter New Password"  v-model="password"> -->
                                                    </div>
                                                    <div class="form-group" style="position: relative;">
                                                       <label>Confirm Password</label>
                                                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" v-model="password_confirmation" class="{ error: errors.has('password_confirmation') } form-control" v-validate="'required'" v-on:keyup="matchpassword">
                                                        <div class="tooltip2" v-show="errors.has('password_confirmation')">
                                                            <div class="tooltip-inner">
                                                                <span v-show="errors.has('password_confirmation')">{{ errors.first('password_confirmation') }}</span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light" :disabled="errors.any() || !isComplete "  >Update Password</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- col -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- Page content Wrapper -->
    </div><!-- content -->
</template>

<script>
    import { apiAdminHost } from'./../../config';
    export default {

        data() {
            return {
                user_id: '',
                password: '',
                re_type_password: '',
                custom_msg_class: '',
                userExistsMessage: '',
                hiddenuserid: null,
                id: '',
                fullname: '',
                password_confirmation : '',
                flag_for_hide_validation_messege: '',
                password_flag : ""
            }
        },
        computed: {
            isComplete() {
                return this.password && this.password_confirmation && this.user_id && this.password_flag;
            },
        },
        mounted() {
            this.flag_for_hide_validation_messege = false;
        },
        methods: {
            changePass() {
                axios.post('updateuserpassword', {
                    password: this.password,
                    id: this.hiddenuserid
                }).then(resp => {
                    if (resp.data.code === 200) {
                        $('#change-password-form').trigger("reset");
                        this.$toaster.success(resp.data.message);
                    } else {
                        this.$toaster.error(resp.data.message);
                    }
                }).catch(error => {
                    this.$toaster.error(error.response.data.message);
                });
            },
            checkuserexist() {

                axios.post('../checkuserexist', {
                    user_id: this.user_id,
                }).then(response => {
                    //console.log(response.data.code);
                    if (response.data.code == 404) {

                        //this.custom_msg_class = 'text-danger';
                        //this.userExistsMessage = response.data.message;  

                        this.errors.add({
                            field: 'user_id',
                            msg: 'User not available'
                        });

                    } else {
                        this.hiddenuserid = response.data.data.id;
                        this.fullname = response.data.data.fullname;

                        this.custom_msg_class = 'text-success';
                        this.userExistsMessage = response.data.message;
                    }
                }).catch(error => {

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

                // if (this.password.substring(0, 1) == this.password.match(/[A-z]/)) {
                //     this.starting_with_letter = 'valid';
                // }
                if ((this.password.length >= 8) && (this.password.length <= 15)) {
                    this.greater_than_6 = 'valid';
                }
                // if (this.password.match(/[a-z]/)) {
                //     this.one_letter = 'valid';
                // }
                // if (this.password.match(/[A-Z]/)) {
                //     this.one_capital_letter = 'valid';
                // }
                // if (this.password.match(/\d/)) {
                //     this.one_number = 'valid';
                // }
                // if (this.password.match(/[^a-zA-Z0-9\-\/]/)) {
                //     this.special_char = 'valid';
                // }
                // if (this.password.match(/\s/g)) {
                //     this.special_char = 'invalid';
                // }
               /* if (this.one_letter === 'valid' && this.greater_than_6 === 'valid' && this.one_capital_letter === 'valid' && this.special_char === 'valid' && this.one_number === 'valid' && this.starting_with_letter === 'valid') {
                    this.flag_for_hide_validation_messege = false;
                }*/if (this.greater_than_6 === 'valid' ) {
                    this.flag_for_hide_validation_messege = false;
                } else {
                    this.errors.add({
                        field: 'password',
                        msg: 'password not valid'
                    });
                }
            },
            matchpassword() {
                if (this.password !== this.password_confirmation) {
                    this.password_flag = false;
                    this.errors.add({
                        field: 'password_confirmation',
                        msg: 'Confirm password does not match with password'
                    });
                } else {
                    this.password_flag = true;
                }
            },
        }
    }
</script>