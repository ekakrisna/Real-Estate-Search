// --------------------------------------------------------------------------
var app;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Vue g-maps
// --------------------------------------------------------------------------
import * as VueGoogleMaps from 'vue2-google-maps';
// --------------------------------------------------------------------------
// Vue currency
// --------------------------------------------------------------------------
import { CurrencyInput } from 'vue-currency-input';
import { CurrencyDirective } from 'vue-currency-input';
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Vue Datepicker
// --------------------------------------------------------------------------
import DatePicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import 'vue2-datepicker/locale/ja';
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Vue google maps
// --------------------------------------------------------------------------
Vue.use(VueGoogleMaps, {
    load: {
      key: 'AIzaSyAd7VuMeln4Slhqg4K5-L9ka3Ikq8226kg',
      libraries: 'places', // This is required if you use the Autocomplete plugin
      region: 'JP',
      language: 'ja',
      // OR: libraries: 'places,drawing'
      // OR: libraries: 'places,drawing,visualization'
      // (as you require)

      //// If you want to set the version, you can do so:
      v: '3',
    },
    installComponents: false
});

Vue.component('google-map', VueGoogleMaps.Map);
Vue.component('google-marker', VueGoogleMaps.Marker);
Vue.component('gmap-autocomplete', VueGoogleMaps.Autocomplete);

// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Vue toasted notification
// --------------------------------------------------------------------------
import Toasted from 'vue-toasted';
Vue.use( Toasted, {
    duration: 5000,
    theme: 'bubble',
    type: 'success',
    containerClass: 'vue-toasted',
});
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
// Vue sortable
// --------------------------------------------------------------------------
import Draggable from 'vuedraggable';
Vue.component( 'Draggable', Draggable );
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
// Vue numeral filter
// --------------------------------------------------------------------------
import vueNumeral from 'vue-numeral-filter';
Vue.use( vueNumeral, { locale: 'ja' });
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Vue moment
// --------------------------------------------------------------------------
import VueMoment from 'vue-moment';
Vue.use( VueMoment );
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Vue router
// --------------------------------------------------------------------------
import VueRouter from 'vue-router';
Vue.use( VueRouter );
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Vuex
// --------------------------------------------------------------------------
import Vuex from 'vuex';
Vue.use( Vuex );
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
// Vee Validate - Vue validation component
// --------------------------------------------------------------------------
// import { ValidationProvider, ValidationObserver } from 'vee-validate/dist/vee-validate.full.esm';
// Vue.component( 'Validation', ValidationProvider );
// Vue.component( 'Observer', ValidationObserver );
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Custom vue filters
// --------------------------------------------------------------------------
require('./filters/precisionRounding');
require('./filters/manFilters');
require('./filters/tsuboFilters');
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Vue init options
// --------------------------------------------------------------------------
var vueOptions = {
    el: '#app',
    delimiters: ['{{', '}}'],
    mixins: [mixin],
    data: function(){ return {
        mounted: false
    }},
    mounted(){
        this.mounted = true;
    },
    components: {
        CurrencyInput, DatePicker,
        // ValidationProvider, ValidationObserver,
    },
    directives: { currency: CurrencyDirective }
};
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// If router is defined, add it to Vue init options
// --------------------------------------------------------------------------
if( !_.isUndefined( router ) && !_.isEmpty( router )){
    vueOptions.router = new VueRouter( router );
}
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// If store is defined, add it to Vue init options
// --------------------------------------------------------------------------
if( !_.isUndefined( store ) && !_.isEmpty( store )){
    vueOptions.store = new Vuex.Store( store );
}
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
if( mixin ) app = new Vue( vueOptions ); // Initiate Vue
// --------------------------------------------------------------------------
