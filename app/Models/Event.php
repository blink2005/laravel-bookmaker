<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_category',
        'event_name',
        'date_start',
        'time_start',
        'parameters',
        'status',
    ];
}
