<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'owner_id',
        'code'
    ];

    public function Owner(){
        return $this->belongsTo(User::class);
    }

    public function Members(){
        return $this->HasMany(User::class, "current_league_id");
    }

    public function LeaguePlayerMatch(){
        return $this->hasMany(LeaguePlayerMatch::class);
    }
}
