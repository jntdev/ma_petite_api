<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaguePlayerMatch extends Model
{
    use HasFactory;
    protected $fillable =[
        'user_id',
        'league_id',
    ];

    public function User(){
        return $this->belongsTo(User::class);
    }
    public function League(){
        return $this->belongsTo(League::class);
    }
}
