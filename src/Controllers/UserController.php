<?php

namespace Properos\Users\Controllers;

use Illuminate\Http\Request;
use Properos\Users\Classes\CUser;
use Properos\Base\Controllers\ApiController;
use Properos\Condo\Models\Unit;
use Spatie\Permission\Models\Role;
use Properos\Users\Models\User;
use stdClass;

class UserController extends ApiController
{

    protected $user;
    protected $cUser;

    public function __construct($user = null, $cUser = null)
    {
        $this->user = $user ?  $user : new User();
        $this->cUser = $cUser ?  $cUser : new CUser();
    }
    public function index()
    {
        $roles = Role::all();
        return view('be.users.index')->with(['roles' => $roles]);
    }

    public function usersSearch(Request $request)
    {
        $cUser = new CUser();
        $options = $cUser->standardize_search($request);
        $users = $cUser->find($options);

        return $this->setPagination($cUser->get_paginator()->toArray())->respondData('Users search result.', $users);
    }

    public function setUser($user_id)
    {
        \Session::put('return_user', \Auth::user()->id);

        \Auth::loginUsingId($user_id);
        return redirect('/');
    }

    public function returnUser()
    {
        if (\Session::has('return_user')) {
            \Auth::loginUsingId(\Session::pull('return_user', 1));
            return redirect('/admin/users');
        } else {
            return redirect('/');
        }
    }
    public function createUser()
    {
        $roles = Role::all();
        $units = Unit::where('status', 'active')->get();
        return view('be.users.create-user')->with([
            'roles' => $roles,
            'units' => $units
        ]);
    }

    public function editUser($id)
    {
        if ($id > 0) {
            $user = $this->user->where('id', $id)->with(['affiliate'])->first();
            if ($user) {
                $modified_user = new \stdClass();
                $modified_user = $user;
                $modified_user->_roles = $user->getCurrentRoles();
                $roles = Role::all();
                $units = \Properos\Condo\Models\Unit::where('status', 'active')->get();
                return view('be.users.create-user', [
                    'roles' => $roles,
                    'units' => $units,
                    'editable_user' => $modified_user
                ]);
            } else {
                abort(404);
            }
        }
    }

    public function profile()
    {
        return view('be.users.my-profile')->with([
            'editable_user' => \Auth::user(),
        ]);
    }
}
