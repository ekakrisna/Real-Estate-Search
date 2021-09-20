<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileHelper
{
    public static function upload($file, $fileName = null) {
        if(empty($file)){
            return null;
        }

        if(empty($fileName)){
            $name = time() . '-' . $file->getClientOriginalName();
        } else {
            $name = $fileName . '.' . $file->getClientOriginalExtension();
        }

        $path = Storage::putFileAs(null, $file, $name, 'public');

        return $path;
    }

    public static function remove($path){
        if( Storage::exists($path) && !empty($path)){
            Storage::delete($path);
        }
    }

    public static function update($file, $path_to_remove, $is_remove = "false", $fileName = null){
        if($is_remove == "true"){
            self::remove($path_to_remove);
            return null;
        }

        if(empty($file)){
            return $path_to_remove;
        }

        self::remove($path_to_remove);
        return self::upload($file, $fileName);
    }
}
