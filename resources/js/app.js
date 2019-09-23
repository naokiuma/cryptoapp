/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./mouseover_change');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('news-component', require('./components/NewsComponent.vue').default);//ニュースデータを出力するvue
Vue.component('coin-component', require('./components/CoinComponent.vue').default);//コインのトレンドデータをajaxで取得するvue
Vue.component('twitter-component',require('./components/TwitterComponent.vue').default);//ツイッターのログイン時ユーザー一覧
Vue.component('nologin-component',require('./components/NologinComponent.vue').default);//ログインしていない時用のユーザー一覧
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const newsapp = new Vue({
  el: '#newsapp',
})

const coinapp = new Vue({
  el: '#coinapp',
})

const twitterapp = new Vue({
  el: '#twitterapp',
})

const nologinapp = new Vue({
  el: '#nologinapp',
})


//const app = new Vue({
//    el: '#app',
//});
