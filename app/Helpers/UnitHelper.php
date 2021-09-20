<?php
    // ----------------------------------------------------------------------
    // Decimal rounding helper
    // Source: http://bit.ly/2M1zGHm (JS version)
    // ----------------------------------------------------------------------
    if( !function_exists( 'precisionRounding' )){
        function precisionRounding( $value, $precision = 4, $rounding = 'floor' ){
            // --------------------------------------------------------------
            if( 'round' == $rounding ) return round( $value, $precision );
            else {
                // ----------------------------------------------------------
                if( $precision ){
                    $multiplier = pow( 10, $precision );
                    return $rounding(( $value + PHP_FLOAT_EPSILON ) * $multiplier ) / $multiplier;
                } 
                // ----------------------------------------------------------
                else return $rounding( $value ); // 0 precision
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------
        }
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Convert from meter to tsubo
    // ----------------------------------------------------------------------
    /**
    * @param string $dimension
    * Dimension in metric to be converted to tsubo
    *
    * @param string $precision
    * Decimal precision
    * Defaults to 4 decimals
    *
    * @param string $rounding
    * Rounding mode ( floor | ceil } round )
    * Defaults to "floor"
    *
    * @return string dimension in tsubo
    *
    **/
    // ----------------------------------------------------------------------
    if( !function_exists( 'toTsubo' )){
        function toTsubo( $dimension, $precision = null, $rounding = null ){
            // --------------------------------------------------------------
            // Meter to Tsubo calculation
            // --------------------------------------------------------------
            $tsubo = config('const.tsubo.control');
            $result = $dimension / $tsubo;
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Default values
            // --------------------------------------------------------------
            if( null === $rounding ) $rounding = config('const.tsubo.rounding');
            if( null === $precision ) $precision = config('const.tsubo.precision');
            // --------------------------------------------------------------
            
            // --------------------------------------------------------------
            // Decimal rounding
            // --------------------------------------------------------------
            $roundings = collect([ 'floor', 'round', 'ceil' ]);
            if( $roundings->contains( $rounding )){
                $result = precisionRounding( $result, (int) $precision, $rounding );
            }
            // --------------------------------------------------------------
            
            // --------------------------------------------------------------
            return $result;
            // --------------------------------------------------------------
        }
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Convert back from tsubo to meter
    // ----------------------------------------------------------------------
    /**
    * @param string $dimension
    * Dimension in tsubo to be converted to metric
    *
    * @param string $precision
    * Decimal precision
    * Defaults to 4 decimals
    *
    * @param string $rounding
    * Rounding mode ( floor | ceil } round )
    * Defaults to "floor"
    *
    * @return string dimension in metric
    *
    **/
    // ----------------------------------------------------------------------
    if( !function_exists( 'fromTsubo' )){
        function fromTsubo( $dimension, $precision = 4, $rounding = null ){
            // --------------------------------------------------------------
            // Tsubo to meter conversion
            // --------------------------------------------------------------
            $tsubo = config('const.tsubo.control');
            $result = $dimension * $tsubo;
            // --------------------------------------------------------------
            
            // --------------------------------------------------------------
            // Default values
            // --------------------------------------------------------------
            if( null === $rounding ) $rounding = config('const.tsubo.rounding');
            if( null === $precision ) $precision = config('const.tsubo.precision');
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Decimal rounding
            // --------------------------------------------------------------
            $roundings = collect([ 'floor', 'round', 'ceil' ]);
            if( $roundings->contains( $rounding )){
                $result = precisionRounding( $result, (int) $precision, $rounding );
            }
            // --------------------------------------------------------------
            
            // --------------------------------------------------------------
            return $result;
            // --------------------------------------------------------------
        }
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Convert regular number into Japanese Man format (Ten thousands)
    // ----------------------------------------------------------------------
    /**
    * @param string $number
    * Number to be converted to Man
    *
    * @param string $precision
    * Decimal precision
    * Defaults to 4 decimals
    *
    * @param string $rounding
    * Rounding method, ( floor | ceil | round )
    * Defaults to "floor"
    *
    * @return string Number in Man
    *
    **/
    // ----------------------------------------------------------------------
    if( !function_exists( 'toMan' )){
        function toMan( $number, $precision = 4, $rounding = null ){
            // --------------------------------------------------------------
            // Number to Man conversion
            // --------------------------------------------------------------
            $man = config('const.man.control');
            $result = $number / $man;
            // --------------------------------------------------------------
            
            // --------------------------------------------------------------
            // Default values
            // --------------------------------------------------------------
            if( null === $rounding ) $rounding = config('const.man.rounding');
            if( null === $precision ) $precision = config('const.man.precision');
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Decimal rounding
            // --------------------------------------------------------------
            $roundings = collect([ 'floor', 'round', 'ceil' ]);
            if( $roundings->contains( $rounding )){
                $result = precisionRounding( $result, (int) $precision, $rounding );
            }
            // --------------------------------------------------------------
            
            // --------------------------------------------------------------
            return $result;
            // --------------------------------------------------------------
        }
    }
    // ----------------------------------------------------------------------


    if( !function_exists( 'toManDisplay' )){
        function toManDisplay( $val){
            $length = strlen($val);
            $digits = array('', '万', '億', '兆', '京', '垓');
            $results = array();
            $result = null;

            for($i = 0; $i < ceil($length / 4); $i++){
                $results[$i] = substr($val, -4, 4);
                $val = substr($val, 0, -4);
            if($results[$i] != '0000') 
                $result = number_format($results[$i]).$digits[$i].$result;
            }
            return $result.'円';
        }
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Convert Japanese Man format (Ten thousands) back to number
    // ----------------------------------------------------------------------
    /**
    * @param string $number
    * Man to be converted to regular number
    *
    * @param string $precision
    * Decimal precision
    * Defaults to 4 decimals
    *
    * @param string $rounding
    * Rounding method, ( floor | ceil | round )
    * Defaults to "floor"
    *
    * @return string Regular number
    *
    **/
    // ----------------------------------------------------------------------
    if( !function_exists( 'fromMan' )){
        function fromMan( $number, $precision = 4, $rounding = null ){
            // --------------------------------------------------------------
            // Man to regualar number conversion
            // --------------------------------------------------------------
            $man = config('const.man.control');
            $result = $number * $man;
            // --------------------------------------------------------------
            
            // --------------------------------------------------------------
            // Default values
            // --------------------------------------------------------------
            if( null === $rounding ) $rounding = config('const.man.rounding');
            if( null === $precision ) $precision = config('const.man.precision');
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Decimal rounding
            // --------------------------------------------------------------
            $roundings = collect([ 'floor', 'round', 'ceil' ]);
            if( $roundings->contains( $rounding )){
                $result = precisionRounding( $result, (int) $precision, $rounding );
            }
            // --------------------------------------------------------------
            
            // --------------------------------------------------------------
            return $result;
            // --------------------------------------------------------------
        }
    }
    // ----------------------------------------------------------------------
?>
