<?php
namespace App\Models\Lang;

use Askync\Utils\Facades\AskyncResponse;
use Askync\Utils\Utils\ResponseException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProjectVersion extends Model
{
    protected $table = 'lang.project_version';

    const STATUS_DELETE = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'project_id',
        'project_version',
        'last_update',
        'status',
        'last_status_date'
    ];

    protected $casts = [
        'last_update' => 'datetime'
    ];


}
