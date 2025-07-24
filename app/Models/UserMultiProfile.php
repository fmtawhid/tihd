<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\BaseModel;

class UserMultiProfile extends BaseModel
{
    use HasFactory;
    protected $table = 'user_multi_profiles';

    protected $fillable = [
        'user_id',
        'name',
        'avatar'
    ];

    public function activeprofile()
    {
        return $this->hasOne(Device::class , 'active_profile');
    }

}
