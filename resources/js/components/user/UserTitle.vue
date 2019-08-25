<template>
    <div>
        <div class="row page-titles">
            <!-- <div class="col-12 col-md-6 p-0">
                <h4>Welcome, <span>{{user.fullname}} ({{user.user_id}})</span></h4>
            </div> -->
            <div class="col-12 col-md-6 p-0">
                <ol class="breadcrumb">
                   <!--  <li class="breadcrumb-item" @click="routeTo('dashboard')" tag="a">Dashboard</li> -->
                    <!-- <router-link tag="li" class="breadcrumb-item" :to="{ name: 'dashboard' }">
                        <a>Dashboard</a>
                    </router-link>
                    <li v-if="breadcrum != null" class="breadcrumb-item"><a href="javascript:void(0)">{{breadcrum}}</a> </li>
                    <li class="breadcrumb-item active"> {{pagetitle}}  </li> -->
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="profile">
                    <div class="profile-head">
                        <div class="photo-content">
                            <div class="cover-photo"> {{pagetitle}} </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
        data(){
            return {
                user : {
                    user_id : null,
                    fullname : null,
                    sponser_id : null,
                    email : null,
                },
                pagetitle : null,
                breadcrum : null
            }
        },
        mounted() {
            this.getProfileDetails();
            this.cat = this.$route.params.category;
        },
        methods: {
            getProfileDetails(){
                this.pagetitle = this.$route.meta.title;
                this.breadcrum = this.$route.meta.breadcrumb;
                axios.get('profile')
                .then(response => {
                    this.user  = response.data.data;
                    if(this.user.type == "Admin"){
                        this.$router.push({ path: '/403'});
                    }
                });
                if(this.$route.params.category != ''){
                    this.pagetitle = this.$route.params.category;
                }
            },
            routeTo (pRouteTo) {
                this.$router.push(pRouteTo);
            },
        }
    }
</script>