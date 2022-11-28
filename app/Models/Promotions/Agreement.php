<?php

namespace App\Models\Promotions;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'promotions.agreement';
    protected $fillable = [
        "merchant_id",
        "transaction_id",
        "document_number",
        "document_name",
        "description",
        "attachment",
        "fund",
        "approved",
        "approved_by",
        "available_fund"
    ];

    public function promotion(){
        return $this->belongsTo(\App\Models\Promotions\Promotion::class, 'promotion_id', 'id');
    }

    public function promotions()
    {
        return $this->hasMany(\App\Models\Promotions\Promotion::class, 'id','promotion_id');
    }

    public function funds()
    {
        return $this->hasMany(\App\Models\Promotions\Funds::class, 'agreement_id', 'id');
    }
}
