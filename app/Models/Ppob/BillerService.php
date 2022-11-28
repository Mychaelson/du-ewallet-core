<?php

namespace App\Models\Ppob;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillerService extends Model
{
    protected $table = 'ppob.biller_services';
    use HasFactory;
    protected $fillable = ['name', 'desctription', 'biller_id', 'service', 'status'];

}
