<?php

use Stillat\Numeral\Languages\LanguageManager;
use Stillat\Numeral\Numeral;

if( !function_exists( 'is_decimal' )){
    function is_decimal( $val )
    {
        return is_numeric( $val ) && floor( $val ) != $val;
    }
}

if( !function_exists( 'numeral' )){
    function numeral( $value, $format ){
        // Create the language manager instance.
        $languageManager = new LanguageManager;
        // Create the Numeral instance.
        $formatter = new Numeral;
        // Now we need to tell our formatter about the language manager.
        $formatter->setLanguageManager($languageManager);

        //if(is_decimal($value)) {
            //$result = $formatter->format($value, $format.'.00');
        //}else{
            $result = $formatter->format($value, $format);
        //} 

        return $result;
    }
}

?>