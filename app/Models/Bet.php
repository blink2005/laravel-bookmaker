<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id_better',
        'sum_bet',
        'coefficient',
        'status',
        'event_id',
        'event_name',
        'event_team',
        'event_result',
    ];
}
