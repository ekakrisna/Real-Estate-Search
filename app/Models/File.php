<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class File
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $extension
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|PropertyFlyer[] $property_flyers
 * @property Collection|PropertyPhoto[] $property_photos
 *
 * @package App\Models
 */
class File extends Model
{
	protected $table = 'files';
	public $timestamps = true;

	protected $fillable = [
		'name',
		'extension',
		'original_name',
		'size_byte',
	];

	public function property_flyers()
	{
		return $this->hasMany(PropertyFlyer::class);
	}

	public function property_photos()
	{
		return $this->hasMany(PropertyPhoto::class);
	}

	protected $appends = ['url'];

	// ----------------------------------------------------------------------
    // @TODO: Build URL based on route
    // ----------------------------------------------------------------------
    public function getUrlAttribute(){
        $url = new \stdClass;		
		$filename = '/properties/' . $this->name . '.' . $this->extension;		
		$storagePath = Storage::url($filename);		
		$url->image = $storagePath;
		$url->original_name = $this->original_name . '.' . $this->extension;
        return $url;
    }

	public static function uploadFile($file, $id, $type)
	{
		$uuid = (string) Str::uuid();
		$directory = 'properties';
		$extension = $file->extension();
		$filename = "property-{$id}-{$type}-{$uuid}";
		$storedFile = File::create([
			'name' => "{$filename}",
			'extension' => $extension,
			'original_name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
			'size_byte' => $file->getSize(),
		]);
		if ($storedFile) {
			$file->storeAs($directory, "{$filename}.{$extension}");
		}
		return $storedFile;
	}
}
