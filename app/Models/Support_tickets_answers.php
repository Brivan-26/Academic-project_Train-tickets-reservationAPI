<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support_tickets_answers extends Model
{
    use HasFactory;

    protected $fillable = [
        'support_ticket_id',
        'from',
        'to',
        'content'
    ];
}
