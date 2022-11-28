<?php
namespace App\Models\Lang;

use Illuminate\Database\Eloquent\Model;

class ProjectScreenTranslation extends Model
{
    protected $table = 'lang.prj_screen_trans_lang';
    
    const STATUS_DELETE = 0;
    const STATUS_ACTIVE = 1;
    
    protected $fillable = [
        'screen_trans_id',
        'trans_lang_id',
        'translation',
        'status',
        'last_status_date'
    ];
}
