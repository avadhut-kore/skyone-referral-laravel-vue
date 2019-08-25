<template>
    <div id="wrapper" v-if="token">
        <!-- Top Bar Start -->
        <topbar></topbar>
        <navlink></navlink>
        <!-- Left Sidebar End -->
        <!-- Start Main Content here -->
        <div class="content-page">
            <transition name="fade">
                <router-view></router-view>
            </transition>
        </div>
        <footer class="footer">
            © {{ new Date().getFullYear() }} SkyOne
        </footer>
        <!-- End Main Content here -->
    </div>
    <div v-else>
        <transition name="fade">
            <router-view></router-view>
        </transition>
    </div>
</template>
<script>
    import navlink from './components/admin/NavigationComponent.vue';
    import topbar from './components/admin/TopbarComponent.vue';
    import { redirect} from'./config';
    export default {
        data(){
            return {
                token:''
            }
        },
        components:{
            navlink,
            topbar,
        },
        mounted() { 
            this.token = localStorage.getItem('access_token');
            this.checkUserAccess();
        },
        methods : {
            checkUserAccess(){
                axios.get('../check-user-access')
                .then(response => {
                    if(response.data.code  == 200){
                        if(response.data.data.type != 'Admin'){
                            window.location.href = redirect;
                            this.$toaster.error("Access Denied. You don't have permission to access.");
                            //this.$router.push({ path: '/403'});
                        }
                    } else {
                        this.$router.push({ path: '/403'});
                    }
                }).catch(error => {
                    this.$router.push({ path: '/403'});
                });
            }
        }
    }
</script>
