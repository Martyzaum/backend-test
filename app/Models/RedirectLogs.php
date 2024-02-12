<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedirectLogs extends Model
{
    use HasFactory;

    protected $hidden = ['id', 'redirect_id'];
}
