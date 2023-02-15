<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $guarded = [];
    // ###############################################
    // ################## RELATIONSHIPS ###################
    // ###############################################
    public function user() {
        return $this->belongsTo(User::class);
    }
    // ###############################################
    // ################## FUNCTIONS ###################
    // ###############################################
    public function scopeUnread($query) {
        return $query->where('read',false);
    }
    public function scopeRead($query) {
        return $query->where('read',true);
    }
} 
