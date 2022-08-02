<?php

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Properos\Users\Models\User;
use Properos\Users\Models\UserProfile;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (count(User::all()) == 0) {
            if (count(UserProfile::all()) > 0) {
                DB::table('user_profiles')->truncate();
            }

            \DB::table('users')->insert([
                'firstname' => "Larry",
                'lastname' => "Berman",
                'email' => "larry@gmail.com",
                'password' => bcrypt('larry123'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            \DB::table('users')->insert([
                'firstname' => "Dulce",
                'lastname' => "Naranjo",
                'email' => "dulce@gmail.com",
                'password' => bcrypt('dulce123'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $admins = User::where('email', '=', ['larry@gmail.com'])->get();
            if (count($admins) > 0) {
                foreach ($admins as $key => $admin) {
                    $admin->assignRole('admin');
                }
            }

            $managers = User::where('email', '=', ['dulce@gmail.com'])->get();
            if (count($managers) > 0) {
                foreach ($managers as $key => $manager) {
                    $manager->assignRole('manager');
                }
            }

            foreach (User::all() as $key => $user) {
                \DB::table('user_profiles')->insert([
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
