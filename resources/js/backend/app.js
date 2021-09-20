require('./bootstrap');
window.Vue = require('vue');

import $ from 'jquery';
window.$ = window.jQuery = $;

// Import jQuery Plugins
import 'jquery-confirm/dist/jquery-confirm.min.css';
import 'jquery-confirm/dist/jquery-confirm.min.js';

require('jquery.scrollto');

import parseNumeric from './helpers/parseNumeric.js';
window.parseNumeric = parseNumeric;

import deviceUuid from 'device-uuid';
window.deviceUuid = deviceUuid;