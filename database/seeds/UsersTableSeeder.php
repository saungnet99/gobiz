<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'user_id' => '609c03880ee47',
            'role_id' => '1',
            'permissions' => '{"blogs": 1,"pages": 1,"plans": 1,"users": 1,"themes": 1,"sitemap": 1,"customers": 1,"invoice_tax": 1,"transactions": 1,"translations": 1,"payment_methods": 1,"software_update": 1,"general_settings": 1}',
            'name' => 'GoBiz',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin@admin'),
            'auth_type' => 'Email',
        ]);
    }
}
