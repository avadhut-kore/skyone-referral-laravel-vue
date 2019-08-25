import Vue from 'vue'; 
import axios from 'axios';
import Toaster from 'v-toaster';
import { redirect} from'./config';

// new 
export default function setup() {
    axios.interceptors.request.use(function(config) {
        const token =   localStorage.getItem('access_token');
        //console.log("Token : ");
        //console.log(token);
        if(token != null) {
            config.headers.Authorization = "Bearer "+token;
        }
        return config;
    }, function(err) {
        console.log("err");
        return Promise.reject(err);
    });
    axios.interceptors.response.use(function(config) {
        //console.log("LOG : "+ config.data.code);
        return config;
    }, function(err) {
        var msg = err.response.data.message;
        if(msg != undefined && msg != ''){
            Vue.prototype.$toaster.error(msg);
        }
        console.log("status : " + err.response.status);
        if(err.response.status == 403) {
            //this.$router.push({ path: '/403'});
             localStorage.clear();
            window.location.href = redirect;
        } else if(err.response.status == 401) {
            /*if(msg != undefined && msg != ''){
                //console.log(msg);
                Vue.prototype.$toaster.error(msg);
                Vue.prototype.router.push({ path: '/403'});
                console.log("401");
                return Promise.reject(err)
            }*/

            var logintype = localStorage.getItem('typelogin');
            //alert(logintype);
            if(logintype == 'Admin'){
              localStorage.removeItem('typelogin'); 
            }else{
                console.log(err);
                //window.location.href = redirect;  
            }
           
        }
        //return;
    });
}