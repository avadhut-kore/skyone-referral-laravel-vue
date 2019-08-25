    <template>
    <!-- sidebar -->
    <div class="nk-sidebar">
        <div class="nk-nav-scroll">
            <ul class="metismenu" id="menu">
                <router-link tag="li"  :to="{ name: 'dashboard' }" class="">
                    <a  @click="hideNav" ><i class="icon-speedometer"></i> <span class="nav-text">Dashboard</span></a>
                </router-link>
                

                <li @click="hideNav">
                <router-link tag="li" :to="{ name: 'profile' }" class="">
                    <a><i class="icon-note"></i> <span class="nav-text">Profile</span></a>
                </router-link>
                </li>

             <li @click="hideNav">
                  <router-link tag="li" :to="{ name: 'logout' }" class="">
                            <a> <i class="fa fa-sign-out"></i> <span class="nav-text">Logout</span></a>
                 </router-link>
             </li>    

                <!-- <router-link tag="li" :to="{ name: 'all-transaction' }" class="">
                    <a><i class="icon-list"></i> <span class="nav-text">Transaction</span></a>
                </router-link> -->
            </ul>
        </div>
        <!-- #/ nk nav scroll -->
    </div>
    <!-- #/ sidebar -->
</template>
<script>
 export default{
        data(){
           
        },
        mounted() {
           
        },
        methods: {

            hideNav(){
                   //$('.v-light').removeClass('mini-nav');
                   var windowWidth = window.screen.width;
                 
                   if (windowWidth< 768) {
                $('.v-light').addClass('mini-nav');

                   }

            }
          
        }
        // auto refresh server time
        /*created: function () {
            this.token = localStorage.getItem('access_token');
            if(this.token != '' && this.token != null){
                setInterval(function () {
                    this.getServerInformation();
                }.bind(this), 20000); 
            }
        },*/
    }
</script>