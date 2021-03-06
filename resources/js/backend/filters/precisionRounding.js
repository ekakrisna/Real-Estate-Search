// ----------------------------------------------------------------------
// Precision rounding helper
// Source: http://bit.ly/2M1zGHm
// ----------------------------------------------------------------------
Vue.filter( 'precisionRounding', function( number, precision, rounding ){
    // ------------------------------------------------------------------
    precision = precision || 4;
    rounding = rounding || 'round';
    // ------------------------------------------------------------------

    // ------------------------------------------------------------------
    // If rounding mode is invalid, default to 'round'
    // ------------------------------------------------------------------
    var roundings = [ 'floor', 'round', 'ceil' ];
    if( 0 < roundings.indexOf( rounding )) rounding = 'round';
    // ------------------------------------------------------------------

    // ------------------------------------------------------------------
    // Rounding process
    // ------------------------------------------------------------------
    var multiplier = Math.pow( 10, precision );
	return number === 0 ? '':Math[rounding](( number + Number.EPSILON ) * multiplier ) / multiplier;
    // ------------------------------------------------------------------
});
// ----------------------------------------------------------------------