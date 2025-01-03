<?php

namespace App\Http\Controllers;

use Exception;
use App\Setting;
use App\Visitor;
use App\Category;
use Carbon\Carbon;
use App\ContactForm;
use App\Testimonial;
use App\BusinessCard;
use App\StoreProduct;
use App\BusinessField;
use Razorpay\Api\Card;
use App\Mail\EnquiryForm;
use Nette\Utils\DateTime;
use App\BookedAppointment;
use Jenssegers\Agent\Agent;
use App\CardAppointmentTime;
use Illuminate\Http\Request;
use JeroenDesloovere\VCard\VCard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Artesaos\SEOTools\Facades\OpenGraph;
use Illuminate\Support\Facades\Response;

class ProfileController extends Controller
{
    // View Card Profile
    public function profile(Request $request, $id)
    {
        $card_details = DB::table('business_cards')->where('card_url', $id)->where('card_status', 'activated')->first();

        $currentUser = 0;

        // Check storage folder
        if (!File::isDirectory('storage')) {
            Artisan::call('storage:link');
        }

        if (isset($card_details)) {
            $currentUser = DB::table('users')->where('user_id', $card_details->user_id)->where('status', 1)->whereDate('plan_validity', '>=', Carbon::now())->count();
        }

        if ($currentUser == 1) {

            // Check whatsapp number exists89
            $whatsAppNumberExists = BusinessField::where('card_id', $card_details->card_id)->where('type', 'wa')->exists();

            // Save visitor
            $clientIP = \Request::getClientIp(true);

            $agent = new Agent();
            $userAgent = $request->header('user_agent');
            $agent->setUserAgent($userAgent);

            // Device
            $device = $agent->device();
            if ($device == "" || $device == "0") {
                $device = "Others";
            }

            // Language
            $language = "en";
            if ($agent->languages()) {
                $language = $agent->languages()[0];
            }

            $visitor = new Visitor();
            $visitor->card_id = $card_details->card_url;
            $visitor->type = $card_details->card_type;
            $visitor->ip_address = $clientIP;
            $visitor->platform = $agent->platform();
            $visitor->device = $agent->device();
            $visitor->language = $language;
            $visitor->user_agent = $userAgent;
            $visitor->save();

            if (isset($card_details)) {
                if ($card_details->card_type == "store") {
                    $enquiry_button = '#';

                    $business_card_details = DB::table('business_cards')->where('business_cards.card_id', $card_details->card_id)
                        ->join('users', 'business_cards.user_id', '=', 'users.user_id')
                        ->join('themes', 'business_cards.theme_id', '=', 'themes.theme_id')
                        ->select('business_cards.*', 'users.plan_details', 'themes.theme_code')
                        ->first();

                    if ($business_card_details) {

                        $products = StoreProduct::join('categories', 'store_products.category_id', '=', 'categories.category_id')
                            ->where('store_products.card_id', $card_details->card_id)
                            ->where('categories.user_id', $business_card_details->user_id)
                            ->where('store_products.product_status', 'instock')
                            ->where('categories.status', 1);

                        $products = $products->orderBy('store_products.id', 'desc');

                        if ($request->has('category')) {
                            $products->where('category_name', ucfirst($request->category));
                        }

                        $products = $products->paginate(12);

                        // Get categories
                        $getCategories = DB::table('store_products')->select('category_id')->groupBy('category_id')->where('card_id', $card_details->card_id)->where('user_id', $business_card_details->user_id);
                        $categories = Category::whereIn('category_id', $getCategories)->get();

                        $settings = Setting::where('status', 1)->first();
                        $config = DB::table('config')->get();

                        SEOTools::setTitle($business_card_details->title);
                        SEOTools::setDescription($business_card_details->sub_title);
                        SEOTools::addImages([url($business_card_details->profile)]);

                        SEOMeta::setTitle($business_card_details->title);
                        SEOMeta::setDescription($business_card_details->sub_title);
                        SEOMeta::addMeta('article:section', $business_card_details->title, 'property');
                        SEOMeta::addKeyword(["'" . $business_card_details->title . "'", "'" . $business_card_details->title . " vcard online'"]);

                        OpenGraph::setTitle($business_card_details->title);
                        OpenGraph::setDescription($business_card_details->sub_title);
                        OpenGraph::setUrl(url($business_card_details->card_url));
                        OpenGraph::addImage([url($business_card_details->profile)]);

                        JsonLd::setTitle($business_card_details->title);
                        JsonLd::setDescription($business_card_details->sub_title);
                        JsonLd::addImage([url($business_card_details->profile)]);

                        // PWA
                        $icons = [
                            '512x512' => [
                                'path' => url($business_card_details->profile),
                                'purpose' => 'any'
                            ]
                        ];

                        $splash = [
                            '640x1136' => url($business_card_details->profile),
                            '750x1334' => url($business_card_details->profile),
                            '828x1792' => url($business_card_details->profile),
                            '1125x2436' => url($business_card_details->profile),
                            '1242x2208' => url($business_card_details->profile),
                            '1242x2688' => url($business_card_details->profile),
                            '1536x2048' => url($business_card_details->profile),
                            '1668x2224' => url($business_card_details->profile),
                            '1668x2388' => url($business_card_details->profile),
                            '2048x2732' => url($business_card_details->profile),
                        ];

                        $shortcuts = [
                            [
                                'name' => $business_card_details->title,
                                'description' => $business_card_details->sub_title,
                                'url' => asset($business_card_details->card_url),
                                'icons' => [
                                    "src" => url($business_card_details->profile),
                                    "purpose" => "any"
                                ]
                            ]
                        ];

                        $fill = [
                            "name" => $business_card_details->title,
                            "short_name" => $business_card_details->title,
                            "start_url" => asset($business_card_details->card_url),
                            "theme_color" => "#ffffff",
                            "icons" => $icons,
                            "splash" => $splash,
                            "shortcuts" => $shortcuts,
                        ];

                        $out = $this->generateNew($fill);

                        Storage::disk('public')->put("manifest/" . $business_card_details->card_id . '.json', json_encode($out));

                        $manifest = url("storage/manifest/" . $business_card_details->card_id . '.json');

                        $plan_details = json_decode($business_card_details->plan_details, true);
                        $store_details = json_decode($business_card_details->description, true);

                        if ($store_details['whatsapp_no'] != null) {
                            $enquiry_button = $store_details['whatsapp_no'];
                        }

                        $whatsapp_msg = $store_details['whatsapp_msg'];
                        $currency = $store_details['currency'];

                        $url = URL::to('/') . "/" . strtolower(preg_replace('/\s+/', '-', $card_details->card_url));
                        $business_name = $card_details->title;
                        $profile = URL::to('/') . "/" . $business_card_details->cover;

                        $shareContent = $config[30]->config_value;
                        $shareContent = str_replace("{ business_name }", $business_name, $shareContent);
                        $shareContent = str_replace("{ business_url }", $url, $shareContent);
                        $shareContent = str_replace("{ appName }", $config[0]->config_value, $shareContent);

                        // If branding enabled, then show app name.
                        if ($plan_details['hide_branding'] == "1") {
                            $shareContent = str_replace("{ appName }", $business_name, $shareContent);
                        } else {
                            $shareContent = str_replace("{ appName }", $config[0]->config_value, $shareContent);
                        }

                        $url = urlencode($url);
                        $shareContent = urlencode($shareContent);

                        // Session::put('locale', strtolower($business_card_details->card_lang));
                        app()->setLocale(Session::get('locale'));

                        $qr_url = "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=" . $url;

                        $shareComponent['facebook'] = "https://www.facebook.com/sharer/sharer.php?u=$url&quote=$shareContent";
                        $shareComponent['twitter'] = "https://twitter.com/intent/tweet?text=$shareContent";
                        $shareComponent['linkedin'] = "https://www.linkedin.com/shareArticle?mini=true&url=$url";
                        $shareComponent['telegram'] = "https://telegram.me/share/url?text=$shareContent&url=$url";
                        $shareComponent['whatsapp'] = "https://api.whatsapp.com/send/?phone&text=$shareContent";

                        $datas = compact('card_details', 'plan_details', 'store_details', 'categories', 'business_card_details', 'products', 'settings', 'shareComponent', 'shareContent', 'config', 'enquiry_button', 'whatsapp_msg', 'currency', 'manifest', 'whatsAppNumberExists');
                        return view('templates.' . $business_card_details->theme_code, $datas);
                    } else {
                        return redirect()->route('user.edit.card', $id)->with('failed', trans('Please fill out the basic business details.'));
                    }
                } else {
                    $enquiry_button = "#";

                    $business_card_details = DB::table('business_cards')->where('business_cards.card_id', $card_details->card_id)
                        ->join('users', 'business_cards.user_id', '=', 'users.user_id')
                        ->join('themes', 'business_cards.theme_id', '=', 'themes.theme_id')
                        ->select('business_cards.*', 'users.plan_details', 'themes.theme_code')
                        ->first();

                    if ($business_card_details) {

                        $feature_details = DB::table('business_fields')->where('card_id', $card_details->card_id)->orderBy('position', 'asc')->get();
                        $service_details = DB::table('services')->where('card_id', $card_details->card_id)->orderBy('id', 'asc')->get();
                        $product_details = DB::table('vcard_products')->where('card_id', $card_details->card_id)->orderBy('id', 'asc')->get();
                        $galleries_details = DB::table('galleries')->where('card_id', $card_details->card_id)->orderBy('id', 'asc')->get();
                        $testimonials = Testimonial::where('card_id', $card_details->card_id)->orderBy('id', 'asc')->get();
                        $payment_details = DB::table('payments')->where('card_id', $card_details->card_id)->get();
                        $business_hours = DB::table('business_hours')->where('card_id', $card_details->card_id)->first();
                        $make_enquiry = DB::table('business_fields')->where('card_id', $card_details->card_id)->where('type', 'wa')->first();
                        $iframes = DB::table('business_fields')->where('type', 'iframe')->where('card_id', $card_details->card_id)->orderBy('id', 'asc')->get();
                        $customTexts = DB::table('business_fields')->where('type', 'text')->where('card_id', $card_details->card_id)->orderBy('id', 'asc')->get();

                        // Appointment slots for the card
                        $appointmentSlots = CardAppointmentTime::where('card_id', $card_details->card_id)->orderBy('id', 'asc')->get();

                        // Initialize the time slots array
                        $appointment_slots = [
                            'monday' => [],
                            'tuesday' => [],
                            'wednesday' => [],
                            'thursday' => [],
                            'friday' => [],
                            'saturday' => [],
                            'sunday' => [],
                        ];

                        // Iterate through the appointment slots and categorize them by day
                        foreach ($appointmentSlots as $slot) {
                            // Assuming your `CardAppointmentTime` model has a `day` attribute and a `time` attribute
                            $day = strtolower($slot->day); // Convert to lowercase to match array keys
                            $time = $slot->time_slots; // Assuming this contains the time range string like "16:00 - 17:00"

                            // Check if the day exists in the time_slots array
                            if (array_key_exists($day, $appointment_slots)) {
                                $appointment_slots[$day][] = $time; // Add the time to the appropriate day
                                // Get price
                                $appointment_slots[$day][] = $slot->price;
                            }
                        }

                        $appointment_slots = json_encode($appointment_slots); // Convert the array to JSON

                        if ($make_enquiry != null) {
                            $enquiry_button = $make_enquiry->content;
                        }

                        $settings = Setting::where('status', 1)->first();
                        $config = DB::table('config')->get();

                        SEOTools::setTitle($business_card_details->title);
                        SEOTools::setDescription($business_card_details->sub_title);
                        SEOTools::addImages([url($business_card_details->profile)]);

                        SEOMeta::setTitle($business_card_details->title);
                        SEOMeta::setDescription($business_card_details->sub_title);
                        SEOMeta::addMeta('article:section', $business_card_details->title, 'property');
                        SEOMeta::addKeyword(["'" . $business_card_details->title . "'", "'" . $business_card_details->title . " vcard online'"]);

                        OpenGraph::setTitle($business_card_details->title);
                        OpenGraph::setDescription($business_card_details->sub_title);
                        OpenGraph::setUrl(url($business_card_details->card_url));
                        OpenGraph::addImage([url($business_card_details->profile)]);

                        JsonLd::setTitle($business_card_details->title);
                        JsonLd::setDescription($business_card_details->sub_title);
                        JsonLd::addImage([url($business_card_details->profile)]);

                        // PWA
                        $icons = [
                            '512x512' => [
                                'path' => url($business_card_details->profile),
                                'purpose' => 'any'
                            ]
                        ];

                        $splash = [
                            '640x1136' => url($business_card_details->profile),
                            '750x1334' => url($business_card_details->profile),
                            '828x1792' => url($business_card_details->profile),
                            '1125x2436' => url($business_card_details->profile),
                            '1242x2208' => url($business_card_details->profile),
                            '1242x2688' => url($business_card_details->profile),
                            '1536x2048' => url($business_card_details->profile),
                            '1668x2224' => url($business_card_details->profile),
                            '1668x2388' => url($business_card_details->profile),
                            '2048x2732' => url($business_card_details->profile),
                        ];

                        $shortcuts = [
                            [
                                'name' => $business_card_details->title,
                                'description' => $business_card_details->sub_title,
                                'url' => asset($business_card_details->card_url),
                                'icons' => [
                                    "src" => url($business_card_details->profile),
                                    "purpose" => "any"
                                ]
                            ]
                        ];

                        $fill = [
                            "name" => $business_card_details->title,
                            "short_name" => $business_card_details->title,
                            "start_url" => asset($business_card_details->card_url),
                            "theme_color" => "#ffffff",
                            "icons" => $icons,
                            "splash" => $splash,
                            "shortcuts" => $shortcuts,
                        ];

                        $out = $this->generateNew($fill);

                        Storage::disk('public')->put("manifest/" . $business_card_details->card_id . '.json', json_encode($out));

                        $manifest = url("storage/manifest/" . $business_card_details->card_id . '.json');

                        $plan_details = json_decode($business_card_details->plan_details, true);

                        $url = URL::to('/') . "/" . strtolower(preg_replace('/\s+/', '-', $card_details->card_url));
                        $business_name = $card_details->title;
                        $profile = URL::to('/') . "/" . $business_card_details->cover;

                        $shareContent = $config[30]->config_value;
                        $shareContent = str_replace("{ business_name }", $business_name, $shareContent);
                        $shareContent = str_replace("{ business_url }", $url, $shareContent);

                        // If branding enabled, then show app name.
                        if ($plan_details['hide_branding'] == "1") {
                            $shareContent = str_replace("{ appName }", $business_name, $shareContent);
                        } else {
                            $shareContent = str_replace("{ appName }", $config[0]->config_value, $shareContent);
                        }

                        $url = urlencode($url);
                        $shareContent = urlencode($shareContent);

                        // Session::put('locale', strtolower($business_card_details->card_lang));
                        app()->setLocale(Session::get('locale'));

                        $qr_url = "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=" . $url;

                        $shareComponent['facebook'] = "https://www.facebook.com/sharer/sharer.php?u=$url&quote=$shareContent";
                        $shareComponent['twitter'] = "https://twitter.com/intent/tweet?text=$shareContent";
                        $shareComponent['linkedin'] = "https://www.linkedin.com/shareArticle?mini=true&url=$url";
                        $shareComponent['telegram'] = "https://telegram.me/share/url?text=$shareContent&url=$url";
                        $shareComponent['whatsapp'] = "https://api.whatsapp.com/send/?phone&text=$shareContent";

                        // Datas
                        $datas = compact('card_details', 'plan_details', 'business_card_details', 'feature_details', 'service_details', 'product_details', 'galleries_details', 'testimonials', 'payment_details', 'appointment_slots', 'business_hours', 'settings', 'shareComponent', 'shareContent', 'config', 'enquiry_button', 'iframes', 'customTexts', 'manifest', 'whatsAppNumberExists');

                        return view('templates.' . $business_card_details->theme_code, $datas);
                    } else {
                        return redirect()->route('user.edit.card', $id)->with('failed', trans('Please fill out the basic business details.'));
                    }
                }
            } else {
                http_response_code(404);
                return view('errors.404');
            }
        } else {
            return view('errors.404');
        }
    }

