<?php

namespace Properos\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Properos\Users\Models\User;

class UserAddress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'address1', 'address2', 'city', 'zip', 'state', 'country', 'default'
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
