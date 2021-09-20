import numeral from 'numeral';
import $ from 'jquery';

window.Parsley.addValidator( 'greaterThan', {
    requirementType: 'string',
    validateString: function( value, selector ){
        value = numeral( value ).value();
        var comparison = numeral( $(selector).val()).value();

        if( !isNaN( value ) && !isNaN( comparison )){
            return comparison < value;
        }
    },
    messages: {
        en: 'Max Value must be greater than Min Value',
        ja: '最大値は最小値より大きくなければなりません'
    }
});

window.Parsley.addValidator( 'lesserThan', {
    requirementType: 'string',
    validateString: function( value, selector ){
        value = numeral( value ).value();
        var comparison = numeral( $(selector).val()).value();

        if( !isNaN( value ) && !isNaN( comparison )){
            return comparison > value;
        }
    },
    messages: {
        en: 'Min Value must be smaller than Max Value',
        ja: '最小値は最大値よりも小さくする必要があります'
    }
});