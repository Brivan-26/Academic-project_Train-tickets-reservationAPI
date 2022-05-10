<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support_ticket extends Model
{
    use HasFactory;
    
    protected $table = "support_tickets";

    protected $fillable = ['user_id', 'description', 'title', 'assigned_to', 'is_active'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class,'assigned_to');
    }

    public function answers(){
        return $this->hasMany(Support_tickets_answer::class);
    }

}
