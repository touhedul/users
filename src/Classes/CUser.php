<?php

namespace Properos\Users\Classes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Properos\Users\Models\User;
use Properos\Base\Classes\Base;
use Properos\Base\Exceptions\ApiException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Properos\Users\Models\UserAddress;

class CUser extends Base
{
    protected $user_model;

    function __construct($user = null)
    {
        $this->user_model = $user ? new $user : new User();
        parent::__construct($this->user_model, 'User');
    }

    public function init_fillable()
    {
        foreach ($this->model->getFillable() as $key) {
            $this->fillable[$key] = null;
        }
    }

    public function storeUser(array $data)
    {
        $validator = Validator::make($data, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|max:255',
            'roles' => 'required'
        ]);
        if ($validator->passes()) {
            $exiting_user = $this->user_model->where('email', $data['email'])->first();
            if (!$exiting_user) {
                $user = new $this->user_model;
                $user->firstname = $data['firstname'];
                $user->lastname = $data['lastname'];
                $user->email = $data['email'];
                $user->status = 'active';
                $user->affiliate_id = isset($data['affiliate_id']) ? $data['affiliate_id'] : 0;
                $user->percent = isset($data['percent']) ? $data['percent'] : 0;
                $user->password = isset($data['password']) ? bcrypt($data['password']) : bcrypt(Str::random(11));
                if (isset($data['phone'])) {
                    $user->phone = $data['phone'];
                }

                if ($user->save()) {
                    if (count($data['roles']) > 0) {
                        $roles = $data['roles'];
                        foreach ($roles as $key => $rol) {
                                if (isset($rol['name']) && !isset($rol['acc_id'])) {
                                    $user->assignRole($rol['name']);
                                } else if (isset($rol['name']) && isset($rol['acc_id'])) {
                                    $unit = \Properos\Condo\Models\Unit::with('users')->find($rol['acc_id']);
                                    if ($unit) {
                                        $user->assignRole($rol['name'], $unit);
                                        $unit->users()->attach($user->id);
                                    } else {
                                        if (!$user->hasAnyRole()) {
                                            $user->status();
                                        }
                                    }
                                }
                            }       
                    } else {
                        throw new ApiException("User role for this unit is required", '003');
                    }
                }
                return $user;
            } else {
                throw new ApiException("An user with this email address is already registered", '003');
            }
        } else {
            throw new ApiException($validator->errors(), '004', $data);
        }
    }

    public function updateUser(array $data)
    {
        $validator = Validator::make($data, [
            'id' => 'required',
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|max:255',
        ]);
        if ($validator->passes()) {
            if (\Auth::user()->hasRole('admin') || \Auth::user()->id == $data['id']) {
                $user = $this->user_model->find($data['id']);
                if ($user) {
                    $user->firstname = $data['firstname'];
                    $user->lastname = $data['lastname'];
                    if($user->email != $data['email']){
                        $exiting_user = $this->user_model->where('email', $data['email'])->where('id', '<>', $data['id'])->first();
                        if ($exiting_user) {
                            throw new ApiException("An user with this email address is already registered", '003');
                        }else{
                            $user->email = $data['email'];
                        }
                    }
                    $user->affiliate_id = isset($data['affiliate_id']) ? $data['affiliate_id'] : 0;
                    $user->percent = isset($data['percent']) ? $data['percent'] : 0;

                    if (isset($data['phone'])) {
                        $user->phone = $data['phone'];
                    }
                    if (isset($data['status'])) {
                        $user->status = $data['status'];
                    }
                    // $user->save();
                    if ($user->save()) {
                        if (isset($data['roles'])) {
                            if (count($data['roles']) > 0) {
                                $roles = $data['roles'];
                                foreach ($roles as $key => $rol) {
                                    if (isset($rol['name']) && !isset($rol['acc_id'])) {
                                        if (!$user->hasRole($rol['name'])) {
                                            $user->assignRole($rol['name']);
                                        }
                                    } 
                                    else if (isset($rol['name']) && isset($rol['acc_id'])) {
                                        $unit = \Properos\Condo\Models\Unit::find($rol['acc_id']);
                                        if ($unit) {
                                            if (!$user->hasRole($rol['name'], $unit)) {
                                                $user->assignRole($rol['name'], $unit);
                                                $unit->users()->attach($user->id);
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            // throw new ApiException("User role for this unit is required", '003');
                        }
                        if(isset($data['addresses']) && count($data['addresses']) > 0){
                            $addressFound = false;
                            if(count($user->addresses) > 0){
                                $addresses = UserAddress::where('user_id',$user->id)->where('default',1)->first();
                                if($addresses){
                                    $addressFound = true;
                                    if(isset($data['addresses'][0]['address1'])){
                                        $addresses->address1 = $data['addresses'][0]['address1'];
                                    }
                                    if(isset($data['addresses'][0]['address2'])){
                                        $addresses->address2 = $data['addresses'][0]['address2'];
                                    }
                                    if(isset($data['addresses'][0]['city'])){
                                        $addresses->city = $data['addresses'][0]['city'];
                                    }
                                    if(isset($data['addresses'][0]['state'])){
                                        $addresses->state = $data['addresses'][0]['state'];
                                    }
                                    if(isset($data['addresses'][0]['zip'])){
                                        $addresses->zip = $data['addresses'][0]['zip'];
                                    }
                                    if(isset($data['addresses'][0]['country'])){
                                        $addresses->country = $data['addresses'][0]['country'];
                                    }
                                    $addresses->default = 1;
                                    $addresses->save();
                                }
                            }
                            if(!$addressFound){
                                $addresses = new UserAddress();
                                if(isset($data['addresses'][0]['address1'])){
                                    $addresses->address1 = $data['addresses'][0]['address1'];
                                }
                                if(isset($data['addresses'][0]['address2'])){
                                    $addresses->address2 = $data['addresses'][0]['address2'];
                                }
                                if(isset($data['addresses'][0]['city'])){
                                    $addresses->city = $data['addresses'][0]['city'];
                                }
                                if(isset($data['addresses'][0]['state'])){
                                    $addresses->state = $data['addresses'][0]['state'];
                                }
                                if(isset($data['addresses'][0]['zip'])){
                                    $addresses->zip = $data['addresses'][0]['zip'];
                                }
                                if(isset($data['addresses'][0]['country'])){
                                    $addresses->country = $data['addresses'][0]['country'];
                                }
                                $addresses->default = 1;
                                $addresses->user_id = $user->id;
                                $addresses->save();
                               
                            }
                        }
                    }

                    $modified_user = new \stdClass();
                    $modified_user = $user;
                    $modified_user->_roles = $user->getCurrentRoles();
                    $modified_user->addresses = $user->addresses();
                    return $modified_user;
                } else {
                    throw new ApiException("User not found", '404');
                }
            }
            throw new ApiException("Access forbidden", '404');
        } else {
            throw new ApiException($validator->errors(), '004', $data);
        }
    }

    public function removeRole(array $data)
    {
        $validator = Validator::make($data, [
            'user_id' => 'required',
            'role' => 'required'
        ]);
        if ($validator->passes()) {
            $user = $this->user_model->find($data['user_id']);
            if ($user) {
                $removed_role = [];
                if (isset($data['restrictable_id'])) {
                    $restrictable = \Properos\Condo\Models\Unit::find($data['restrictable_id']);
                    $user->removeRole($data['role'], $restrictable);
                    $restrictable->users()->detach($user->id);
                    $removed_role['restrictable_id'] = $data['restrictable_id'];
                } else {
                    $user->removeRole($data['role']);
                }
                $user->removeRole($data['role']);
                $removed_role['role'] = $data['role'];
                return $removed_role;
            } else {
                throw new ApiException("User not found", '404');
            }
        } else {
            throw new ApiException($validator->errors(), '004', $data);
        }
    }
}
