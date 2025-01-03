<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            "google_key" => "",
            "google_analytics_id" => "",
            "site_name" => "GoBiz",
            "site_logo" => "img/logo.png",
            "favicon" => "img/favicon.png"
        ]);
    }
}
