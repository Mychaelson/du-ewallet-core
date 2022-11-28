<?php

namespace App\Models\Ppob;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedNumbers extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'customer_id', 'product_id', 'code', 'category'];
    protected $table = 'ppob.saved_numbers';
    protected  $primaryKey = 'id';
    public $timestamps = false;
}
