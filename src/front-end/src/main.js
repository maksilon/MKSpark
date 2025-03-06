import Vue from 'vue';
import App from './App.vue';
import i18n from './i18n';
import 'bootstrap';
import '../assets/scss/main.scss';

new Vue({
  el: '#app',
  i18n,
  render: (h) => h(App)
});
