<?php

namespace App\Models;

use App\Model\LpProperty;
use Illuminate\Database\Eloquent\Model;

class LpPropertyStatus extends Model
{
    const APPROVAL_PENDING = 1;
    const PUBLISHED = 2;
    const PULICATION_LIMITED = 3;
    const NOT_POSTED = 4;
    const FINISH_PUBLISH = 5;

    const JPN_ID = [
        self::APPROVAL_PENDING => "1",
        self::PUBLISHED => "2",
        self::PULICATION_LIMITED => "3",
        self::NOT_POSTED => "4",
        self::FINISH_PUBLISH => "5",
    ];

    protected $table = 'lp_property_statuses';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'id' => 'int'
    ];

    protected $fillable = [
        'name',
        'label'
    ];

    public function properties()
    {
        return $this->hasMany(LpProperty::class, 'lp_property_statuses_id');
    }
}
