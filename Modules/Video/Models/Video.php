<?php

namespace Modules\Video\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Subscriptions\Models\Plan;

class Video extends BaseModel
{

    use SoftDeletes;

    protected $table = 'videos';
    protected $fillable = [
        'name',
        'type',
        'description',
        'poster_url',
        'short_desc',
        'trailer_url_type',
        'trailer_url',
        'access',
        'plan_id',
        'status',
        'duration',
        'release_date',
        'is_restricted',
        'video_upload_type',
        'video_url_input',
        'download_status',
        'enable_quality',
        'download_type',
        'download_url',
        'enable_download_quality',
    ];

    //  protected $appends = ['poster_url'];

    //  public function getPosterUrlAttribute()
    //  {
    //      $media = $this->getFirstMediaUrl('poster_url');
    //      return $media ? $media : 'https://dummyimage.com/600x300/cfcfcf/000000.png';
    //  }


     protected static function boot()
     {
         parent::boot();

         static::deleting(function ($video) {

             if ($video->isForceDeleting()) {

                $video->VideoStreamContentMappings()->withTrashed()->each(function ($mapping) {
                    $mapping->forceDelete();
                });

             } else {

                 $video->VideoStreamContentMappings()->delete();
             }

         });

         static::restoring(function ($video) {

           $video->VideoStreamContentMappings()->withTrashed()->restore();

         });
     }


    public function VideoStreamContentMappings()
    {
        return $this->hasMany(VideoStreamContentMapping::class,'video_id','id');
    }

    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }

    public function Watchlist()
    {
        return $this->hasMany(Watchlist::class,'entertainment_id','id','type');
    }
    public function video()
    {
        return $this->hasOne(\Modules\Video\Models\Video::class, 'id', 'entertainment_id');
    }

    public function entertainmentReviews()
    {
        return $this->hasMany(Review::class,'entertainment_id','id');
    }
    
    public function entertainmentLike()
    {
        return $this->hasMany(Like::class,'entertainment_id','id');
    }

    public function videoDownloadMappings()
    {
        return $this->hasMany(VideoDownloadMapping::class,'video_id','id');
    }

}
