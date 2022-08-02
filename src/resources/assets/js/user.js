
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 //Users List
 Vue.component('users-list', require('./components/UsersListComponent.vue'));
 Vue.component('create-user', require('./components/CreateUserComponent.vue'));
 Vue.component('my-profile', require('./components/MyProfileComponent.vue'));
 
 
 
 const users = new Vue({
     el: '#users-module'
 });
 