    // Check password
    public function checkPwd(Request $request, $id)
    {
        $business_card = BusinessCard::where('card_id', $id)->first();

        if ($business_card) {
            // Check password
            if ($business_card->password == $request->password) {
                Session::put('password_protected', true);

                return redirect()->route('profile', $business_card->card_url);
            } else {

                Session::flash('message', trans('Incorrect vcard password.'));
                return redirect()->route('profile', $business_card->card_url);
            }
        } else {
            return redirect()->route('profile', $business_card->card_url);
        }
    }

    public function downloadVcard(Request $request, $id)
    {
        $business_card = BusinessCard::where('card_id', $id)->first();

        if ($business_card == null) {
            return view('errors.404');
        } else {
            $business_card_details = DB::table('business_cards')->where('business_cards.card_id', $id)
                ->join('users', 'business_cards.user_id', '=', 'users.user_id')
                ->select('business_cards.*')
                ->first();
            $features = BusinessField::where('card_id', $id)->get();

            $vcard_url = url()->previous();

            // define vcard
            $vcard = new VCard();

            // define variables
            $lastname = '';
            $firstname = $business_card_details->title;
            $additional = '';
            $prefix = '';
            $suffix = '';
            $email = '';
            $tel = '';
            $whatsapp = '';


            // add personal data
            $vcard->addName($lastname, $firstname, $additional, $prefix, $suffix);

            foreach ($features as $key => $value) {
                if ($value->type == "email") {
                    $vcard->addEmail($value->content);
                }
                if ($value->type == "tel") {
                    $vcard->addPhoneNumber($value->content, 'WORK');
                }
                if ($value->type == "wa") {
                    $vcard->addPhoneNumber($value->content, 'WHATSAPP');
                }
                if (
                    $value->type == "facebook" || $value->type == "instagram" || $value->type == "x-twitter" || $value->type == "linkedin"
                    || $value->type == "pinterest" || $value->type == "reddit" || $value->type == "tiktok" || $value->type == "threads"
                    || $value->type == "snapchat" || $value->type == "wechat" || $value->type == "telegram" || $value->type == "tumblr"
                    || $value->type == "qq" || $value->type == "discord" || $value->type == "quora" || $value->type == "url"
                ) {
                    $vcard->addURL($value->content, $value->type);
                }
                if ($value->type == "address") {
                    $vcard->addAddress($value->content);
                }
            }

            if ($business_card_details->profile == null) {
                $image = "";
            } else {
                $image = str_replace(' ', '%20', public_path($business_card_details->profile));
            }

            // add work data
            $vcard->addJobtitle($business_card_details->sub_title);
            // $vcard->addPhoto($image);
            $vcard->addURL($vcard_url);

            return Response::make($vcard->getOutput(), 200, $vcard->getHeaders(true));
        }
    }

