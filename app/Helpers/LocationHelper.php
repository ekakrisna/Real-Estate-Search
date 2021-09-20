<?php
    // ----------------------------------------------------------------------
    // Parse a location string
    // Return a collection of the location components
    // ----------------------------------------------------------------------
    if( !function_exists( 'parseLocation' )){
        function parseLocation( $locationString ){
            $location = \Location::splitPrefecturesCityTown( $locationString );
            return collect( (object) array_change_key_case( $location, CASE_LOWER ));
        }
    }
    // ----------------------------------------------------------------------
?>
