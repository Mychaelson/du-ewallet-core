<?php
namespace App\Models\Lang;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProjectScreenTranslationList extends Model
{
    protected $table = 'lang.prj_screen_trans';
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'key',
        'default_translation',
        'project_screen_id',
        'status',
        'last_status_date'
    ];

}