    // Sent email form vcard
    public function sentEnquiry(Request $request)
    {
        $business_card = BusinessCard::where('card_id', $request->card_id)->first();

        if ($business_card == null) {
            return view('errors.404');
        } else {
            $business_card_details = DB::table('business_cards')->where('business_cards.card_id', $request->card_id)
                ->join('users', 'business_cards.user_id', '=', 'users.user_id')
                ->select('business_cards.*', 'users.*')
                ->first();

            // Save enquiry
            $contactForm = new ContactForm();
            $contactForm->contact_form_id = rand(1, 9999999999999999);
            $contactForm->card_id = $request->card_id;
            $contactForm->user_id = $business_card_details->user_id;
            $contactForm->name = $request->name;
            $contactForm->email = $request->email;
            $contactForm->phone = $request->phone;
            $contactForm->message = $request->message;
            $contactForm->save();

            // The email sending is done using the to method on the Mail facade
            try {
                // Contact Details Array
                $enquiryDetails = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->phone,
                    'message' => $request->message
                ];

                // Send mail
                Mail::to($business_card_details->enquiry_email)->send(new EnquiryForm($enquiryDetails));

                Session::flash('message', trans('Email sent successfully.'));
                return redirect()->back();
            } catch (Exception $e) {
                Session::flash('message', trans('Email service not available.'));
                return redirect()->back();
            }
        }

