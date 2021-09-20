const { default: Axios } = require("axios");
const io = require('lodash');

window.Parsley.addValidator( 'emailExists', {
    requirementType: [ 'string', 'string' ],
    validateString: function( value, url, userID ){
        var deferred = jQuery.Deferred();

        var data = { email: value };
        if( userID ) data.user = io.parseInt( userID );
        var request = axios.post( url, data );

        request.then( function( response ){

            var status = io.get( response, 'status' );
            var exists = io.get( response, 'data' );

            if( 200 === status && !exists ) deferred.resolve();
            deferred.reject();

        });

        return deferred.promise();
    },
    messages: {
        en: 'This email is already registered',
        ja: 'このメールアドレスは既に登録済みです'
    }
});
