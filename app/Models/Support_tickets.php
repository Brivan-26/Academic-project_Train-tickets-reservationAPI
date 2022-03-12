<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support_tickets extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'description', 'is_assigend', 'is_active'];
}
