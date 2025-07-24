<?php

namespace Modules\Entertainment\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Subscriptions\Models\Plan;
use Modules\Season\Models\Season;
use Modules\Episode\Models\Episode;

class Entertainment extends BaseModel
{

    use SoftDeletes;

    protected $table = 'entertainments';

    protected $fillable = [
        'name',
        'tmdb_id',
        'description',
        'trailer_url_type',
        'trailer_url',
        'poster_url',
        'thumbnail_url',
        'movie_access',
        'type', // movie,tv_show
        'plan_id',
        'status',
        'language',
        'IMDb_rating',
        'content_rating',
        'duration',
        'release_date',
        'is_restricted',
        'video_upload_type',
        'enable_quality',
        'video_url_input',
        'download_status',
        'download_type',
        'download_url',
        'enable_download_quality',
        'video_quality_url'

    ];



    public function entertainmentGenerMappings()
    {
        return $this->hasMany(EntertainmentGenerMapping::class,'entertainment_id','id')->with('genre');
    }
    public function entertainmentCountryMappings()
    {
        return $this->hasMany(EntertainmentCountryMapping::class,'entertainment_id','id')->with('country');
    }

    public function entertainmentStreamContentMappings()
    {
        return $this->hasMany(EntertainmentStreamContentMapping::class,'entertainment_id','id');
    }

    public function entertainmentDownloadMappings()
    {
        return $this->hasMany(EntertainmnetDownloadMapping::class,'entertainment_id','id');
    }


    public function EntertainmentDownload()
    {
        return $this->hasMany(EntertainmentDownload::class,'entertainment_id','id');
    }


    public function entertainmentTalentMappings()
    {
        return $this->hasMany(EntertainmentTalentMapping::class,'entertainment_id','id')->with('talentprofile');
    }

    public function plan()
    {
        return $this->hasOne(Plan::class, 'id', 'plan_id');
    }

    public function entertainmentReviews()
    {
        return $this->hasMany(Review::class,'entertainment_id','id');
    }

    public function entertainmentLike()
    {
        return $this->hasMany(Like::class,'entertainment_id','id');
    }

    public function entertainmentView()
    {
        return $this->hasMany(EntertainmentView::class, 'entertainment_id', 'id');
    }

    public function UserReminder()
    {
        return $this->hasMany(UserReminder::class,'entertainment_id','id');
    }

    public function UserRemind()
    {
        return $this->hasOne(UserReminder::class,'entertainment_id','id');
    }

    public function Watchlist()
    {
        return $this->hasMany(Watchlist::class,'entertainment_id','id');
    }


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($entertainment) {

            if ($entertainment->isForceDeleting()) {

                $entertainment->entertainmentGenerMappings()->forceDelete();
                $entertainment->entertainmentStreamContentMappings()->forceDelete();
                $entertainment->entertainmentTalentMappings()->forceDelete();
                $entertainment->entertainmentReviews()->forceDelete();
                $entertainment->entertainmentDownloadMappings()->forceDelete();
                $entertainment->EntertainmentDownload()->forceDelete();
                $entertainment->entertainmentLike()->forceDelete();
                $entertainment->UserReminder()->forceDelete();
                $entertainment->Watchlist()->forceDelete();


            } else {

                $entertainment->entertainmentGenerMappings()->delete();
                $entertainment->entertainmentStreamContentMappings()->delete();
                $entertainment->entertainmentTalentMappings()->delete();
                $entertainment->entertainmentReviews()->delete();
                $entertainment->entertainmentDownloadMappings()->delete();
                $entertainment->EntertainmentDownload()->delete();
                $entertainment->entertainmentLike()->delete();
                $entertainment->UserReminder()->delete();
                $entertainment->Watchlist()->delete();

            }

        });

        static::restoring(function ($entertainment) {

            $entertainment->entertainmentGenerMappings()->withTrashed()->restore();
            $entertainment->entertainmentStreamContentMappings()->withTrashed()->restore();
            $entertainment->entertainmentTalentMappings()->withTrashed()->restore();
            $entertainment->entertainmentReviews()->withTrashed()->restore();
            $entertainment->entertainmentDownloadMappings()->withTrashed()->restore();
            $entertainment->EntertainmentDownload()->withTrashed()->restore();
            $entertainment->entertainmentLike()->withTrashed()->restore();
            $entertainment->UserReminder()->withTrashed()->restore();
            $entertainment->Watchlist()->withTrashed()->restore();
        });
    }

    public function season()
    {
        return $this->hasMany(Season::class, 'entertainment_id')->with('plan', 'episodes');
    }


    public function episode()
    {
        return $this->hasMany(Episode::class,'entertainment_id')->with('plan','EpisodeStreamContentMapping');
    }

}
