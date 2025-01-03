<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        // Urban Templates
        DB::table('themes')->insert([
            "theme_id" => "588969111086",
            "theme_code" => "premium-black",
            "theme_thumbnail" => "premium-black.png",
            "theme_name" => "Urban Black",
            "theme_description" => "vCard",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111087",
            "theme_code" => "premium-indigo",
            "theme_thumbnail" => "premium-indigo.png",
            "theme_name" => "Urban Indigo",
            "theme_description" => "vCard",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111088",
            "theme_code" => "premium-red",
            "theme_thumbnail" => "premium-red.png",
            "theme_name" => "Urban Red",
            "theme_description" => "vCard",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111089",
            "theme_code" => "premium-pink",
            "theme_thumbnail" => "premium-pink.png",
            "theme_name" => "Urban Pink",
            "theme_description" => "vCard",
            "theme_price" => "Free"
        ]);


        // Neo
        DB::table('themes')->insert([
            "theme_id" => "588969111090",
            "theme_code" => "ultra-premium-black",
            "theme_thumbnail" => "ultra-premium-black.png",
            "theme_name" => "Neo Black",
            "theme_description" => "vCard",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111091",
            "theme_code" => "ultra-premium-indigo",
            "theme_thumbnail" => "ultra-premium-indigo.png",
            "theme_name" => "Neo Indigo",
            "theme_description" => "vCard",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111092",
            "theme_code" => "ultra-premium-red",
            "theme_thumbnail" => "ultra-premium-red.png",
            "theme_name" => "Neo Red",
            "theme_description" => "vCard",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111093",
            "theme_code" => "ultra-premium-pink",
            "theme_thumbnail" => "ultra-premium-pink.png",
            "theme_name" => "Neo Pink",
            "theme_description" => "vCard",
            "theme_price" => "Free"
        ]);

        // Gradient Lime
        DB::table('themes')->insert([
            "theme_id" => "588969111095",
            "theme_code" => "lime-blue",
            "theme_thumbnail" => "lime-blue.png",
            "theme_name" => "Gradient Lime",
            "theme_description" => "vCard",
            "theme_price" => "Free"
        ]);

        // Elite Red
        DB::table('themes')->insert([
            "theme_id" => "588969111094",
            "theme_code" => "premium-1",
            "theme_thumbnail" => "premium-1.png",
            "theme_name" => "Elite Red",
            "theme_description" => "vCard",
            "theme_price" => "Free"
        ]);

        // Store
        // Modern (Store) Light
        DB::table('themes')->insert([
            "theme_id" => "588969111070",
            "theme_code" => "modern-store-light-blue",
            "theme_name" => "Modern Blue Light",
            "theme_description" => "WhatsApp Store",
            "theme_thumbnail" => "modern-store-light-blue.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111071",
            "theme_code" => "modern-store-light-indigo",
            "theme_name" => "Modern Indigo Light",
            "theme_description" => "WhatsApp Store",
            "theme_thumbnail" => "modern-store-light-indigo.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111072",
            "theme_code" => "modern-store-light-green",
            "theme_name" => "Modern Green Light",
            "theme_description" => "WhatsApp Store",
            "theme_thumbnail" => "modern-store-light-green.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111073",
            "theme_code" => "modern-store-light-yellow",
            "theme_name" => "Modern Yellow Light",
            "theme_description" => "WhatsApp Store",
            "theme_thumbnail" => "modern-store-light-yellow.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111074",
            "theme_code" => "modern-store-light-red",
            "theme_name" => "Modern Red Light",
            "theme_description" => "WhatsApp Store",
            "theme_thumbnail" => "modern-store-light-red.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111075",
            "theme_code" => "modern-store-light-purple",
            "theme_name" => "Modern Purple Light",
            "theme_description" => "WhatsApp Store",
            "theme_thumbnail" => "modern-store-light-purple.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111076",
            "theme_code" => "modern-store-light-pink",
            "theme_name" => "Modern Pink Light",
            "theme_description" => "WhatsApp Store",
            "theme_thumbnail" => "modern-store-light-pink.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111077",
            "theme_code" => "modern-store-light-gray",
            "theme_name" => "Modern Gray Light",
            "theme_description" => "WhatsApp Store",
            "theme_thumbnail" => "modern-store-light-gray.png",
            "theme_price" => "Free"
        ]);

        // Store Dark
        DB::table('themes')->insert([
            "theme_id" => "588969111078",
            "theme_code" => "modern-store-dark-blue",
            "theme_name" => "Modern Blue Dark",
            "theme_description" => "WhatsApp Store",
            "theme_thumbnail" => "modern-store-dark-blue.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111079",
            "theme_code" => "modern-store-dark-indigo",
            "theme_name" => "Modern Indigo Dark",
            "theme_description" => "WhatsApp Store",
            "theme_thumbnail" => "modern-store-dark-indigo.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111080",
            "theme_code" => "modern-store-dark-green",
            "theme_name" => "Modern Green Dark",
            "theme_description" => "WhatsApp Store",
            "theme_thumbnail" => "modern-store-dark-green.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111081",
            "theme_code" => "modern-store-dark-yellow",
            "theme_name" => "Modern Yellow Dark",
            "theme_description" => "WhatsApp Store",
            "theme_thumbnail" => "modern-store-dark-yellow.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111082",
            "theme_code" => "modern-store-dark-red",
            "theme_name" => "Modern Red Dark",
            "theme_description" => "WhatsApp Store",
            "theme_thumbnail" => "modern-store-dark-red.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111083",
            "theme_code" => "modern-store-dark-purple",
            "theme_name" => "Modern Purple Dark",
            "theme_description" => "WhatsApp Store",
            "theme_thumbnail" => "modern-store-dark-purple.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111084",
            "theme_code" => "modern-store-dark-pink",
            "theme_name" => "Modern Pink Dark",
            "theme_description" => "WhatsApp Store",
            "theme_thumbnail" => "modern-store-dark-pink.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111085",
            "theme_code" => "modern-store-dark-gray",
            "theme_name" => "Modern Gray Dark",
            "theme_description" => "WhatsApp Store",
            "theme_thumbnail" => "modern-store-dark-gray.png",
            "theme_price" => "Free"
        ]);

        // Template 1
        DB::table('themes')->insert([
            "theme_id" => "588969110990",
            "theme_code" => "template-1-blue",
            "theme_name" => "Basic Blue", 
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-1-blue.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969110991",
            "theme_code" => "template-1-indigo",
            "theme_name" => "Basic Indigo",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-1-indigo.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969110992",
            "theme_code" => "template-1-green",
            "theme_name" => "Basic Green",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-1-green.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969110993",
            "theme_code" => "template-1-yellow",
            "theme_name" => "Basic Yellow",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-1-yellow.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969110994",
            "theme_code" => "template-1-red",
            "theme_name" => "Basic Red",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-1-red.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969110995",
            "theme_code" => "template-1-purple",
            "theme_name" => "Basic Purple",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-1-purple.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969110996",
            "theme_code" => "template-1-pink",
            "theme_name" => "Basic Pink",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-1-pink.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969110997",
            "theme_code" => "template-1-gray",
            "theme_name" => "Basic Gray",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-1-gray.png",
            "theme_price" => "Free"
        ]);




        // Template 2
        DB::table('themes')->insert([
            "theme_id" => "588969110998",
            "theme_code" => "template-2-blue",
            "theme_name" => "Flat Blue",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-2-blue.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969110999",
            "theme_code" => "template-2-indigo",
            "theme_name" => "Flat Indigo",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-2-indigo.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111000",
            "theme_code" => "template-2-green",
            "theme_name" => "Flat Green",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-2-green.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111001",
            "theme_code" => "template-2-yellow",
            "theme_name" => "Flat Yellow",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-2-yellow.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111002",
            "theme_code" => "template-2-red",
            "theme_name" => "Flat Red",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-2-red.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111003",
            "theme_code" => "template-2-purple",
            "theme_name" => "Flat Purple",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-2-purple.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111004",
            "theme_code" => "template-2-pink",
            "theme_name" => "Flat Pink",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-2-pink.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111005",
            "theme_code" => "template-2-gray",
            "theme_name" => "Flat Gray",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-2-gray.png",
            "theme_price" => "Free"
        ]);




        // Template 3
        DB::table('themes')->insert([
            "theme_id" => "588969111006",
            "theme_code" => "template-3-blue",
            "theme_name" => "Retro Blue",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-3-blue.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111007",
            "theme_code" => "template-3-indigo",
            "theme_name" => "Retro Indigo",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-3-indigo.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111008",
            "theme_code" => "template-3-green",
            "theme_name" => "Retro Green",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-3-green.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111009",
            "theme_code" => "template-3-yellow",
            "theme_name" => "Retro Yellow",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-3-yellow.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111010",
            "theme_code" => "template-3-red",
            "theme_name" => "Retro Red",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-3-red.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111011",
            "theme_code" => "template-3-purple",
            "theme_name" => "Retro Purple",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-3-purple.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111012",
            "theme_code" => "template-3-pink",
            "theme_name" => "Retro Pink",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-3-pink.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111013",
            "theme_code" => "template-3-gray",
            "theme_name" => "Retro Gray",
            "theme_description" => "vCard",
            "theme_thumbnail" => "template-3-gray.png",
            "theme_price" => "Free"
        ]);

        // Personal
        DB::table('themes')->insert([
            "theme_id" => "588969111014",
            "theme_code" => "personal-blue",
            "theme_name" => "Personal Blue",
            "theme_description" => "vCard",
            "theme_thumbnail" => "personal-blue.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111015",
            "theme_code" => "personal-indigo",
            "theme_name" => "Personal Indigo",
            "theme_description" => "vCard",
            "theme_thumbnail" => "personal-indigo.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111016",
            "theme_code" => "personal-green",
            "theme_name" => "Personal Green",
            "theme_description" => "vCard",
            "theme_thumbnail" => "personal-green.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111017",
            "theme_code" => "personal-yellow",
            "theme_name" => "Personal Yellow",
            "theme_description" => "vCard",
            "theme_thumbnail" => "personal-yellow.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111018",
            "theme_code" => "personal-red",
            "theme_name" => "Personal Red",
            "theme_description" => "vCard",
            "theme_thumbnail" => "personal-red.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111019",
            "theme_code" => "personal-purple",
            "theme_name" => "Personal Purple",
            "theme_description" => "vCard",
            "theme_thumbnail" => "personal-purple.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111020",
            "theme_code" => "personal-pink",
            "theme_name" => "Personal Pink",
            "theme_description" => "vCard",
            "theme_thumbnail" => "personal-pink.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111021",
            "theme_code" => "personal-gray",
            "theme_name" => "Personal Gray",
            "theme_description" => "vCard",
            "theme_thumbnail" => "personal-gray.png",
            "theme_price" => "Free"
        ]);



        // Modern (Light)
        DB::table('themes')->insert([
            "theme_id" => "588969111022",
            "theme_code" => "modern-vcard-light-blue",
            "theme_name" => "Modern Blue Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "modern-light-blue.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111023",
            "theme_code" => "modern-vcard-light-indigo",
            "theme_name" => "Modern Indigo Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "modern-light-indigo.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111024",
            "theme_code" => "modern-vcard-light-green",
            "theme_name" => "Modern Green Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "modern-light-green.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111025",
            "theme_code" => "modern-vcard-light-yellow",
            "theme_name" => "Modern Yellow Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "modern-light-yellow.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111026",
            "theme_code" => "modern-vcard-light-red",
            "theme_name" => "Modern Red Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "modern-light-red.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111027",
            "theme_code" => "modern-vcard-light-purple",
            "theme_name" => "Modern Purple Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "modern-light-purple.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111028",
            "theme_code" => "modern-vcard-light-pink",
            "theme_name" => "Modern Pink Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "modern-light-pink.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111029",
            "theme_code" => "modern-vcard-light-gray",
            "theme_name" => "Modern Gray Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "modern-light-gray.png",
            "theme_price" => "Free"
        ]);



        // Modern (Dark)
        DB::table('themes')->insert([
            "theme_id" => "588969111030",
            "theme_code" => "modern-vcard-dark-blue",
            "theme_name" => "Modern Blue Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "modern-dark-blue.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111031",
            "theme_code" => "modern-vcard-dark-indigo",
            "theme_name" => "Modern Indigo Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "modern-dark-indigo.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111032",
            "theme_code" => "modern-vcard-dark-green",
            "theme_name" => "Modern Green Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "modern-dark-green.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111033",
            "theme_code" => "modern-vcard-dark-yellow",
            "theme_name" => "Modern Yellow Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "modern-dark-yellow.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111034",
            "theme_code" => "modern-vcard-dark-red",
            "theme_name" => "Modern Red Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "modern-dark-red.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111035",
            "theme_code" => "modern-vcard-dark-purple",
            "theme_name" => "Modern Purple Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "modern-dark-purple.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111036",
            "theme_code" => "modern-vcard-dark-pink",
            "theme_name" => "Modern Pink Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "modern-dark-pink.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111037",
            "theme_code" => "modern-vcard-dark-gray",
            "theme_name" => "Modern Gray Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "modern-dark-gray.png",
            "theme_price" => "Free"
        ]);




        // Classic Light
        DB::table('themes')->insert([
            "theme_id" => "588969111038",
            "theme_code" => "classic-light-blue",
            "theme_name" => "Classic Blue Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "classic-light-blue.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111039",
            "theme_code" => "classic-light-indigo",
            "theme_name" => "Classic Indigo Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "classic-light-indigo.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111040",
            "theme_code" => "classic-light-green",
            "theme_name" => "Classic Green Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "classic-light-green.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111041",
            "theme_code" => "classic-light-yellow",
            "theme_name" => "Classic Yellow Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "classic-light-yellow.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111042",
            "theme_code" => "classic-light-red",
            "theme_name" => "Classic Red Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "classic-light-red.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111043",
            "theme_code" => "classic-light-purple",
            "theme_name" => "Classic Purple Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "classic-light-purple.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111044",
            "theme_code" => "classic-light-pink",
            "theme_name" => "Classic Pink Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "classic-light-pink.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111045",
            "theme_code" => "classic-light-gray",
            "theme_name" => "Classic Gray Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "classic-light-gray.png",
            "theme_price" => "Free"
        ]);



        // Classic Dark
        DB::table('themes')->insert([
            "theme_id" => "588969111046",
            "theme_code" => "classic-dark-blue",
            "theme_name" => "Classic Blue Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "classic-dark-blue.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111047",
            "theme_code" => "classic-dark-indigo",
            "theme_name" => "Classic Indigo Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "classic-dark-indigo.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111048",
            "theme_code" => "classic-dark-green",
            "theme_name" => "Classic Green Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "classic-dark-green.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111049",
            "theme_code" => "classic-dark-yellow",
            "theme_name" => "Classic Yellow Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "classic-dark-yellow.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111050",
            "theme_code" => "classic-dark-red",
            "theme_name" => "Classic Red Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "classic-dark-red.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111051",
            "theme_code" => "classic-dark-purple",
            "theme_name" => "Classic Purple Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "classic-dark-purple.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111052",
            "theme_code" => "classic-dark-pink",
            "theme_name" => "Classic Pink Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "classic-dark-pink.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111053",
            "theme_code" => "classic-dark-gray",
            "theme_name" => "Classic Gray Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "classic-dark-gray.png",
            "theme_price" => "Free"
        ]);



        // Metro Light
        DB::table('themes')->insert([
            "theme_id" => "588969111054",
            "theme_code" => "metro-light-blue",
            "theme_name" => "Metro Blue Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "metro-light-blue.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111055",
            "theme_code" => "metro-light-indigo",
            "theme_name" => "Metro Indigo Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "metro-light-indigo.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111056",
            "theme_code" => "metro-light-green",
            "theme_name" => "Metro Green Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "metro-light-green.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111057",
            "theme_code" => "metro-light-yellow",
            "theme_name" => "Metro Yellow Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "metro-light-yellow.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111058",
            "theme_code" => "metro-light-red",
            "theme_name" => "Metro Red Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "metro-light-red.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111059",
            "theme_code" => "metro-light-purple",
            "theme_name" => "Metro Purple Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "metro-light-purple.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111060",
            "theme_code" => "metro-light-pink",
            "theme_name" => "Metro Pink Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "metro-light-pink.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111061",
            "theme_code" => "metro-light-gray",
            "theme_name" => "Metro Gray Light",
            "theme_description" => "vCard",
            "theme_thumbnail" => "metro-light-gray.png",
            "theme_price" => "Free"
        ]);




        // Metro Dark
        DB::table('themes')->insert([
            "theme_id" => "588969111062",
            "theme_code" => "metro-dark-blue",
            "theme_name" => "Metro Blue Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "metro-dark-blue.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111063",
            "theme_code" => "metro-dark-indigo",
            "theme_name" => "Metro Indigo Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "metro-dark-indigo.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111064",
            "theme_code" => "metro-dark-green",
            "theme_name" => "Metro Green Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "metro-dark-green.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111065",
            "theme_code" => "metro-dark-yellow",
            "theme_name" => "Metro Yellow Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "metro-dark-yellow.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111066",
            "theme_code" => "metro-dark-red",
            "theme_name" => "Metro Red Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "metro-dark-red.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111067",
            "theme_code" => "metro-dark-purple",
            "theme_name" => "Metro Purple Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "metro-dark-purple.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111068",
            "theme_code" => "metro-dark-pink",
            "theme_name" => "Metro Pink Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "metro-dark-pink.png",
            "theme_price" => "Free"
        ]);

        DB::table('themes')->insert([
            "theme_id" => "588969111069",
            "theme_code" => "metro-dark-gray",
            "theme_name" => "Metro Gray Dark",
            "theme_description" => "vCard",
            "theme_thumbnail" => "metro-dark-gray.png",
            "theme_price" => "Free"
        ]);
    }
}
