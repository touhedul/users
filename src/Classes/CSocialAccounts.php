<?php

namespace Properos\Users\Classes;

use Laravel\Socialite\Contracts\User as ProviderUser;
use Properos\Users\Models\User;
use Properos\Users\Models\ApiCredential;
use Spatie\Permission\Models\Role;

class CSocialAccounts
{
	public function GetOrCreateUser(ProviderUser $provider_user, $provider)
    {
        // $ref_id = 0;
        // if(\Cookie::get('code') != null){
        //     $code = \Cookie::get('code');
        //     $get_user = User::where('reference_code',$code)->first();
        //     $ref_id = $get_user['id'];
        // }

        $credential = ApiCredential::where([['api', $provider],['data->provider_user_id',$provider_user->getId()]])->first();
        if ($credential) {
            return User::where('id',$credential->user_id)->first();
        } else {
            $credential = new ApiCredential([
                'user_id' => 0,
                'api' => $provider,
                'name' => $provider_user->getEmail(),
                'data' => [
                    'provider_user_id' => $provider_user->getId(),
                    'user_token' => $provider_user->token,
                    'avatar' => $provider_user->avatar
                ]   
            ]);
            $user = User::where('email',$provider_user->getEmail())->first();
            if (!$user) {
                $ref_code = str_random(5);
                $name = explode(" ",$provider_user->getName());
                $user = User::create([
                        'email' => $provider_user->getEmail(),
                        'firstname' => (count($name) >= 3) ? $name[0].' '.$name[1] : $name[0],
                        'lastname' => (count($name) >= 3) ? $name[2] : (count($name) == 2) ? $name[1] : '',
                        'status' => 'active',
                        'password' => \bcrypt(str_random(10)),
                        // 'reference_id'=>$ref_id,
                        // 'reference_code'=>$ref_code
                ]);
                // dispatch(new \App\Jobs\WelcomeJob($user));
                $this->defaultRoleAssignment($user);
            }

            $credential->user_id = $user->id;
            $credential->save();
        }
        // \Cookie::queue(\Cookie::forget('code'));
        return $user;
    }

    public function defaultRoleAssignment($user)
    {
        $user->assignRole('customer');
    }
}