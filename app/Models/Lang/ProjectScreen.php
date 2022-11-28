<?php
namespace App\Models\Lang;

use Illuminate\Database\Eloquent\Model;

class ProjectScreen extends Model
{
    protected $table = 'lang.project_screen';

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'project_version_id',
        'screen_name',
        'screen_description',
        'status',
        'last_status_date'
    ];

    
}

