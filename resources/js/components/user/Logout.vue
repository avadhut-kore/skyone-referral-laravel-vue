<template>
</template>
<script>
    import usertitle from './UserTitle.vue';
    import Swal from 'sweetalert2';
    import { redirect} from'../../config';
    import { frontSiteUrl} from'../../config';
    export default {
        data(){
            return {
            }
        },
        mounted(){   
            this.logout();
        },
        methods: {
            logout(){   

                 axios.get('../logout').then(response => {
                            if(response.data.code == 200) {
                                //localStorage.removeItem('Authorization'); 
                                /*console.log(localStorage.getItem('Authorization'));
                                if(localStorage.getItem('Authorization')===null){
                                    this.$router.replace({ name:'login' });
                                } */
                                localStorage.removeItem('access_token'); 
                               // this.$router.replace({ name:'login' });
                                this.flash(response.data.message, 'success', {
                                  timeout: 5000,
                                });
                                window.location.href = redirect;
                               //window.location.href = frontSiteUrl;

                            } else {
                                this.errmessage  = response.data.message;
                                this.flash(this.errmessage, 'warning', {
                                    timeout: 100000,
                                });
                            }
                        }).catch(error => {
                            this.message  = error.response.data.message;
                            this.flash(this.message, 'error', {
                              timeout: 5000,
                            });
                        });
                /*Swal({
                    title: 'Are you sure ?',
                    text: "You want to logout!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                       
                    } else {
                        this.$router.go(-1);
                    }
                })*/
            },
        }
    }
</script>