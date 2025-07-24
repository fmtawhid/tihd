<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use App\Models\Traits\HasSlug;

class MobileSetting extends Model
{
    use HasFactory;
    // use HasSlug;
    use SoftDeletes;

    protected $fillable = ['name','slug', 'position', 'value'];
    public static function getValueBySlug($slug)
    {
        // Retrieve the setting by slug
        $setting = self::where('slug', $slug)->first();


        // If the setting exists, return its value, otherwise return null
        return $setting ? $setting->value : null;
    }
}
