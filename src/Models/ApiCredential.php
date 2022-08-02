<?php

namespace Properos\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Properos\Users\Models\User;

class ApiCredential extends Model
{
    protected $fillable = [
        'user_id','api','name','data'
    ];

    protected $casts = ['data' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
