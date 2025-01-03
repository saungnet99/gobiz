<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plans')->insert([
            "plan_id" => "60673288f0d35",
            "plan_type"  => "BOTH",
            "plan_name"  => "Beginner",
            "plan_description"  => "Nullam diam arcu, sodales quis convallis sit amet, sagittis varius ligula.",
            "plan_price"  => 0,
            "validity"  => 31,
            "no_of_vcards"  => 1,
            "no_of_services"  => 5,
            "no_of_vcard_products"  => 5,
            "no_of_links"  => 5,
            "no_of_payments"  => 5,
            "no_of_galleries"  => 5,
            "no_testimonials" => 5,
            "business_hours"  => 1,
            "contact_form"  => 1,
            "appointment"  => 0,
            "no_of_enquires"  => 10,
            "no_of_stores"  => 1,
            "no_of_categories"  => 2,
            "no_of_store_products"  => 5,
            "pwa"  => 1,
            "password_protected" => 0,
            "advanced_settings" => 0,
            "additional_tools" => 0,
            "personalized_link"  => 0,
            "hide_branding"  => 0,
            "free_setup"  => 0,
            "free_support"  => 0,
            "is_private"  => 0
        ]);

        DB::table('plans')->insert([
            "plan_id" => "606732aa4fb58",
            "plan_type"  => "BOTH",
            "plan_name"  => "Intermediate",
            "plan_description"  => "Nullam diam arcu, sodales quis convallis sit amet, sagittis varius ligula.",
            "plan_price"  => 24,
            "validity"  => 31,
            "no_of_vcards"  => 5,
            "no_of_services"  => 10,
            "no_of_vcard_products"  => 10,
            "no_of_links"  => 10,
            "no_of_payments"  => 10,
            "no_of_galleries"  => 10,
            "no_testimonials" => 10,
            "business_hours"  => 1,
            "contact_form"  => 1,
            "appointment"  => 1,
            "no_of_enquires"  => 100,
            "no_of_stores"  => 5,
            "no_of_categories"  => 5,
            "no_of_store_products"  => 100,
            "pwa"  => 1,
            "password_protected" => 1,
            "advanced_settings" => 1,
            "additional_tools" => 1,
            "personalized_link"  => 1,
            "hide_branding"  => 1,
            "free_setup"  => 0,
            "free_support"  => 1,
            "is_private"  => 0
        ]);

        DB::table('plans')->insert([
            "plan_id" => "606732cb4ec9b",
            "plan_type"  => "BOTH",
            "plan_name"  => "Professional",
            "plan_description"  => "Nullam diam arcu, sodales quis convallis sit amet, sagittis varius ligula.",
            "plan_price"  => 48,
            "validity"  => 31,
            "no_of_vcards"  => 999,
            "no_of_services"  => 999,
            "no_of_vcard_products"  => 999,
            "no_of_links"  => 999,
            "no_of_payments"  => 999,
            "no_of_galleries"  => 999,
            "no_testimonials" => 999,
            "business_hours"  => 1,
            "contact_form"  => 1,
            "appointment"  => 1,
            "no_of_enquires"  => 999,
            "no_of_stores"  => 999,
            "no_of_categories"  => 999,
            "no_of_store_products"  => 999,
            "pwa"  => 1,
            "password_protected" => 1,
            "advanced_settings" => 1,
            "additional_tools" => 1,
            "personalized_link"  => 1,
            "hide_branding"  => 1,
            "free_setup"  => 1,
            "free_support"  => 1,
            "is_private"  => 0
        ]);
    }
}
