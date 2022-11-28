<?php

namespace App\Models\Ppob;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalCategories extends Model
{
    use HasFactory;

    protected $table = 'ppob.digital_categories';
    protected  $primaryKey = 'id';
    public $timestamps = false;

    protected $casts = [
        'meta' => 'array'
    ];

    protected $fillable = ['name', 'slug', 'description', 'image', 'parent_id', 'status', 'type', 'meta'];

    public function parent()
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    public function childs()
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }

    /* public function products()
    {
        return $this->hasMany(DigitalProducts::class, 'category_id');
    } */

    public function products()
    {
        return $this->hasMany(ProductV2::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
