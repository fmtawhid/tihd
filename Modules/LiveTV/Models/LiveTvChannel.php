<?php

namespace Modules\LiveTV\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\LiveTV\Models\TvChannelStreamContentMapping;
use Modules\Subscriptions\Models\Plan;

class LiveTvChannel extends BaseModel
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'live_tv_channel';
    protected $fillable = [
        'name','category_id','poster_url','thumb_url','access','plan_id','description','status',
    ];
    // protected $appends = ['poster_url'];

    // public function getPosterUrlAttribute()
    // {
    //     $media = $this->getFirstMediaUrl('poster_url');
    //     return $media ? $media : 'https://dummyimage.com/600x300/cfcfcf/000000.png';
    // }

    public function TvChannelStreamContentMappings()
    {
        return $this->hasOne(TvChannelStreamContentMapping::class,'tv_channel_id','id');
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function () {

        });

        static::restoring(function () {

        });
    }

    public function TvCategory()
    {
        return $this->hasOne(LiveTvCategory::class,'id','category_id');
    }

    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }
}
