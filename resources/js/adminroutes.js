import VueRouter from 'vue-router';
// ADMIN
import DashboardComponent from './components/admin/Dashboard.vue';
import LoginComponent from './components/admin/LoginComponent.vue';
import ManageUser from './components/admin/ManageUser.vue';
import TotalUserReportComponent from './components/admin/TotalUserReportComponent.vue';

import Error403 from './components/admin/Error403.vue';
import NotFound from './components/admin/NotFound.vue';
import Logout from './components/admin/Logout.vue';
import ProductComponent from './components/admin/ProductComponent.vue';
import ProductAddComponent from './components/admin/ProductAddComponent.vue';
import CategoryComponent from './components/admin/CategoryComponent.vue';
import CategoryAddComponent from './components/admin/CategoryAddComponent.vue';
import Guard from './middleware';
import Vue from 'vue';
import {currentLoginStatus,apiHost} from'./config';

let routes = [];
let base = '/admin/';
if(localStorage.getItem('access_token')!==null){
    routes = [
        {
            path: '/login',
            component: LoginComponent,
            name:'login',
        },
        {
            path: '/dashboard',
            component: DashboardComponent,
            name:'dashboard',
            beforeEnter: Guard.auth
        },
        {
            path: '/',
            redirect: 'dashboard',
            beforeEnter: Guard.auth
        },
        {
            path: '/user-account',
            component: ManageUser,
            name:'user-account',
            beforeEnter: Guard.auth
        },
        {
            path: '/total-user-report',
            component: TotalUserReportComponent,
            name:'total-user-report',
            beforeEnter: Guard.auth
        },
        {
            path: '/logout',
            component: Logout,
            name:'logout',
            beforeEnter: Guard.auth,
        },
        {
            path: '/403',
            component: Error403,
            name:'403',
            beforeEnter: Guard.auth,
        },
        {
            path: '/products-list',
            component: ProductComponent,
            name:'products-list',
            beforeEnter: Guard.auth,
        },
        {
            path: '/product-add',
            component: ProductAddComponent,
            name:'product-add',
            beforeEnter: Guard.auth,
        },
        {
            path: '/categories-list',
            component: CategoryComponent,
            name:'categories-list',
            beforeEnter: Guard.auth,
        },
        {
            path: '/category-add',
            component: CategoryAddComponent,
            name:'category-add',
            beforeEnter: Guard.auth,
        },
        {
            path: '*',
            component: NotFound,
        }   

    ];
} else {
    routes = [
        {
            path: '/login',
            component: LoginComponent,
            name:'login',
        },
        {
            path: '*',
            component: LoginComponent,
            redirect: 'login',
        }
    ];
}
export default new VueRouter({
    base :base,
    routes,
});