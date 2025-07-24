<?php

namespace Modules\Banner\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'banners';
    protected $fillable = ['title', 'file_url','poster_url','type', 'type_id','type_name', 'status', 'created_by'];
    const CUSTOM_FIELD_MODEL = 'Modules\Banner\Models\Banner';

}
