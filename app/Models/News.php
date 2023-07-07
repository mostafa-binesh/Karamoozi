<?php

namespace App\Models;

use App\Traits\CPaginationTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class News extends Model
{
    use HasFactory, Notifiable, Filterable;

    use CPaginationTrait;

    protected $table='news';
    protected $fillable= [
        'title',
        'body',
        'image',

    ];
}

