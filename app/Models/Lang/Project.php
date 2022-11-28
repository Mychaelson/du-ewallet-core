<?php
namespace App\Models\Lang;

use Askync\Utils\Facades\AskyncResponse;
use Askync\Utils\Utils\ResponseException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'lang.project';
    
    const STATUS_DELETE = 0;
    const STATUS_ACTIVE = 1;

    protected $fillable = [
        'project_uid',
        'project_description',
        'project_image',
        'status',
        'last_status_date'
    ];

   
}
