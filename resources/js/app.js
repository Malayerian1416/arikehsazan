/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import {win} from "leaflet/src/core/Browser";

require('./bootstrap');

window.Vue = require('vue').default;
import AutoNumeric from "autonumeric";
window.bootbox = require('bootbox');
window.AutoNumeric = AutoNumeric;
window.alerify = require('alertifyjs');
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
require('bootstrap-select/dist/js/bootstrap-select');
require('bootstrap-select/js/i18n/defaults-fa_IR');
require('owl.carousel');
require('chart.js/dist/chart.min');
require('chart.js/dist/helpers.esm');
require('leaflet/dist/leaflet.js');
require('leaflet-draw/dist/leaflet.draw');
import * as d3 from "d3-geo";
window.d3 = d3;
// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
// // Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
