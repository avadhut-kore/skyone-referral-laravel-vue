<template>
    <div id="main-wrapper"  v-if="token">
        <!-- header -->
        <div class="header">
            <div class="nav-header">
                <div class="brand-logo">
                    <router-link tag="a" class="breadcrumb-item" :to="{ name: 'dashboard' }">
                        <span class="brand-title"><img src="public/images/logo1.png" alt=""></span>
                        <span class="logo-mob img-fluid"> <img src="public/images/small-logo.png" style="padding: 0px 0px;"/> </span>
                    </router-link>
                </div>
                <div class="nav-control">
                    <div class="hamburger"><span class="line"></span>  <span class="line"></span>  <span class="line"></span>
                    </div>
                </div>
            </div>
            <div class="header-content">
                <div class="header-right">
                    <ul>
                      <li class="icons mt25"> <button type="button" class="btn btn-theme m-b-10 m-l-5 uid"><i class="fa fa-user"></i> {{user_id}}</button>
                              </li> 
                        <!-- <router-link tag="li" :to="{ name: 'logout' }" class="icons">
                            <a><i class="fa fa-sign-out"></i></a>
                        </router-link> -->
                    </ul>
                </div>
            </div>
        </div>
        <navlink></navlink>
        <div class="content-body">
            <transition name="fade">
                <router-view></router-view>
            </transition>
        </div>
        <!-- #/ header -->
        <!-- footer -->
        <div class="footer">
            <div class="copyright">
                <p class="text-center">Copyright &copy; {{ new Date().getFullYear() }}<a href="/" target="_blank"> SkyOne </a> </p>
            </div>
        </div>
    </div>
    <div v-else>
        <transition name="fade">
            <router-view></router-view>
        </transition>
    </div>
</template>

<script>
    import moment from 'moment';
    import navlink from './components/Navigation.vue';    
    export default{
        components:{
          navlink  
        },
        data(){
            return {
                user_id:'',
                server_information : {
                    server_time : null,
                    current_time : null,
                    ip_address : null,
                },
                token:null,
            }
        },
        mounted() {
            this.token = localStorage.getItem('access_token');
            if(this.token != '' && this.token != null){
                this.checkUserAccess();
               
            }
            // this.getProfileDetails();
        },
        methods: {
            getServerInformation(){
                axios.get('../server-information')
                .then(response => {
                    if(response.data.code  == 200){
                        this.server_information  = response.data.data;
                    }
                });
            },
            checkUserAccess(){
                this.token = localStorage.getItem('access_token');
                axios.get('../check-user-access')
                .then(response => {
                    console.log(response);
                    if(response.data.code  == 200){
                        this.user_id=response.data.data.user_id;
                        if(response.data.data.type == 'Admin'){
                            localStorage.clear();
                           // this.$router.push({ path: '/403'});
                           this.$router.go({ name:'login' });
                        }

                        //this.getServerInformation();
                    } else {
                        console.log('1');
                        localStorage.clear();
                       // this.$router.push({ path: '/403'});
                       this.$router.go({ name:'login' });
                    }
                }).catch(error => {
                    localStorage.removeItem('access_token'); 
                    console.log('2');
                    this.$router.go({ name:'login' });
                    //this.$toastr.error("Something went wrong");
                });
            }
        },

    }
</script>