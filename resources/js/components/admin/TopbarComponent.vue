<template>
	<!-- top bar start -->
    <div class="topbar">
        <!-- LOGO -->
        <div class="topbar-left">
            <!--<a href="index.php" class="logo"><span>Up</span>Bond</a>-->
            <!--<a href="index.php" class="logo-sm"><span>U</span></a>-->
            <a class="logo">
                <img src="public/admin/images/logo.png" height="35" alt="logo">
            </a>
            <a class="logo-sm">
                <img src="public/admin/images/logo_sm.png" height="30" alt="logo">
            </a>
        </div>
        <!-- Button mobile view to collapse sidebar menu -->
        <div class="navbar navbar-default" role="navigation">
            <div class="container">
                <div class="">
                    <div class="pull-left">
                        <button type="button" class="button-menu-mobile open-left waves-effect waves-light">
                            <i class="ion-navicon"></i>
                        </button>
                        <span class="clearfix"></span>
                    </div>
                    <ul class="nav navbar-nav navbar-right pull-right server hidden-xs">
                          <li class="mt25"> <a href="/"  target="_blank"><i class="fa fa-home"></i></a> 
                              </li>
                        <router-link tag="li" :to="{ name: 'logout' }" class="header-icon dib logoutBtn logout-p-r-50">
                            <a><span class="btn btn-primary">Logout</span></a>
                        </router-link>
                    </ul>
                   
                </div><!--/.nav-collapse -->
            </div>
        </div>
    </div>            
            <!-- top bar end -->
</template>

<script>
    export default {
        data() {
            return {
                token:null,
            }
        },
        mounted() {
            this.token = localStorage.getItem('access_token');
        },
        methods: {
            
        },
       
    }
</script>