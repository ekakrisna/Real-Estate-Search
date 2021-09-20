<?php

// --------------------------------------------------------------------------
// Generate a URL to an application asset with a versioned timestamp parameter.
// --------------------------------------------------------------------------
if( !function_exists( 'assetv' )){
    function assetv( $asset, $secure = null ){
        $timestamp = @filemtime( public_path( $asset )) ?: 0;
        return asset( $asset, $secure ) . '?' . $timestamp;
    }
}
// --------------------------------------------------------------------------