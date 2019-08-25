<template>
</template>
<script>
    import Swal from 'sweetalert2';
    import { adminHost} from'../../config';
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
                Swal({
                    title: 'Are you sure ?',
                    text: "You want to logout!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes!',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.value) {
                        axios.get('../logout').then(response => {
                            if(response.data.code == 200) {
                                localStorage.removeItem('access_token'); 
                                localStorage.removeItem('typelogin'); 
                                this.$router.replace({ name:'login' });
                                this.flash(response.data.message, 'success', {
                                    timeout: 500000,
                                });
                                window.location.href = adminHost;
                            } else {
                                this.errmessage  = response.data.message;
                                this.flash(this.errmessage, 'warning', {
                                    timeout: 100000,
                                });
                            }
                        }).catch(error => {
                            this.message  = error.response.data.message;
                            this.flash(this.message, 'error', {
                              timeout: 500000,
                            });
                        });
                    }else {
                        this.$router.go(-1);
                    }
                })
            },
        }
    }
</script>