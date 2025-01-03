<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class GatewayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gateways')->insert([
            "payment_gateway_id" => "60964401751ab",
            "payment_gateway_logo"  => "img/payment-method/paypal.png",
            "payment_gateway_name"  => "Paypal",
            "display_name"  => "Paypal",
            "client_id"  => "5",
            "secret_key"  => "6",
            "is_status"  => "enabled"
        ]);

        DB::table('gateways')->insert([
            "payment_gateway_id" => "60964410731d9",
            "payment_gateway_logo"  => "img/payment-method/razorpay.png",
            "payment_gateway_name"  => "Razorpay",
            "display_name"  => "Razorpay",
            "client_id"  => "7",
            "secret_key"  => "8",
            "is_status"  => "enabled"
        ]);

        DB::table('gateways')->insert([
            "payment_gateway_id" => "60964410732t9",
            "payment_gateway_logo"  => "img/payment-method/stripe.png",
            "payment_gateway_name"  => "Stripe",
            "display_name"  => "Stripe",
            "client_id"  => "10",
            "secret_key"  => "11",
            "is_status"  => "enabled"
        ]);

        DB::table('gateways')->insert([
            "payment_gateway_id" => "60964410736592",
            "payment_gateway_logo"  => "img/payment-method/paystack.png",
            "payment_gateway_name"  => "Paystack",
            "display_name"  => "Paystack",
            "client_id"  => "14",
            "secret_key"  => "15",
            "is_status"  => "enabled"
        ]);

        DB::table('gateways')->insert([
            "payment_gateway_id" => "6096441071589632",
            "payment_gateway_logo"  => "img/payment-method/mollie.webp",
            "payment_gateway_name"  => "Mollie",
            "display_name"  => "Mollie",
            "client_id"  => "16",
            "secret_key"  => "17",
            "is_status"  => "enabled"
        ]);

        DB::table('gateways')->insert([
            "payment_gateway_id" => "19065566166715",
            "payment_gateway_logo"  => "img/payment-method/phonepe.png",
            "payment_gateway_name"  => "PhonePe",
            "display_name"  => "PhonePe",
            "client_id"  => "18",
            "secret_key"  => "19",
            "is_status"  => "enabled"
        ]);

        DB::table('gateways')->insert([
            "payment_gateway_id" => "776111730465",
            "payment_gateway_logo"  => "img/payment-method/mercado-pago.png",
            "payment_gateway_name"  => "Mercado Pago",
            "display_name"  => "Mercado Pago",
            "client_id"  => "20",
            "secret_key"  => "21",
            "is_status"  => "enabled"
        ]);

        DB::table('gateways')->insert([
            "payment_gateway_id" => "767510608137",
            "payment_gateway_logo"  => "img/payment-method/toyyibpay.png",
            "payment_gateway_name"  => "Toyyibpay",
            "display_name"  => "Toyyibpay",
            "client_id"  => "22",
            "secret_key"  => "23",
            "is_status"  => "enabled"
        ]);

        DB::table('gateways')->insert([
            "payment_gateway_id" => "754201940107",
            "payment_gateway_logo"  => "img/payment-method/flutterwave.png",
            "payment_gateway_name"  => "Flutterwave",
            "display_name"  => "Flutterwave",
            "client_id"  => "24",
            "secret_key"  => "25",
            "is_status"  => "enabled"
        ]);

        DB::table('gateways')->insert([
            "payment_gateway_id" => "659644107y2g5",
            "payment_gateway_logo"  => "img/payment-method/bank-transfer.png",
            "payment_gateway_name"  => "Bank Transfer",
            "display_name"  => "Bank Transfer",
            "client_id"  => "12",
            "secret_key"  => "13",
            "is_status"  => "enabled"
        ]);
    }
}