        Session::flash('message', trans('Email sent successfully.'));
        return redirect()->back();
    }

    // Generate manifest json
    public function generateNew($fill)
    {
        $basicManifest = [
            'name' => $fill['name'],
            'short_name' => $fill['short_name'],
            'start_url' => $fill['start_url'],
            'background_color' => '#ffffff',
            'theme_color' => '#000000',
            'display' => 'standalone',
            'status_bar' => "black",
            'splash' => $fill['splash']
        ];

        foreach ($fill['icons'] as $size => $file) {
            $fileInfo = pathinfo($file['path']);
            $basicManifest['icons'][] = [
                'src' => $file['path'],
                'type' => 'image/' . $fileInfo['extension'],
                'sizes' => $size,
                'purpose' => $file['purpose']
            ];
        }

        if ($fill['shortcuts']) {
            foreach ($fill['shortcuts'] as $shortcut) {

                if (array_key_exists("icons", $shortcut)) {
                    $fileInfo = pathinfo($shortcut['icons']['src']);
                    $icon = [
                        'src' => $shortcut['icons']['src'],
                        'type' => 'image/' . $fileInfo['extension'],
                        'sizes' => $size,
                        'purpose' => $shortcut['icons']['purpose']
                    ];
                } else {
                    $icon = [];
                }

                $basicManifest['shortcuts'][] = [
                    'name' => trans($shortcut['name']),
                    'description' => trans($shortcut['description']),
                    'url' => $shortcut['url'],
                    'icons' => [
                        $icon
                    ]
                ];
            }
        }
        return $basicManifest;
    }

    public function setLocale(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'locale' => 'required|string|max:2',
        ]);

        // Set the session locale
        Session::put('locale', strtolower($request->locale));
        app()->setLocale(Session::get('locale'));

        // Optionally return a response
        return response()->json(['message' => 'Locale updated successfully!']);
    }
}
