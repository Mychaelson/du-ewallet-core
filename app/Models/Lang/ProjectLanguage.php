<?php
namespace App\Models\Lang;

use Illuminate\Database\Eloquent\Model;

class ProjectLanguage extends Model
{
    protected $table = 'lang.project_language';

    const STATUS_DELETE = 0;
    const STATUS_ACTIVE = 1;
    
    protected $fillable = [
        'hl',
        'flag',
        'status',
        'last_status_date'
    ];

}
