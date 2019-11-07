<?php

use App\Laravue\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@laravue.dev',
            'password' => Hash::make('laravue'),
            'roles' => json_encode(['data' => ['admin']])
        ]);
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@laravue.dev',
            'password' => Hash::make('laravue'),
            'roles' => json_encode(['data' => ['admin']])
        ]);
        $editor = User::create([
            'name' => 'Editor',
            'email' => 'editor@laravue.dev',
            'password' => Hash::make('laravue'),
            'roles' => json_encode(['data' => ['admin']])
        ]);
        $user = User::create([
            'name' => 'User',
            'email' => 'user@laravue.dev',
            'password' => Hash::make('laravue'),
            'roles' => json_encode(['data' => ['admin']])
        ]);
        $visitor = User::create([
            'name' => 'Visitor',
            'email' => 'visitor@laravue.dev',
            'password' => Hash::make('laravue'),
            'roles' => json_encode(['data' => ['admin']])
        ]);

        $this->call(UsersTableSeeder::class);
        $this->call(EntityTableSeeder::class);
        $this->call(OperationTableSeeder::class);
    }
}
