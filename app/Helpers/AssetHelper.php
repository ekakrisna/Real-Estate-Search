<?php
// namespace App\Helpers; #remove namespace to make easy to implement in view, AssetHelper::version('path');
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class AssetHelper {
    public static function version( $path ){
        $filenamePath = public_path( $path );
        if( file_exists( $filenamePath )){
            $modDateTime = date( "YmdHis", filemtime( $filenamePath ));
            return asset($path).'?v='.$modDateTime;
        }
    }
}
// --------------------------------------------------------------------------