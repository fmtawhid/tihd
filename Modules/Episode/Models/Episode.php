<?php

namespace Modules\Episode\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Entertainment\Models\Entertainment;
use Modules\Season\Models\Season;
use Modules\Subscriptions\Models\Plan;

class Episode extends BaseModel
{

    use SoftDeletes;

    protected $table = 'episodes';
    protected $fillable=[ 'name',
                          'entertainment_id',
                          'season_id',
                          'poster_url',
                          'trailer_url_type',
                          'trailer_url',
                          'access',
                          'plan_id',
                          'IMDb_rating',
                          'content_rating',
                          'duration',
                          'release_date',
                          'is_restricted',
                          'short_desc',
                          'description',
                          'enable_quality',
                          'video_upload_type',
                          'video_url_input',
                          'download_status',
                          'download_type',
                          'download_url',
                          'enable_download_quality',
                          'status',
                          'video_quality_url','tmdb_id','tmdb_season','episode_number'];

            
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($episode) {

         if ($episode->isForceDeleting()) {

             $episode->EpisodeStreamContentMapping()->forceDelete();
             $episode->episodeDownloadMappings()->forceDelete();
             
         } else {

             $episode->EpisodeStreamContentMapping()->delete();
             $episode->episodeDownloadMappings()->delete();
         }

        });

        static::restoring(function ($episode) {

            $episode->EpisodeStreamContentMapping()->withTrashed()->restore();
            $episode->episodeDownloadMappings()->delete();

        });
    }


    public function entertainmentdata()
    {
        return $this->belongsTo(Entertainment::class,'entertainment_id')->with('entertainmentGenerMappings');
    }

 
    public function seasondata()
    {
        return $this->belongsTo(Season::class,'season_id');
    }

    public function episodeDownloadMappings()
    {
        return $this->hasMany(EpisodeDownloadMapping::class, 'episode_id', 'id');
    }


    public function EpisodeStreamContentMapping()
    {
        return $this->hasMany(EpisodeStreamContentMapping::class,'episode_id','id');
    }

    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }

}
