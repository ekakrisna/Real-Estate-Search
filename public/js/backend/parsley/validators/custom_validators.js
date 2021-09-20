(function( window, document, undefined ){
  window.Parsley.addValidator('checkemail', {
    requirementType: ['string', 'string'],
    validateString: function validateString(value, url, userID) {
      var deferred = jQuery.Deferred();
      var data = {
        email: value
      };
      if (userID) data.user = parseInt(userID);
      var request = axios.post(url, data);
      request.then(function (response) {
        var status = response.status;
        var exists = response.data;
        if (200 === status && !exists) deferred.resolve();
        deferred.reject();
      });
      return deferred.promise();
    },
    messages: {
      en: 'Please Enter Your Correct Email',
      ja: '該当のメールアドレスは存在しません。'
    }
  });

  // Greater than or equal to validator
  window.Parsley.addValidator('ge', {
    validateString: function( value, target ){

      // Parse the values
      var currentValue = Number( value );
      var targetValue = Number( $(target).val());

      // If either one of the values are falsy
      if( !currentValue || !targetValue ) return true;

      // Return and make sure current-value is greater than the target
      return currentValue >= targetValue;
    },
    messages: {
      en: 'Max Value must be Greater Than Min Value',
      ja: '最大値は最小値より大きくなければなりません'
    }
  });

  // Less than or equal to validator
  window.Parsley.addValidator( 'le', {
    validateString: function( value, target ){

      // Parse the values
      var currentValue = Number( value );
      var targetValue = Number( $(target).val());

      // If either one of the values are falsy
      if( !currentValue || !targetValue ) return true;

      // Return and make sure current-value is less than the target
      return currentValue <= targetValue;
    },
    messages: {
      en: 'Min Value must be Smaller Than Max Value',
      ja: '最小値は最大値よりも小さくする必要があります'
    }
  });

  //not Equal TO
  window.Parsley.addValidator("notequalto", {
    requirementType: "string",
    validateString: function(value, element) {
        return value !== $(element).val();
    }
  });
}( window, document ));