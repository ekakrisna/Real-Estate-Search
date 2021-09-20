// ----------------------------------------------------------------------
// Man conversion config
// ----------------------------------------------------------------------
var config = {
    control: 3.30579,
    rounding: 'round',
    precision: 4
};
// ----------------------------------------------------------------------

// ----------------------------------------------------------------------
// Convert meter to Japanese Tsubo unit
// ----------------------------------------------------------------------
Vue.filter( 'toTsubo', function( meter, precision, rounding ){
    // ------------------------------------------------------------------
    precision = precision || config.precision;
    rounding = rounding || config.rounding;
    // ------------------------------------------------------------------
    var tsubo = config.control;
    var result = meter / tsubo;
    // ------------------------------------------------------------------

    // ------------------------------------------------------------------
    return Vue.filter('precisionRounding')( result, precision, rounding );
    // ------------------------------------------------------------------
});
// ----------------------------------------------------------------------


// ----------------------------------------------------------------------
// Convert Japanese Tsubo unit back to meter
// ----------------------------------------------------------------------
Vue.filter( 'fromTsubo', function( meter, precision, rounding ){
    // ------------------------------------------------------------------
    precision = precision || config.precision;
    rounding = rounding || config.rounding;
    // ------------------------------------------------------------------
    var tsubo = config.control;
    var result = meter * tsubo;
    // ------------------------------------------------------------------

    // ------------------------------------------------------------------
    return Vue.filter('precisionRounding')( result, precision, rounding );
    // ------------------------------------------------------------------
});
// ----------------------------------------------------------------------