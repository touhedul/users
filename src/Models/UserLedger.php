<?php

namespace Properos\Users\Models;

use Illuminate\Database\Eloquent\Model;

class UserLedger extends Model
{

    protected $fillable = [
        'user_id','type', 'description','debit','credit','sbalance','ebalance', 'status' 
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
