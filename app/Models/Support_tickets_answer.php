<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support_tickets_answer extends Model
{   
    protected $table = 'support_tickets_answers';

    use HasFactory;
    protected $fillable = [
        'support_ticket_id',
        'from',
        'to',
        'content'
    ];


    public function support_ticket(){
        return $this->belongsTo(Support_ticket::class,'support_ticket_id');
    }
}
