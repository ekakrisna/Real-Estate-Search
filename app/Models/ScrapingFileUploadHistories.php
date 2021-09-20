<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ScrapingFileUploadHistories extends Model
{
    protected $table = 'scraping_file_upload_histories';
    public $timestamps = false;
    protected $appends = ['ja'];

    protected $fillable = [
        'file_name',
        'created_at',
	];

    	// ----------------------------------------------------------------------
    // Get Japanese formatted timestamps
    // ----------------------------------------------------------------------
    public function getJaAttribute(){
        $result = new \stdClass; $format = "Y年m月d日 H:i"; $date_format = "Y年m月d日";
        if( !empty( $this->created_at )) $result->created_at = Carbon::parse( $this->created_at )->format( $format );
        return $result;
    }
	// ----------------------------------------------------------------------
}
