let baseUrl = '';
let redirectUrl = '';
let loginStatus = '';
let adminUrl = '';
let userBroweserUrl = '';
let assetsUrl = '';
let adminassetsUrl = '';

if (process.env.NODE_ENV === 'production') {
    baseUrl = 'https://www.helpingcommunity.live/helping-community/api';
    redirectUrl = 'https://www.helpingcommunity.live/helping-community/';
    adminUrl = 'https://www.helpingcommunity.live/helping-community/admin@help/';
    userBroweserUrl= 'https://www.helpingcommunity.live/helping-community';
    assetsUrl = 'https://www.helpingcommunity.live/helping-community/';
    adminassetsUrl = 'https://www.helpingcommunity.live/helping-community/admin';
}else {
    baseUrl = 'http://localhost/earningpoint/api';
    redirectUrl = 'http://localhost/earningpoint/';
    adminUrl = 'http://localhost/earningpoint/admin@help/';
    userBroweserUrl = 'http://localhost/earningpoint/admin@help/';
    assetsUrl = 'http://localhost/earningpoint/public';
    adminassetsUrl = 'http://localhost/earningpoint/public/admin';
}
loginStatus = localStorage.getItem('access_token');
export const apiHost = baseUrl;
export const redirect = redirectUrl;
export const currentLoginStatus = loginStatus;
export const adminHost = adminUrl;
export const apiAdminHost = baseUrl+'/admin/';
export const userBroweserHost = userBroweserUrl;
export const userAssets = assetsUrl;
export const adminAssets = adminassetsUrl;
export const getHeader = function() {
	const tokenData = localStorage.setItem('token');
	const headers = {
		'Accept': 'application/json',
		'Authorization': tokenData
	}
	return headers;
}

export const providehelpconditions = Object.freeze({
    min: "100",
    max: "10000",
    multiple: "100",
   
});

export const withdrawconditions = Object.freeze({
    min: "500",
    multiple: "500", 
});
