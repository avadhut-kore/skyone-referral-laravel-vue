<template>
    <!-- <div> -->
        <!--  Left Sidebar Start -->
        <div class="left side-menu">
            <div class="sidebar-inner slimscrollleft">
                <form class="sidebar-search">
                    <div class="">
                        <input type="text" class="form-control search-bar" placeholder="Search...">
                    </div>
                    <button type="submit" class="btn btn-search">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
                <div class="user-details">
                    <!-- <div class="text-center">
                            <img src="admin/images/users/avatar-1.jpg" alt="" class="img-circle">
                        </div> -->
                    <div class="user-info">
                        <div class="dropdown">
                            <span class="profile-name">Admin</span>

                        </div>
                    </div>
                </div>
                <!--- Divider -->
                <!--- navigations -->
                <div id="sidebar-menu" style="overflow: hidden !important;">
                   
                    <ul>
                        <li v-for="(nav, index) in navigations" class="has_sub" :class="{'nav-active':showMenu === index}">
                            <a class="waves-effect" :href="((nav.childmenu.length > 0)?'javascript:void(0)':'skyoneadmin#/'+nav.parentmenu.parent_path)" @click="addExpandClass(index)">
                                <i v-bind:class="nav.parentmenu.parent_icon_class"></i>
                                <span> {{nav.parentmenu.parent_menu}} </span>
                                 
                                 <span v-if="nav.parentmenu.parent_menu =='Enquiry'" class="has_sub" >({{equiry_count}} )</span>

                                <span class="pull-right" v-if="nav.childmenu.length > 0">
                                    <i class="mdi mdi-chevron-right"></i>
                                </span>
                            </a>
                            <ul class="list-unstyled" v-if="nav.childmenu.length > 0">
                                <li v-for="sub_nav in nav.childmenu">

                                    <a :href="'skyoneadmin#/'+sub_nav.path">{{sub_nav.menu}}</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!---/. navigations -->
                <div class="clearfix"></div>
            </div>
            <!-- end sidebarinner -->
        </div><!-- Left Sidebar End -->
<!--     </div> -->

</template>

<script>
    import { apiAdminHost } from'./../../config';
    export default {
        data() {
            return {
                navigations:'',
                showMenu:'',
                equiry_count:'0'
            }
        },
        mounted() {
            this.getNavigations();
            this.enquiries_report_count();
        },
        methods: {
            getNavigations(){
                axios.post('/navigations',{
                }).then(resp => {                  
                    this.navigations = resp.data.data;
                }).catch(err => {
                    
                })
            },
            addExpandClass(element) {
                if (element === this.showMenu) {
                    this.showMenu = '0';
                } else {
                    this.showMenu = element;
                }
            },
            enquiries_report_count(){
              axios.get(apiAdminHost+'send-enquiry-count').then(response => {
               
                        this.equiry_count=response.data.data.count
                  
                    

                }).catch(error => {
               
                });
            }
        }
    }
</script>