<?php

namespace Properos\Users\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Properos\Users\Models\UserAddress;
use Properos\Users\Models\UserProfile;
use App\Models\Lead;
use Laravel\Passport\HasApiTokens;

// use Properos\Commerce\Models\Order;
// use Properos\Commerce\Traits\UserTrait;
// use Properos\Condo\Traits\UserCondoTrait;
// use Properos\Crm\Traits\UserCrmTrait;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    use HasRoles;
    use HasApiTokens;
    // use UserTrait;
    // use UserCondoTrait;
    // use UserCrmTrait;

    public $current_roles = [];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'phone', 'avatar', 'company', 'affiliate_id', 'percent', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $searchable = [
        'firstname', 'lastname', 'email', 'company', 'phone'
    ];

    public $index_fulltext = false;

    public function toSearchableArray()
    {
        return array_flip($this->searchable);
    }

    public function getSearchableArray()
    {
        return $this->searchable;
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function getCurrentRoles()
    {
        foreach ($this->roles()->get() as $role) {
            $partial_role = new \stdClass();
            $partial_role->id = $role->id;
            $partial_role->name = $role->name;
            $this->current_roles[] = $partial_role;
        }
        if (isset($this->units) && $this->units->count() > 0) {
            foreach ($this->units as $acc) {
                foreach ($this->roles($acc)->get() as $role) {
                    $partial_role = new \stdClass();
                    $partial_role->id = $role->id;
                    $partial_role->name = $role->name;
                    $partial_role->acc_id = $role->pivot->restrictable_id;
                    $this->current_roles[] = $partial_role;
                }
            }
        }
        return $this->current_roles;
    }

    public function isRole($_role)
    {
        $count = 0;
        foreach ($this->units as $acc) {
            foreach ($this->roles($acc)->get() as $role) {
                if ($role['name'] == $_role) {
                    $count++;
                    break;
                }
            }
        }
        return $count > 0 ? true : false;
    }

    // //Commerce package
    // public function orders()
    // {
    //     return $this->hasMany(Order::class);
    // }

    public function affiliate()
    {
        return $this->hasOne(User::class, 'id', 'affiliate_id');
    }

    //CRM Leads
    public function leads()
    {
        return $this->hasMany(Lead::class, 'assigned_id', 'id');
    }

    public function user_roles()
    {
        return $this->morphToMany(
            config('permission.models.role'),
            'model',
            config('permission.table_names.model_has_roles'),
            'model_id',
            'role_id'
        )->withPivot('restrictable_id', 'restrictable_type');
    }
}
