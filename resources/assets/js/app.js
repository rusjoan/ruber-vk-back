require('./bootstrap');

import VueRouter from 'vue-router';
import VueClip from 'vue-clip';
import Index from './components/Index.vue';
import Group from './components/Group.vue';

window.Vue = require('vue');

Vue.use(VueRouter);
Vue.use(VueClip);

const routes = [
    {path: '/', component: Index},
    {path: '/group/:groupId', component: Group, children: [
        // {path: '/', component: Index},
    ]},
];

const router = new VueRouter({
    routes
});

const app = new Vue({
    router,
    el: '#app'
});
