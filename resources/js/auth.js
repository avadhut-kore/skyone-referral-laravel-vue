import Vue from 'vue'

export default {
    user() {
        return this.$store.state.user
    },

    check() {
        return localStorage.getItem('access_token') //get token from localStorage 
    }
}