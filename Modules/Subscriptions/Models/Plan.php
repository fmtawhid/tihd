<?php

namespace Modules\Subscriptions\Models;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends BaseModel
{
    use SoftDeletes;

    protected $table = 'plan';

    protected $fillable = ['name', 'identifier','android_identifier','apple_identifier', 'level', 'duration', 'duration_value', 'price', 'description', 'status', 'discount', 'discount_percentage', 'total_price'];

    const CUSTOM_FIELD_MODEL = 'Modules\Subscriptions\Models\Plan';

    public function planLimitation()
    {
        return $this->hasMany(PlanLimitationMapping::class, 'plan_id', 'id')->with('limitation_data');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withTrashed();
    }


 


}

