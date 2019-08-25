import VueRouter from 'vue-router';
import Profile from './components/user/Profile.vue';
import Dashboard from './components/user/Dashboard.vue';
import Login from './components/user/Login.vue';
import Register from './components/user/Register.vue';
import ForgotPassword from './components/user/ForgotPassword.vue';
import Thankyou from './components/user/Thankyou.vue';
import ResetPassword from './components/user/ResetPassword.vue';
import Logout from './components/user/Logout.vue';
import Error403 from './components/user/Error403.vue';
import RouteNotFound from './components/user/RouteNotFound.vue';
import ProductComponent from './components/user/ProductComponent.vue';
import {currentLoginStatus,apiHost} from'./config';
let routes = [];
let base = '/user/';
if(currentLoginStatus){
    routes = [
        {
            path: '/',
            redirect: 'dashboard',
            name:'/dashboard'
        },
        {
            path: '/login',
            redirect: 'dashboard',
            name:'login'
        },
        /*{
            path: '/register',
            component: Register,
            name:'register'
        },*/
        {
            path: '/thankyou',
            component: Thankyou,
            name:'thankyou'
        },
        {
            path: '/resetpassword',
            component: ResetPassword,
            name:'resetpassword'
        },
        /*{
            path: '/forget-password',
            component: ForgotPassword,
            name:'forget-password'
        },*/
        {
            path: '/profile',
            component: Profile,
            name:'profile',
            meta: {
                title :'Profile',    
                breadcrumb:'Profile'
            }
        },
        {
            path: '/product/:category',
            component: ProductComponent,
            name:'product',
            meta: {
                title :'category',    
                breadcrumb:'Product'
            }
        },
        {
            path: '/dashboard',
            component: Dashboard,
            name:'dashboard'
        },
        {
            path: '/logout',
            component: Logout,
            name:'logout'
        },
        {
            path: '/403',
            component: Error403,
            name:'403'
        },
        {
            path: '*',
            component: RouteNotFound,
            meta: {
                breadcrumb :null,    
                title:'Page Not Found'
            }
        }
    ];
} else {
    routes = [
        {
            path: '/',
            redirect: 'login',
            name:'/login'
        },
        {
            path: '/login',
            component: Login,
            name:'login'
        },
         {
            path: '/register',
            component: Register,
            name:'register'
        },
        {
            path: '/thankyou',
            component: Thankyou,
            name:'thankyou'
        },
        {
            path: '/resetpassword',
            component: ResetPassword,
            name:'resetpassword'
        },
        {
            path: '/forget-password',
            component: ForgotPassword,
            name:'forget-password'
        },
        {
            path: '*',
            component: Login,
            redirect: 'login',
        }
    ];
}
export default new VueRouter({
    base :base,
    routes,
});