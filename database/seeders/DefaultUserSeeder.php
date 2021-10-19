<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DefaultUserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return  void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $roleAdmin  = Role::whereName('Admin')->first();
        $inputAdmin = [
            'name' => $faker->word,
            'is_active' => $faker->boolean(true),
            'created_at' => $faker->date(),
            'updated_at' => $faker->date(),
            'added_by' => $faker->numberBetween(1,5),
            'updated_by' => $faker->numberBetween(1,5),
            'username' => 'yessenia02',
            'email' => 'cecilia.nitzsche@hotmail.com',
            'password' => Hash::make(']L;tcz'),
            'email_verified_at' => Carbon::now(),
        ];

        $userAdmin  = User::create($inputAdmin);
        $userAdmin->assignRole($roleAdmin);
            
        $roleUser  = Role::whereName('User')->first();
        $inputUser = [
            'name' => $faker->word,
            'is_active' => $faker->boolean(true),
            'created_at' => $faker->date(),
            'updated_at' => $faker->date(),
            'added_by' => $faker->numberBetween(1,5),
            'updated_by' => $faker->numberBetween(1,5),
            'username' => 'steuber.berry',
            'email' => 'xstoltenberg@damore.org',
            'password' => Hash::make('&quot;&quot;TwQPG~'),
            'email_verified_at' => Carbon::now(),
        ];

        $userUser  = User::create($inputUser);
        $userUser->assignRole($roleUser);
            
    }
}
