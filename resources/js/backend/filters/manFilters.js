// ----------------------------------------------------------------------
// Man conversion config
// ----------------------------------------------------------------------
var config = {
    control: 10000,
    rounding: 'round',
    precision: 4
};
// ----------------------------------------------------------------------

// ----------------------------------------------------------------------
// Convert number to Japanese Man unit (Ten thousands)
// ----------------------------------------------------------------------
Vue.filter( 'toMan', function( number, precision, rounding ){
    // ------------------------------------------------------------------
    precision = precision || config.precision;
    rounding = rounding || config.rounding;
    // ------------------------------------------------------------------
    var man = config.control;
    var result = number / man;
    // ------------------------------------------------------------------

    // ------------------------------------------------------------------
    return Vue.filter('precisionRounding')( result, precision, rounding );
    // ------------------------------------------------------------------
});

Vue.filter( 'toManDisplay', function( _val ){
    const _string = String(_val);
    const _length = _string.length;
    const _digits = ['', '万', '億', '兆', '京', '垓'];
    let _result = '';
    let _results = [];
    
    for(i = 0; i < Math.ceil(_length / 4); i++){
      _results[i] = Number(_string.substring(_length - i * 4, _length - (i + 1) * 4));
      if(_results[i] != 0) _result = String(_results[i]).replace(/(\d)(?=(\d\d\d)+$)/g, '$1,') + _digits[i] + _result;
    }
    return _result + '円';
     
    // ------------------------------------------------------------------
});
// ----------------------------------------------------------------------


// ----------------------------------------------------------------------
// Convert Japanese Man unit (Ten thousands) back to regular number
// ----------------------------------------------------------------------
Vue.filter( 'fromMan', function( number, precision, rounding ){
    // ------------------------------------------------------------------
    precision = precision || config.precision;
    rounding = rounding || config.rounding;
    // ------------------------------------------------------------------
    var man = config.control;
    var result = number * man;
    // ------------------------------------------------------------------

    // ------------------------------------------------------------------
    return Vue.filter('precisionRounding')( result, precision, rounding );
    // ------------------------------------------------------------------
});
// ----------------------------------------------------------------------