<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebToolsController;
use App\Http\Controllers\SubdomainController;
use App\Http\Controllers\User\CardController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\MediaController;
use App\Http\Controllers\User\StoreController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\UpdateController;
use App\Http\Controllers\User\BillingController;
use App\Http\Controllers\User\InquiryController;
use App\Http\Controllers\User\PreviewController;
use App\Http\Controllers\User\VisitorController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\SitemapController;
use App\Http\Controllers\User\CategoryController;
use App\Http\Controllers\User\CheckOutController;
use App\Http\Controllers\User\EditCardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Payment\MollieController;
use App\Http\Controllers\Payment\PaypalController;
use App\Http\Controllers\Payment\StripeController;
use App\Http\Controllers\User\DuplicateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\BookAppointmentController;
use App\Http\Controllers\Payment\OfflineController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Payment\PhonepeController;
use App\Http\Controllers\User\AdditionalController;
use App\Http\Controllers\Admin\TaxSettingController;
use App\Http\Controllers\Payment\PaystackController;
use App\Http\Controllers\Payment\RazorpayController;
use App\Http\Controllers\Payment\ToyyibpayController;
use App\Http\Controllers\User\VerificationController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\EmailSettingController;
use App\Http\Controllers\Admin\TransactionsController;
use App\Http\Controllers\User\VerifiedEmailController;
use App\Http\Controllers\Admin\GoogleSettingController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Payment\FlutterwaveController;
use App\Http\Controllers\Payment\MercadoPagoController;
use App\Http\Controllers\Admin\PaymentSettingController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use App\Http\Controllers\Admin\SubdomainSettingController;
use App\Http\Controllers\Admin\BlogController as AdminBlog;
use App\Http\Controllers\User\Vcard\Create\CreateController;
use App\Http\Controllers\User\Vcard\Create\GalleryController;
use App\Http\Controllers\User\Vcard\Create\ProductController;
use App\Http\Controllers\User\Vcard\Create\ServiceController;
use App\Http\Controllers\User\AccountController as userAccount;
use App\Http\Controllers\User\Vcard\Create\SocialLinkController;
use App\Http\Controllers\User\Vcard\Create\AppointmentController;
use App\Http\Controllers\User\Vcard\Create\ContactFormController;
use App\Http\Controllers\User\Vcard\Create\PaymentLinkController;
use App\Http\Controllers\User\Vcard\Create\TestimonialController;
use App\Http\Controllers\User\Vcard\Create\BusinessHourController;
use App\Http\Controllers\User\DashboardController as userDashboard;
use App\Http\Controllers\User\PlanController as UserPlanController;
use App\Http\Controllers\User\Vcard\Create\AdvancedSettingController;
use App\Http\Controllers\User\TransactionsController as userTransactions;
use App\Http\Controllers\User\AppointmentController as BookedAppointmentController;
use App\Http\Controllers\User\Store\Edit\UpdateController as UpdateStoreController;
use App\Http\Controllers\User\Vcard\Edit\GalleryController as EditGalleryController;
use App\Http\Controllers\User\Vcard\Edit\ProductController as EditProductController;
use App\Http\Controllers\User\Vcard\Edit\ServiceController as EditServiceController;
use App\Http\Controllers\User\Store\Create\CreateController as CreateStoreController;
use App\Http\Controllers\User\Vcard\Edit\SocialLinkController as EditSocialLinkController;
use App\Http\Controllers\User\Store\Edit\ProductController as UpdateStoreProductController;
use App\Http\Controllers\User\Vcard\Edit\AppointmentController as EditAppointmentController;
use App\Http\Controllers\User\Vcard\Edit\ContactFormController as EditContactFormController;
use App\Http\Controllers\User\Vcard\Edit\PaymentLinkController as EditPaymentLinkController;
use App\Http\Controllers\User\Vcard\Edit\TestimonialController as EditTestimonialController;
use App\Http\Controllers\User\Store\Create\ProductController as CreateStoreProductController;
use App\Http\Controllers\User\Vcard\Edit\BusinessHourController as EditBusinessHourController;
use App\Http\Controllers\User\Vcard\Edit\AdvancedSettingController as EditAdvancedSettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Installer Middleware
Route::group(['middleware' => 'Installer'], function () {
    // Subdomain
    Route::domain('{cardname}.' . env('MAIN_DOMAIN') ?? env('APP_URL'))->group(function () {
        Route::get('/', [SubdomainController::class, 'subdomainProfile', 'ShareWidget'])->name('subdomain.profile')->middleware('scriptsanitizer');
    });

    Route::group(['middleware' => 'frame.destroyer'], function () {
        Route::get('/', [HomeController::class, 'index'])->name('home-locale');

        Auth::routes(['verify' => true]);

        // Pages
        Route::get('faq', [HomeController::class, 'faq'])->name('faq');
        Route::get('about-us', [HomeController::class, 'about'])->name('about');
        Route::get('contact-us', [HomeController::class, 'contact'])->name('contact');
        Route::get('support', [HomeController::class, 'support'])->name('support');
        Route::get('privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy.policy');
        Route::get('terms-and-conditions', [HomeController::class, 'termsAndConditions'])->name('terms.and.conditions');
        Route::get('refund-policy', [HomeController::class, 'refundPolicy'])->name('refund.policy');

        Route::get('maintenance', [HomeController::class, 'maintenance'])->name('maintenance');

        // Custom pages
        Route::get('/p/{id}', [HomeController::class, "customPage"])->name("custom.page");

        // Blogs
        Route::get('/blogs', [BlogController::class, "blogs"])->name("blogs")->middleware('scriptsanitizer');
        Route::get('/blog/{slug}', [BlogController::class, "viewBlog"])->name("view.blog")->middleware('scriptsanitizer');

        // Blog post share
        Route::get('/blog/{slug}/share/facebook', [ShareController::class, "shareToFacebook"])->name("sharetofacebook");
        Route::get('/blog/{slug}/share/twitter', [ShareController::class, "shareToTwitter"])->name("sharetotwitter");
        Route::get('/blog/{slug}/share/linkedin', [ShareController::class, "shareToLinkedIn"])->name("sharetolinkedin");
        Route::get('/blog/{slug}/share/instagram', [ShareController::class, "shareToInstagram"])->name("sharetoinstagram");
        Route::get('/blog/{slug}/share/whatsapp', [ShareController::class, "shareToWhatsApp"])->name("sharetowhatsapp");

        // Web Tools
        // HTML
        Route::get('html-beautifier', [WebToolsController::class, 'htmlBeautifier'])->name('web.html.beautifier');
        Route::get('html-minifier', [WebToolsController::class, 'htmlMinifier'])->name('web.html.minifier');

        // CSS
        Route::get('css-beautifier', [WebToolsController::class, 'cssBeautifier'])->name('web.css.beautifier');
        Route::get('css-minifier', [WebToolsController::class, 'cssMinifier'])->name('web.css.minifier');
        Route::post('css-minifier', [WebToolsController::class, 'resultCssMinifier'])->name('web.result.css.minifier');

        // JS
        Route::get('js-beautifier', [WebToolsController::class, 'jsBeautifier'])->name('web.js.beautifier');
        Route::get('js-minifier', [WebToolsController::class, 'jsMinifier'])->name('web.js.minifier');
        Route::post('js-minifier', [WebToolsController::class, 'resultjsMinifier'])->name('web.result.js.minifier');

        // Random Password Generator
        Route::get('random-password-generator', [WebToolsController::class, 'randomPasswordGenerator'])->name('web.random.password.generator');
        Route::post('random-password-generator', [WebToolsController::class, 'resultRandomPasswordGenerator'])->name('web.result.random.password.generator');

        // Bcrypt Password Generator
        Route::get('bcrypt-password-generator', [WebToolsController::class, 'bcryptPasswordGenerator'])->name('web.bcrypt.password.generator');
        Route::post('bcrypt-password-generator', [WebToolsController::class, 'resultBcryptPasswordGenerator'])->name('web.result.bcrypt.password.generator');

        // MD5 Password Generator
        Route::get('md5-password-generator', [WebToolsController::class, 'md5PasswordGenerator'])->name('web.md5.password.generator');
        Route::post('md5-password-generator', [WebToolsController::class, 'resultMd5PasswordGenerator'])->name('web.result.md5.password.generator');

        // Random Word Generator
        Route::get('random-word-generator', [WebToolsController::class, 'randomWordGenerator'])->name('web.random.word.generator');
        Route::post('random-word-generator', [WebToolsController::class, 'resultRandomWordGenerator'])->name('web.result.random.word.generator');

        // Text counter
        Route::get('text-counter', [WebToolsController::class, 'textCounter'])->name('web.text.counter');

        // Lorem Generator
        Route::get('lorem-generator', [WebToolsController::class, 'loremGenerator'])->name('web.lorem.generator');

        // Emojies
        Route::get('emojies', [WebToolsController::class, 'emojies'])->name('web.emojies');

        // DNS Lookup
        Route::get('dns-lookup', [WebToolsController::class, 'dnsLookup'])->name('web.dns.lookup');
        Route::post('dns-lookup', [WebToolsController::class, 'resultDnsLookup'])->name('web.result.dns.lookup');

        // IP Lookup
        Route::get('ip-lookup', [WebToolsController::class, 'ipLookup'])->name('web.ip.lookup');
        Route::post('ip-lookup', [WebToolsController::class, 'resultIpLookup'])->name('web.result.ip.lookup');

        // Whois Lookup
        Route::get('whois-lookup', [WebToolsController::class, 'whoisLookup'])->name('web.whois.lookup');
        Route::post('whois-lookup', [WebToolsController::class, 'resultWhoisLookup'])->name('web.result.whois.lookup');
    });

    Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin', 'frame.destroyer'], 'where' => ['locale' => '[a-zA-Z]{2}']], function () {
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Themes
        Route::get('themes', [ThemeController::class, 'themes'])->name('themes')->middleware('user.page.permission:themes');
        Route::get('active-themes', [ThemeController::class, 'activeThemes'])->name('active.themes')->middleware('user.page.permission:themes');
        Route::get('disabled-themes', [ThemeController::class, 'disabledThemes'])->name('disabled.themes')->middleware('user.page.permission:themes');
        Route::get('edit-theme/{id}', [ThemeController::class, 'editTheme'])->name('edit.theme')->middleware('user.page.permission:themes');
        Route::post('update-theme', [ThemeController::class, 'updateTheme'])->name('update.theme')->middleware('user.page.permission:themes');
        Route::get('update-theme-status', [ThemeController::class, 'updateThemeStatus'])->name('update.theme.status')->middleware('user.page.permission:themes');
        Route::get('search', [ThemeController::class, 'searchTheme'])->name('search.theme')->middleware('user.page.permission:themes');

        // Plans
        Route::get('plans', [PlanController::class, 'plans'])->name('plans')->middleware('user.page.permission:plans');
        Route::get('add-plan', [PlanController::class, 'addPlan'])->name('add.plan')->middleware('user.page.permission:plans');
        Route::post('save-plan', [PlanController::class, 'savePlan'])->name('save.plan')->middleware('user.page.permission:plans');
        Route::get('edit-plan/{id}', [PlanController::class, 'editPlan'])->name('edit.plan')->middleware('user.page.permission:plans');
        Route::post('update-plan', [PlanController::class, 'updatePlan'])->name('update.plan')->middleware('user.page.permission:plans');
        Route::get('status-plan', [PlanController::class, 'statusPlan'])->name('status.plan')->middleware('user.page.permission:plans');
        Route::get('delete-plan', [PlanController::class, 'deletePlan'])->name('delete.plan')->middleware('user.page.permission:plans');

        // Customers
        Route::get('customers', [CustomerController::class, 'customers'])->name('customers')->middleware('user.page.permission:customers');
        Route::get('edit-customer/{id}', [CustomerController::class, 'editCustomer'])->name('edit.customer')->middleware('user.page.permission:customers');
        Route::post('update-customer', [CustomerController::class, 'updateCustomer'])->name('update.customer')->middleware('user.page.permission:customers');
        Route::get('view-customer/{id}', [CustomerController::class, 'viewCustomer'])->name('view.customer')->middleware('user.page.permission:customers');
        Route::get('change-customer-plan/{id}', [CustomerController::class, 'ChangeCustomerPlan'])->name('change.customer.plan')->middleware('user.page.permission:customers');
        Route::post('update-customer-plan', [CustomerController::class, 'UpdateCustomerPlan'])->name('update.customer.plan')->middleware('user.page.permission:customers');
        Route::get('update-status', [CustomerController::class, 'updateStatus'])->name('update.status')->middleware('user.page.permission:customers');
        Route::get('delete-customer', [CustomerController::class, 'deleteCustomer'])->name('delete.customer')->middleware('user.page.permission:customers');
        Route::get('login-as/{id}', [CustomerController::class, 'authAs'])->name('login-as.customer')->middleware('user.page.permission:customers');

        // Payment Gateways
        Route::get('payment-methods', [PaymentMethodController::class, 'paymentMethods'])->name('payment.methods')->middleware('user.page.permission:payment_methods');
        Route::get('add-payment-method', [PaymentMethodController::class, 'addPaymentMethod'])->name('add.payment.method')->middleware('user.page.permission:payment_methods');
        Route::post('save-payment-method', [PaymentMethodController::class, 'savePaymentMethod'])->name('save.payment.method')->middleware('user.page.permission:payment_methods');
        Route::get('edit-payment-method/{id}', [PaymentMethodController::class, 'editPaymentMethod'])->name('edit.payment.method')->middleware('user.page.permission:payment_methods');
        Route::post('update-payment-method', [PaymentMethodController::class, 'updatePaymentMethod'])->name('update.payment.method')->middleware('user.page.permission:payment_methods');
        Route::get('delete-payment-method', [PaymentMethodController::class, 'deletePaymentMethod'])->name('delete.payment.method')->middleware('user.page.permission:payment_methods');

        // Coupons
        Route::get('coupons', [CouponsController::class, 'indexCoupons'])->name('coupons')->middleware('user.page.permission:coupons');
        Route::get('create-coupon', [CouponsController::class, 'createCoupon'])->name('create.coupon')->middleware('user.page.permission:coupons');
        Route::post('store-coupon', [CouponsController::class, 'storeCoupon'])->name('store.coupon')->middleware('user.page.permission:coupons');
        Route::get('edit-coupon/{id}', [CouponsController::class, 'editCoupon'])->name('edit.coupon')->middleware('user.page.permission:coupons');
        Route::post('update-coupon/{id}', [CouponsController::class, 'updateCoupon'])->name('update.coupon')->middleware('user.page.permission:coupons');
        Route::get('update-coupon-status', [CouponsController::class, 'updateCouponStatus'])->name('update.coupon.status')->middleware('user.page.permission:coupons');
        Route::get('delete-coupon', [CouponsController::class, 'deleteCoupon'])->name('delete.coupon')->middleware('user.page.permission:coupons');

        // Transactions
        Route::get('transactions', [TransactionsController::class, 'indexTransactions'])->name('transactions')->middleware('user.page.permission:transactions');
        Route::get('transaction-status/{id}/{status}', [TransactionsController::class, 'transactionStatus'])->name('trans.status')->middleware('user.page.permission:transactions');
        Route::get('offline-transactions', [TransactionsController::class, 'offlineTransactions'])->name('offline.transactions')->middleware('user.page.permission:transactions');
        Route::get('offline-transaction-status/{id}/{status}', [TransactionsController::class, 'offlineTransactionStatus'])->name('offline.trans.status')->middleware('user.page.permission:transactions');
        Route::get('view-invoice/{id}', [TransactionsController::class, 'viewInvoice'])->name('view.invoice')->middleware('user.page.permission:transactions');

        // Users
        Route::get('users', [UserController::class, 'users'])->name('users')->middleware('user.page.permission:users');
        Route::get('create-user', [UserController::class, 'createUser'])->name('create.user')->middleware('user.page.permission:users');
        Route::post('save-user', [UserController::class, 'saveUser'])->name('save.user')->middleware('user.page.permission:users');
        Route::get('view-user/{id}', [UserController::class, 'viewUser'])->name('view.user')->middleware('user.page.permission:users');
        Route::get('edit-user/{id}', [UserController::class, 'editUser'])->name('edit.user')->middleware('user.page.permission:users');
        Route::post('update-user', [UserController::class, 'updateUser'])->name('update.user')->middleware('user.page.permission:users');
        Route::get('update-user-status', [UserController::class, 'updateUserStatus'])->name('update.user.status')->middleware('user.page.permission:users');
        Route::get('delete-user', [UserController::class, 'deleteUser'])->name('delete.user')->middleware('user.page.permission:users');
        Route::get('login-as-user/{id}', [UserController::class, 'authAsUser'])->name('login-as.user')->middleware('user.page.permission:users');

        // Account Setting
        Route::get('account', [AccountController::class, 'account'])->name('account');
        Route::get('edit-account', [AccountController::class, 'editAccount'])->name('edit.account');
        Route::post('update-account', [AccountController::class, 'updateAccount'])->name('update.account');
        Route::get('change-password', [AccountController::class, 'changePassword'])->name('change.password');
        Route::post('update-password', [AccountController::class, 'updatePassword'])->name('update.password');

        // Change theme
        Route::get('theme/{id}', [AccountController::class, "changeTheme"])->name('change.theme');

        // Pages
        Route::get('pages', [PageController::class, "index"])->name('pages')->middleware('user.page.permission:pages');
        Route::get('custom-pages', [PageController::class, "customPagesIndex"])->name('custom.pages')->middleware('user.page.permission:pages');

        Route::get('add-page', [PageController::class, "addPage"])->name('add.page')->middleware('user.page.permission:pages');
        Route::post('save-page', [PageController::class, "savePage"])->name('save.page')->middleware('user.page.permission:pages');
        Route::get('custom-page/{id}', [PageController::class, "editCustomPage"])->name('edit.custom.page')->middleware('user.page.permission:pages');
        Route::post('custom-update-page', [PageController::class, "updateCustomPage"])->name('update.custom.page')->middleware('user.page.permission:pages');
        Route::get('status-page', [PageController::class, "statusPage"])->name('status.page')->middleware('user.page.permission:pages');
        Route::get('page/{id}', [PageController::class, "editPage"])->name('edit.page')->middleware('user.page.permission:pages');
        Route::post('update-page/{id}', [PageController::class, "updatePage"])->name('update.page')->middleware('user.page.permission:pages');
        Route::get('disable-page', [PageController::class, "disablePage"])->name('disable.page')->middleware('user.page.permission:pages');
        Route::get('delete-page', [PageController::class, "deletePage"])->name('delete.page')->middleware('user.page.permission:pages');

        // Blogs Categories
        Route::get('blog-categories', [BlogCategoryController::class, "index"])->name('blog.categories')->middleware('user.page.permission:blogs');
        Route::get('create-blog-category', [BlogCategoryController::class, "createBlogCategory"])->name('create.blog.category')->middleware('user.page.permission:blogs');
        Route::post('publish-blog-category', [BlogCategoryController::class, "publishBlogCategory"])->name('publish.blog.category')->middleware('user.page.permission:blogs');
        Route::get('edit-blog-category/{id}', [BlogCategoryController::class, "editBlogCategory"])->name('edit.blog.category')->middleware('user.page.permission:blogs');
        Route::post('update-blog-category/{id}', [BlogCategoryController::class, "updateBlogCategory"])->name('update.blog.category')->middleware('user.page.permission:blogs');
        Route::get('action-blog-category', [BlogCategoryController::class, "actionBlogCategory"])->name('action.blog.category')->middleware('user.page.permission:blogs');

        // Blogs
        Route::get('blogs', [AdminBlog::class, "index"])->name('blogs')->middleware('user.page.permission:blogs');
        Route::get('create-blog', [AdminBlog::class, "createBlog"])->name('create.blog')->middleware('user.page.permission:blogs');
        Route::post('publish-blog', [AdminBlog::class, "publishBlog"])->name('publish.blog')->middleware('user.page.permission:blogs');
        Route::get('edit-blog/{id}', [AdminBlog::class, "editBlog"])->name('edit.blog')->middleware('user.page.permission:blogs');
        Route::post('update-blog/{id}', [AdminBlog::class, "updateBlog"])->name('update.blog')->middleware('user.page.permission:blogs');
        Route::get('action-blog', [AdminBlog::class, "actionBlog"])->name('action.blog')->middleware('user.page.permission:blogs');

        // Settings
        Route::get('settings', [SettingsController::class, 'settings'])->name('settings')->middleware('user.page.permission:general_settings');
        Route::post('change-general-settings', [SettingsController::class, "changeGeneralSettings"])->name('change.general.settings')->middleware('user.page.permission:general_settings');
        Route::post('change-website-settings', [WebsiteSettingController::class, "index"])->name('change.website.settings')->middleware('user.page.permission:general_settings');
        Route::post('change-payments-settings', [PaymentSettingController::class, "index"])->name('change.payments.settings')->middleware('user.page.permission:general_settings');
        Route::post('change-google-settings', [GoogleSettingController::class, "index"])->name('change.google.settings')->middleware('user.page.permission:general_settings');

        Route::post('change-email-settings', [EmailSettingController::class, "index"])->name('change.email.settings')->middleware('user.page.permission:general_settings');
        Route::get('test-email', [EmailSettingController::class, 'testEmail'])->name('test.email')->middleware('user.page.permission:general_settings');

        Route::post('change-subdomain-settings', [SubdomainSettingController::class, "index"])->name('change.subdomain.settings')->middleware('user.page.permission:general_settings');
        Route::post('update-custom-script', [SettingsController::class, "updateCustomScript"])->name('update.custom.script')->middleware('user.page.permission:general_settings');

        // Tax and email template settings
        Route::get('tax-setting', [TaxSettingController::class, 'taxSetting'])->name('tax.setting')->middleware('user.page.permission:invoice_tax');
        Route::post('update-tex-setting', [TaxSettingController::class, 'updateTaxSetting'])->name('update.tax.setting')->middleware('user.page.permission:invoice_tax');
        Route::post('update-email-setting', [TaxSettingController::class, 'updateEmailSetting'])->name('update.email.setting')->middleware('user.page.permission:invoice_tax');

        // Clear cache
        Route::get('clear', [SettingsController::class, 'clear'])->name('clear');

        // Generating a sitemap
        Route::get('generate-sitemap', [SitemapController::class, 'index'])->name('generate.sitemap')->middleware('user.page.permission:sitemap');

        // Check update
        Route::get('check', [UpdateController::class, 'check'])->name('check')->middleware('user.page.permission:software_update');
        Route::post('check-update', [UpdateController::class, 'checkUpdate'])->name('check.update')->middleware('user.page.permission:software_update');
        Route::post('update-code', [UpdateController::class, 'updateCode'])->name('update.code')->middleware('user.page.permission:software_update');
    });

    Route::group(['as' => 'user.', 'prefix' => 'user', 'namespace' => 'User', 'middleware' => ['auth', 'user', 'frame.destroyer'], 'where' => ['locale' => '[a-zA-Z]{2}']], function () {

        // Dashboard
        Route::get('dashboard', [userDashboard::class, 'index'])->name('dashboard');

        // Business Cards
        Route::get('cards', [CardController::class, 'index'])->name('cards');
        Route::get('card-status/{id}', [CardController::class, 'cardStatus'])->name('card.status');

        // Choose Business type
        Route::get('choose-card-type', [CardController::class, 'chooseCardType'])->name('choose.card.type');

        // Create vcard
        Route::get('create-card', [CreateController::class, 'CreateCard'])->name('create.card');
        Route::post('save-business-card', [CreateController::class, 'saveBusinessCard'])->name('save.business.card');

        // Search theme
        Route::get('search', [CardController::class, 'searchTheme'])->name('search.theme');

        // Cropped image 
        Route::post('vcard-cropped-image', [CreateController::class, 'vcardCroppedImage'])->name('vcard.cropped.image');

        // Check link
        Route::post('check-link', [CreateController::class, 'checkLink'])->name('check.link');

        // Social Links (Create)
        Route::get('social-links/{id}', [SocialLinkController::class, 'socialLinks'])->name('social.links');
        Route::post('save-social-links/{id}', [SocialLinkController::class, 'saveSocialLinks'])->name('save.social.links');

        // Payment links (Create)
        Route::get('payment-links/{id}', [PaymentLinkController::class, 'paymentLinks'])->name('payment.links');
        Route::post('save-payment-links/{id}', [PaymentLinkController::class, 'savePaymentLinks'])->name('save.payment.links');

        // Vcard services (Create)
        Route::get('services/{id}', [ServiceController::class, 'services'])->name('services');
        Route::post('save-services/{id}', [ServiceController::class, 'saveServices'])->name('save.services');

        // Vcard products (Create)
        Route::get('vproducts/{id}', [ProductController::class, 'vProducts'])->name('vproducts');
        Route::post('save-vproducts/{id}', [ProductController::class, 'saveVProducts'])->name('save.vproducts');

        // Galleries (Create)
        Route::get('galleries/{id}', [GalleryController::class, 'galleries'])->name('galleries');
        Route::post('save-galleries/{id}', [GalleryController::class, 'saveGalleries'])->name('save.galleries');

        // Testimonials (Create)
        Route::get('testimonials/{id}', [TestimonialController::class, 'testimonials'])->name('testimonials');
        Route::post('save-testimonial/{id}', [TestimonialController::class, 'saveTestimonial'])->name('save.testimonial');

        // Business hours (Create)
        Route::get('business-hours/{id}', [BusinessHourController::class, 'businessHours'])->name('business.hours');
        Route::post('save-business-hours/{id}', [BusinessHourController::class, 'saveBusinessHours'])->name('save.business.hours');

        // Appointment
        Route::get('appointment/{id}', [AppointmentController::class, 'Appointment'])->name('appointment');
        Route::post('save-appointment/{id}', [AppointmentController::class, 'saveAppointment'])->name('save.appointment');

        // Contact form (Create)
        Route::get('contact-form/{id}', [ContactFormController::class, 'contactForm'])->name('contact.form');
        Route::post('save-contact-form/{id}', [ContactFormController::class, 'saveContactForm'])->name('save.contact.form');

        // Advanced settings (Create)
        Route::get('advanced-setting/{id}', [AdvancedSettingController::class, 'advancedSetting'])->name('advanced.setting');
        Route::post('save-advanced-setting/{id}', [AdvancedSettingController::class, 'saveAdvancedSetting'])->name('save.advanced.setting')->middleware('scriptsanitizer');

        // Inquiries
        Route::get('inquiries/{id}', [InquiryController::class, 'index'])->name('enquiries');

        // Visitors
        Route::get('visitors/{id}', [VisitorController::class, 'index'])->name('visitors');

        // Appointments
        Route::get('appointments/{id}', [BookedAppointmentController::class, 'bookedAppointments'])->name('appointments');
        Route::get('accept-appointment', [BookedAppointmentController::class, 'acceptAppointments'])->name('accept.appointment');
        Route::get('cancel-appointment', [BookedAppointmentController::class, 'cancelAppointments'])->name('cancel.appointment');
        Route::post('reschedule-appointment', [BookedAppointmentController::class, 'rescheduleAppointments'])->name('reschedule.appointment');
        Route::get('complete-appointment', [BookedAppointmentController::class, 'completeAppointments'])->name('complete.appointment');

        // Edit Business Card
        Route::get('edit-card/{id}', [EditCardController::class, 'editCard'])->name('edit.card');
        Route::post('update-business-card/{id}', [EditCardController::class, 'updateBusinessCard'])->name('update.business.card');

        // Edit Social Links
        Route::get('edit-social-links/{id}', [EditSocialLinkController::class, 'socialLinks'])->name('edit.social.links');
        Route::post('update-social-links/{id}', [EditSocialLinkController::class, 'updateSocialLinks'])->name('update.social.links');

        // Edit Payment Links
        Route::get('edit-payment-links/{id}', [EditPaymentLinkController::class, 'paymentLinks'])->name('edit.payment.links');
        Route::post('update-payment-links/{id}', [EditPaymentLinkController::class, 'updatePaymentLinks'])->name('update.payment.links');

        // Edit Service
        Route::get('edit-services/{id}', [EditServiceController::class, 'services'])->name('edit.services');
        Route::post('save-service', [EditServiceController::class, 'saveService'])->name('save.service');
        Route::post('update-service', [EditServiceController::class, 'updateService'])->name('update.service');
        Route::delete('delete-service/{id}', [EditServiceController::class, 'deleteService'])->name('delete.service');
        Route::get('get-service/{id}', [EditServiceController::class, 'getService']);

        // Edit Product
        Route::get('get-vproducts/{id}', [EditProductController::class, 'getVProducts']);
        Route::get('edit-vproducts/{id}', [EditProductController::class, 'vProducts'])->name('edit.vproducts');
        Route::post('save-vproduct', [EditProductController::class, 'saveVProduct'])->name('save.vproduct');
        Route::post('update-vproduct', [EditProductController::class, 'updateVProduct'])->name('update.vproduct');
        Route::delete('delete-vproduct/{id}', [EditProductController::class, 'deleteVProduct'])->name('delete.vproduct');

        // Edit Gallery
        Route::get('edit-galleries/{id}', [EditGalleryController::class, 'galleries'])->name('edit.galleries');
        Route::post('update-galleries/{id}', [EditGalleryController::class, 'updateGalleries'])->name('update.galleries');

        // Edit Testimonial
        Route::get('edit-testimonials/{id}', [EditTestimonialController::class, 'editTestimonials'])->name('edit.testimonials');
        Route::post('update-testimonial/{id}', [EditTestimonialController::class, 'updateTestimonial'])->name('update.testimonial');

        // Edit Business Hour
        Route::get('edit-business-hours/{id}', [EditBusinessHourController::class, 'businessHours'])->name('edit.business.hours');
        Route::post('update-business-hours/{id}', [EditBusinessHourController::class, 'updateBusinessHours'])->name('update.business.hours');

        // Edit Contact Form
        Route::get('edit-contact-form/{id}', [EditContactFormController::class, 'editContactForm'])->name('edit.contact.form');
        Route::post('update-contact-form/{id}', [EditContactFormController::class, 'updateContactForm'])->name('update.contact.form');

        // Edit Appointment
        Route::get('edit-appointment/{id}', [EditAppointmentController::class, 'editAppointment'])->name('edit.appointment');
        Route::post('update-appointment/{id}', [EditAppointmentController::class, 'updateAppointment'])->name('update.appointment');

        // Edit Advanced Settings
        Route::get('edit-advanced-setting/{id}', [EditAdvancedSettingController::class, 'editAdvancedSetting'])->name('edit.advanced.setting');
        Route::post('update-advanced-setting/{id}', [EditAdvancedSettingController::class, 'updateAdvancedSetting'])->name('update.advanced.setting');

        // Delete vcard
        Route::get('delete-card', [CardController::class, 'deleteCard'])->name('delete.card');

        // Business Stores
        Route::get('stores', [StoreController::class, 'index'])->name('stores');

        // Create store
        Route::get('create-store', [CreateStoreController::class, 'CreateStore'])->name('create.store');
        Route::post('save-store', [CreateStoreController::class, 'saveStore'])->name('save.store');

        // Cropped image 
        Route::post('store-cropped-images', [CreateStoreController::class, 'storeCroppedImage'])->name('store.cropped.images');

        // Create store products
        Route::get('products/{id}', [CreateStoreProductController::class, 'products'])->name('products');
        Route::post('save-products/{id}', [CreateStoreProductController::class, 'saveProducts'])->name('save.products');

        // Edit Store
        Route::get('edit-store/{id}', [UpdateStoreController::class, 'editStore'])->name('edit.store');
        Route::post('update-store/{id}', [UpdateStoreController::class, 'updateStore'])->name('update.store');

        // Edit Store products
        Route::get('get-products/{id}', [UpdateStoreProductController::class, 'getProducts']);
        Route::get('edit-products/{id}', [UpdateStoreProductController::class, 'editProducts'])->name('edit.products');
        Route::post('update-products/{id}', [UpdateStoreProductController::class, 'updateProducts'])->name('update.products');
        Route::post('save-product', [UpdateStoreProductController::class, 'saveProduct'])->name('save.product');
        Route::post('update-product', [UpdateStoreProductController::class, 'updateProduct'])->name('update.product');
        Route::delete('delete-product/{id}', [UpdateStoreProductController::class, 'deleteProduct'])->name('delete.product');

        // Delete store
        Route::get('delete-store', [StoreController::class, 'deleteStore'])->name('delete.store');

        // View Preview Business Card
        Route::get('view-preview/{id}', [PreviewController::class, 'index'])->name('view.preview');

        // Categories
        Route::get('categories', [CategoryController::class, 'categories'])->name('categories');
        Route::get('create-category', [CategoryController::class, "createCategory"])->name('create.category');
        Route::post('save-category', [CategoryController::class, "saveCategory"])->name('save.category');
        Route::get('edit-category/{id}', [CategoryController::class, 'editCategory'])->name('edit.category');
        Route::post('update-category', [CategoryController::class, 'updateCategory'])->name('update.category');
        Route::get('status-category', [CategoryController::class, 'statusCategory'])->name('status.category');
        Route::get('delete-category', [CategoryController::class, 'deleteCategory'])->name('delete.category');

        // Duplicate
        Route::get('duplicate', [DuplicateController::class, 'duplicate'])->name('duplicate');

        // Business Plans
        Route::get('plans', [UserPlanController::class, 'index'])->name('plans');

        // Media
        Route::get('media', [MediaController::class, 'media'])->name('media');
        Route::get('media-data', [MediaController::class, 'getMediaData'])->name('media.data');
        Route::get('add-media', [MediaController::class, 'addMedia'])->name('add.media');
        Route::post('upload-media', [MediaController::class, 'uploadMedia'])->name('upload.media');
        Route::get('delete-media', [MediaController::class, 'deleteMedia'])->name('media.delete');

        // Upload media images
        Route::post('multiple', [MediaController::class, 'multipleImages'])->name('multiple');

        //Addtional Tootls -> QR Maker
        Route::get('tools/qr-maker', [AdditionalController::class, 'qrMaker'])->name('qr-maker');
        Route::get('tools/whois-lookup', [AdditionalController::class, 'whoisLookup'])->name('whois-lookup');
        Route::post('tools/whois-lookup', [AdditionalController::class, 'resultWhoisLookup'])->name('result.whois-lookup');
        Route::get('tools/dns-lookup', [AdditionalController::class, 'dnsLookup'])->name('dns-lookup');
        Route::post('tools/dns-lookup', [AdditionalController::class, 'resultDnsLookup'])->name('result.dns-lookup');
        Route::get('tools/ip-lookup', [AdditionalController::class, 'ipLookup'])->name('ip-lookup');
        Route::post('tools/ip-lookup', [AdditionalController::class, 'resultIpLookup'])->name('result.ip-lookup');

        // Transactions
        Route::get('transactions', [userTransactions::class, 'indexTransactions'])->name('transactions');
        Route::get('view-invoice/{id}', [userTransactions::class, 'viewInvoice'])->name('view.invoice');

        // Billing
        Route::get('billing/{id}', [BillingController::class, 'billing'])->name('billing');
        Route::post('update-billing', [BillingController::class, 'updateBilling'])->name('update.billing');

        // Checkout
        Route::get('checkout/{id}', [CheckOutController::class, 'index'])->name('checkout');
        Route::post('checkout-coupon/{id}', [CheckOutController::class, 'checkoutCoupon'])->name('checkout.coupon');

        // Save Upgrade Plan
        Route::post('save-upgrade/{id}', [CardController::class, 'saveUpgrade'])->name('save.upgrade.plan');

        // Resend Email Verfication
        Route::get('verify-email-verification', [VerificationController::class, "verifyEmailVerification"])->name('verify.email.verification');
        Route::get('resend-email-verification', [VerificationController::class, "resendEmailVerification"])->name('resend.email.verification');

        // Account Setting
        Route::get('account', [userAccount::class, 'account'])->name('account');
        Route::get('edit-account', [userAccount::class, 'editAccount'])->name('edit.account');
        Route::post('update-account', [userAccount::class, 'updateAccount'])->name('update.account');
        Route::get('change-password', [userAccount::class, 'changePassword'])->name('change.password');
        Route::post('update-password', [userAccount::class, 'updatePassword'])->name('update.password');

        // Change theme
        Route::get('theme/{id}', [AccountController::class, "changeTheme"])->name('change.theme');
    });

    // Choose Payment Gateway
    Route::post('/prepare-payment/{planId}', [PaymentController::class, 'preparePaymentGateway'])->name('prepare.payment.gateway');

    // PayPal Payment Gateway
    Route::get('/payment-paypal/{planId}/{couponId}', [PaypalController::class, 'paywithpaypal'])->name('paywithpaypal');
    Route::get('/payment/status', [PaypalController::class, 'paypalPaymentStatus'])->name('paypalPaymentStatus');

    // RazorPay
    Route::get('payment-razorpay/{planId}/{couponId}', [RazorpayController::class, 'prepareRazorpay'])->name('paywithrazorpay');
    Route::get('razorpay-payment-status/{oid}/{paymentId}', [RazorpayController::class, 'razorpayPaymentStatus'])->name('razorpay.payment.status');

    // Phonepe
    Route::get('/payment-phonepe/{planId}/{couponId}', [PhonepeController::class, 'preparePhonpe'])->name('paywithphonepe');
    Route::any('/phonepe-payment-status', [PhonepeController::class, 'phonepePaymentStatus'])->name('phonepe.payment.status');

    // Stripe
    Route::get('/payment-stripe/{planId}/{couponId}', [StripeController::class, 'stripeCheckout'])->name('paywithstripe');
    Route::post('/stripe-payment-status/{paymentId}', [StripeController::class, 'stripePaymentStatus'])->name('stripe.payment.status');
    Route::get('/stripe-payment-cancel/{paymentId}', [StripeController::class, 'stripePaymentCancel'])->name('stripe.payment.cancel');

    // Paystack
    Route::get('/payment-paystack/{planId}/{couponId}', [PaystackController::class, "paystackCheckout"])->name('paywithpaystack');
    Route::get('/paystack-payment/callback', [PaystackController::class, 'paystackHandleGatewayCallback'])->name('paystack.handle.gateway.callback');

    // Mollie
    Route::get('/payment-mollie/{planId}/{couponId}', [MollieController::class, "prepareMollie"])->name('paywithmollie');
    Route::get('/mollie-payment-status', [MollieController::class, "molliePaymentStatus"])->name('mollie.payment.status');

    // Mercado Pago
    Route::get('/payment-mercadopago/{planId}/{couponId}', [MercadoPagoController::class, "prepareMercadoPago"])->name('paywithmercadopago');
    Route::get('/mercadopago-payment-status', [MercadoPagoController::class, "mercadoPagoPaymentStatus"])->name('mercadopago.payment.status');
    Route::get('/mercadopago-payment-failure', [MercadoPagoController::class, "mercadoPagoPaymentFailure"])->name('mercadopago.payment.failure');
    Route::get('/mercadopago-payment-pending', [MercadoPagoController::class, "mercadoPagoPaymentPending"])->name('mercadopago.payment.pending');

    // Toyyibpay
    Route::get('/payment-toyyibpay/{planId}/{couponId}', [ToyyibpayController::class, "prepareToyyibpay"])->name('prepare.toyyibpay');
    Route::get('/toyyibpay-payment-status', [ToyyibpayController::class, "toyyibpayPaymentStatus"])->name('toyyibpay.payment.status');
    Route::get('/toyyibpay-payment-success', [ToyyibpayController::class, 'toyyibpayPaymentSuccess'])->name('toyyibpay.payment.success');

    // Flutterwave
    Route::get('/payment-flutterwave/{planId}/{couponId}', [FlutterwaveController::class, "prepareFlutterwave"])->name('prepare.flutterwave');
    Route::get('/flutterwave-payment-status', [FlutterwaveController::class, "flutterwavePaymentStatus"])->name('flutterwave.payment.status');

    // Offline
    Route::get('/payment-offline/{planId}/{couponId}', [OfflineController::class, 'offlineCheckout'])->name('paywithoffline');
    Route::post('/mark-offline-payment', [OfflineController::class, 'markOfflinePayment'])->name('mark.payment.payment');

    // Google Auth
    Route::get('/google-login', [LoginController::class, 'redirectToProvider'])->name('login.google');
    Route::get('/sign-in-with-google', [LoginController::class, 'handleProviderCallback']);

    // Profile
    Route::group(['middleware' => 'scriptsanitizer'], function () {
        Route::get('{id}', [ProfileController::class, 'profile', 'ShareWidget'])->name('profile');
    });

    // Get day wise available time slots
    Route::post('get-available-time-slots', [BookAppointmentController::class, 'getAvailableTimeSlots'])->name('get.available.time.slots');
    
    // Save appointment
    Route::post('book-appointment', [BookAppointmentController::class, 'bookAppointment'])->name('book.appointment');

    Route::get('dynamic-card/{id}', [SubdomainController::class, 'dynamicCard'])->name('dynamic.card');

    Route::post('check-password/{id}', [ProfileController::class, 'checkPwd'])->name('check.pwd');

    Route::post('sent-enquiry', [ProfileController::class, 'sentEnquiry'])->name('sent.enquiry');

    Route::get('/download/{id}', [ProfileController::class, 'downloadVcard'])->name('download.vCard');

    Route::post('/set-locale', [ProfileController::class, 'setLocale'])->name('set.locale');
});
