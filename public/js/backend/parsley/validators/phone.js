(function( window, document, undefined ){
    window.Parsley.addValidator( 'phoneNumber', {
        requirementType: 'string',
        validateString: function( value ){
            return /^[\d/-]+$/.test( value );
        },
        messages: {
            en: 'The value format is incorrect.',
            ja: '値の形式が正しくありません。'
        }
    });
}( window, document ));