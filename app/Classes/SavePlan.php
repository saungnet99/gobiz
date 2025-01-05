<?php

namespace App\Classes;

use App\Plan;
use Illuminate\Support\Facades\Validator;

class SavePlan
{
    public function create($request)
    {
        // Default
        $this->result = 0;

        // Check plan type
        switch ($request->plan_type) {
            case 'VCARD':

                // Validate
                $validator = Validator::make($request->all(), [
                    'plan_type' => 'required',
                    'plan_name' => 'required',
                    'plan_description' => 'required',
                    'plan_price' => 'required',
                    'validity' => 'required',
                    'no_of_vcards' => 'required',
                    'no_of_services' => 'required',
                    'no_of_vcard_products' => 'required',
                    'no_of_links' => 'required',
                    'no_of_payments' => 'required',
                    'no_testimonials' => 'required',
                    'no_of_galleries' => 'required',
                    'business_hours' => 'required',
                    'contact_form' => 'required',
                    'appointment' => 'required',
                    'no_of_enquires' => 'required',
                    'pwa' => 'required'
                ]);

                if ($validator->fails()) {
                    return back()->with('failed', $validator->messages()->all()[0])->withInput();
                }

                // Check
                if ($request->business_hours == "off") {
                    $business_hours = 0;
                } else {
                    $business_hours = 1;
                }

                if ($request->contact_form == "off") {
                    $contact_form = 0;
                } else {
                    $contact_form = 1;
                }

                if ($request->appointment == "off") {
                    $appointment = 0;
                } else {
                    $appointment = 1;
                }

                if ($request->pwa == "off") {
                    $pwa = 0;
                } else {
                    $pwa = 1;
                }

                if ($request->advanced_settings == "off") {
                    $advanced_settings = 0;
                } else {
                    $advanced_settings = 1;
                }

                if ($request->additional_tools == "off") {
                    $additional_tools = 0;
                } else {
                    $additional_tools = 1;
                }
                
                if ($request->personalized_link == "off") {
                    $personalized_link = 0;
                } else {
                    $personalized_link = 1;
                }
        
                if ($request->hide_branding == "off") {
                    $hide_branding = 0;
                } else {
                    $hide_branding = 1;
                }
        
                if ($request->is_private == "off") {
                    $is_private = 0;
                } else {
                    $is_private = 1;
                }
        
                if ($request->free_setup == "off") {
                    $free_setup = 0;
                } else {
                    $free_setup = 1;
                }
        
                if ($request->free_support == "off") {
                    $free_support = 0;
                } else {
                    $free_support = 1;
                }
        
                if ($request->recommended == "off") {
                    $recommended = 0;
                } else {
                    $recommended = 1;
                }

                // Save
                $plan = new Plan;
                $plan->plan_id = uniqid();
                $plan->plan_type = $request->plan_type;
                $plan->plan_name = ucfirst($request->plan_name);
                $plan->plan_description = ucfirst($request->plan_description);
                $plan->recommended = $recommended;
                $plan->plan_price = $request->plan_price;
                $plan->validity = $request->validity;
                $plan->no_of_vcards = $request->no_of_vcards;
                $plan->no_of_services = $request->no_of_services;
                $plan->no_of_vcard_products = $request->no_of_vcard_products;
                $plan->no_of_galleries = $request->no_of_galleries;
                $plan->no_of_links = $request->no_of_links;
                $plan->no_of_payments = $request->no_of_payments;
                $plan->no_testimonials = $request->no_testimonials;
                $plan->business_hours = $business_hours;
                $plan->contact_form = $contact_form;
                $plan->appointment = $appointment;
                $plan->no_of_enquires = $request->no_of_enquires;
                $plan->pwa = $pwa;
                $plan->advanced_settings = $advanced_settings;
                $plan->additional_tools = $additional_tools;
                $plan->personalized_link = $personalized_link;
                $plan->hide_branding = $hide_branding;
                $plan->free_setup = $free_setup;
                $plan->free_support = $free_support;
                $plan->is_private = $is_private;
                $plan->save();

                return $this->result = 1;
                break;

            case 'STORE':

                // Validate
                $validator = Validator::make($request->all(), [
                    'plan_type' => 'required',
                    'plan_name' => 'required',
                    'plan_description' => 'required',
                    'plan_price' => 'required',
                    'validity' => 'required',
                    'no_of_stores' => 'required',
                    'no_of_categories' => 'required',
                    'no_of_store_products' => 'required',
                    'pwa' => 'required'
                ]);

                if ($validator->fails()) {
                    return back()->with('failed', $validator->messages()->all()[0])->withInput();
                }

                // Check
                if ($request->pwa == "off") {
                    $pwa = 0;
                } else {
                    $pwa = 1;
                }

                if ($request->advanced_settings == "off") {
                    $advanced_settings = 0;
                } else {
                    $advanced_settings = 1;
                }

                if ($request->additional_tools == "off") {
                    $additional_tools = 0;
                } else {
                    $additional_tools = 1;
                }

                if ($request->personalized_link == "off") {
                    $personalized_link = 0;
                } else {
                    $personalized_link = 1;
                }
        
                if ($request->hide_branding == "off") {
                    $hide_branding = 0;
                } else {
                    $hide_branding = 1;
                }
        
                if ($request->is_private == "off") {
                    $is_private = 0;
                } else {
                    $is_private = 1;
                }
        
                if ($request->free_setup == "off") {
                    $free_setup = 0;
                } else {
                    $free_setup = 1;
                }
        
                if ($request->free_support == "off") {
                    $free_support = 0;
                } else {
                    $free_support = 1;
                }
        
                if ($request->recommended == "off") {
                    $recommended = 0;
                } else {
                    $recommended = 1;
                }

                // Save
                $plan = new Plan;
                $plan->plan_id = uniqid();
                $plan->plan_type = $request->plan_type;
                $plan->plan_name = ucfirst($request->plan_name);
                $plan->plan_description = ucfirst($request->plan_description);
                $plan->recommended = $recommended;
                $plan->plan_price = $request->plan_price;
                $plan->validity = $request->validity;
                $plan->no_of_stores = $request->no_of_stores;
                $plan->no_of_categories = $request->no_of_categories;
                $plan->no_of_store_products = $request->no_of_store_products;
                $plan->pwa = $pwa;
                $plan->advanced_settings = $advanced_settings;
                $plan->additional_tools = $additional_tools;
                $plan->personalized_link = $personalized_link;
                $plan->hide_branding = $hide_branding;
                $plan->free_setup = $free_setup;
                $plan->free_support = $free_support;
                $plan->is_private = $is_private;
                $plan->save();

                return $this->result = 1;
                break;
            
            default:

                // Validate
                $validator = Validator::make($request->all(), [
                    'plan_type' => 'required',
                    'plan_name' => 'required',
                    'plan_description' => 'required',
                    'plan_price' => 'required',
                    'validity' => 'required',
                    'no_of_vcards' => 'required',
                    'no_of_services' => 'required',
                    'no_of_vcard_products' => 'required',
                    'no_of_links' => 'required',
                    'no_of_payments' => 'required',
                    'no_testimonials' => 'required',
                    'no_of_galleries' => 'required',
                    'business_hours' => 'required',
                    'contact_form' => 'required',
                    'appointment' => 'required',
                    'no_of_enquires' => 'required',
                    'no_of_stores' => 'required',
                    'no_of_categories' => 'required',
                    'no_of_store_products' => 'required',
                    'pwa' => 'required'
                ]);

                if ($validator->fails()) {
                    return back()->with('failed', $validator->messages()->all()[0])->withInput();
                }

                // Check
                if ($request->business_hours == "off") {
                    $business_hours = 0;
                } else {
                    $business_hours = 1;
                }

                if ($request->contact_form == "off") {
                    $contact_form = 0;
                } else {
                    $contact_form = 1;
                }

                if ($request->appointment == "off") {
                    $appointment = 0;
                } else {
                    $appointment = 1;
                }

                if ($request->pwa == "off") {
                    $pwa = 0;
                } else {
                    $pwa = 1;
                }

                if ($request->advanced_settings == "off") {
                    $advanced_settings = 0;
                } else {
                    $advanced_settings = 1;
                }

                if ($request->additional_tools == "off") {
                    $additional_tools = 0;
                } else {
                    $additional_tools = 1;
                }

                if ($request->personalized_link == "off") {
                    $personalized_link = 0;
                } else {
                    $personalized_link = 1;
                }
        
                if ($request->hide_branding == "off") {
                    $hide_branding = 0;
                } else {
                    $hide_branding = 1;
                }
        
                if ($request->is_private == "off") {
                    $is_private = 0;
                } else {
                    $is_private = 1;
                }
        
                if ($request->free_setup == "off") {
                    $free_setup = 0;
                } else {
                    $free_setup = 1;
                }
        
                if ($request->free_support == "off") {
                    $free_support = 0;
                } else {
                    $free_support = 1;
                }
        
                if ($request->recommended == "off") {
                    $recommended = 0;
                } else {
                    $recommended = 1;
                }

                // Save
                $plan = new Plan;
                $plan->plan_id = uniqid();
                $plan->plan_type = $request->plan_type;
                $plan->plan_name = ucfirst($request->plan_name);
                $plan->plan_description = ucfirst($request->plan_description);
                $plan->recommended = $recommended;
                $plan->plan_price = $request->plan_price;
                $plan->validity = $request->validity;
                $plan->no_of_vcards = $request->no_of_vcards;
                $plan->no_of_services = $request->no_of_services;
                $plan->no_of_vcard_products = $request->no_of_vcard_products;
                $plan->no_of_galleries = $request->no_of_galleries;
                $plan->no_of_links = $request->no_of_links;
                $plan->no_testimonials = $request->no_testimonials;
                $plan->no_of_payments = $request->no_of_payments;
                $plan->business_hours = $business_hours;
                $plan->contact_form = $contact_form;
                $plan->appointment = $appointment;
                $plan->no_of_enquires = $request->no_of_enquires;
                $plan->no_of_stores = $request->no_of_stores;
                $plan->no_of_categories = $request->no_of_categories;
                $plan->no_of_store_products = $request->no_of_store_products;
                $plan->pwa = $pwa;
                $plan->advanced_settings = $advanced_settings;
                $plan->additional_tools = $additional_tools;
                $plan->personalized_link = $personalized_link;
                $plan->hide_branding = $hide_branding;
                $plan->free_setup = $free_setup;
                $plan->free_support = $free_support;
                $plan->is_private = $is_private;
                $plan->save();

                return $this->result = 1;
                break;
        }
    }
}
