<?php

namespace App\Models\Connect;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppsCredential extends Model
{
    use HasFactory;

    protected $table = 'connect.apps_credentials';
}
