@php
    use Illuminate\Support\Facades\Auth;
    use Carbon\Carbon;

    // Fetch current user's details
$user = Auth::user();
$allowedPermissions = json_decode($user->permissions, true);

// Check and assign missing permissions
$allowedPermissions['coupons'] = 1;

// Update plan details if necessary
if ($allowedPermissions !== json_decode($user->permissions, true)) {
    $user->permissions = json_encode($allowedPermissions);
    $user->updated_at = Carbon::now();
    $user->save();
}

// Fetch current user's details
    $allowedPermissions = json_decode($user->permissions, true);
@endphp

<div class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar navbar-light">
            <div class="container-xl">
                <ul class="navbar-nav">
                    {{-- Dashboard --}}
                    <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <polyline points="5 12 3 12 12 3 21 12 19 12" />
                                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                {{ __('Dashboard') }}
                            </span>
                        </a>
                    </li>

                    {{-- Themes --}}
                    @if ($allowedPermissions['themes'])
                        <li
                            class="nav-item dropdown {{ request()->is('admin/themes') || request()->is('admin/active-themes') || request()->is('admin/disabled-themes') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown"
                                role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-businessplan" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <ellipse cx="16" cy="6" rx="5" ry="3"></ellipse>
                                        <path d="M11 6v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4"></path>
                                        <path d="M11 10v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4"></path>
                                        <path d="M11 14v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4"></path>
                                        <path d="M7 9h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5"></path>
                                        <path d="M5 15v1m0 -8v1"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('Themes') }}
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('admin.themes') }}">
                                    {{ __('All') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.active.themes') }}">
                                    {{ __('Active Themes') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.disabled.themes') }}">
                                    {{ __('Disabled Themes') }}
                                </a>
                            </div>
                        </li>
                    @endif

                    {{-- Plans --}}
                    @if ($allowedPermissions['plans'])
                        <li
                            class="nav-item dropdown {{ request()->is('admin/plans') || request()->is('admin/add-plan') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown"
                                role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-businessplan" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <ellipse cx="16" cy="6" rx="5" ry="3"></ellipse>
                                        <path d="M11 6v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4"></path>
                                        <path d="M11 10v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4"></path>
                                        <path d="M11 14v4c0 1.657 2.239 3 5 3s5 -1.343 5 -3v-4"></path>
                                        <path d="M7 9h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5"></path>
                                        <path d="M5 15v1m0 -8v1"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('Plans') }}
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('admin.plans') }}">
                                    {{ __('All Plans') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.add.plan') }}">
                                    {{ __('Add Plan') }}
                                </a>
                            </div>
                        </li>
                    @endif

                    {{-- Customers --}}
                    @if ($allowedPermissions['customers'])
                        <li class="nav-item {{ request()->is('admin/customers') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.customers') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('Customers') }}
                                </span>
                            </a>
                        </li>
                    @endif

                    {{-- Payment methods --}}
                    @if ($allowedPermissions['payment_methods'])
                        <li class="nav-item {{ request()->is('admin/payment-methods') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.payment.methods') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-building-bank" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <line x1="3" y1="21" x2="21" y2="21"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                        <polyline points="5 6 12 3 19 6"></polyline>
                                        <line x1="4" y1="10" x2="4" y2="21"></line>
                                        <line x1="20" y1="10" x2="20" y2="21"></line>
                                        <line x1="8" y1="14" x2="8" y2="17"></line>
                                        <line x1="12" y1="14" x2="12" y2="17"></line>
                                        <line x1="16" y1="14" x2="16" y2="17"></line>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('Payment Methods') }}
                                </span>
                            </a>
                        </li>
                    @endif

                    {{-- Coupons --}}
                    @if ($allowedPermissions['coupons'])
                        <li class="nav-item {{ request()->is('admin/coupons') || request()->is('admin/create-coupon') || request()->is('admin/edit-coupon/*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.coupons') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-discount">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M9 15l6 -6" />
                                        <circle cx="9.5" cy="9.5" r=".5" fill="currentColor" />
                                        <circle cx="14.5" cy="14.5" r=".5" fill="currentColor" />
                                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('Coupons') }}
                                </span>
                            </a>
                        </li>
                    @endif

                    {{-- Transactions --}}
                    @if ($allowedPermissions['transactions'])
                        <li
                            class="nav-item dropdown {{ request()->is('admin/transactions') || request()->is('admin/offline-transactions') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown"
                                role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                        <rect x="9" y="3" width="6" height="4" rx="2" />
                                        <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                        <path d="M12 17v1m0 -8v1" />
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('Transactions') }}
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                <a href="{{ route('admin.transactions') }}"
                                    class="dropdown-item">{{ __('Transactions') }}</a>
                                <a href="{{ route('admin.offline.transactions') }}"
                                    class="dropdown-item">{{ __('Offline Transactions') }}</a>
                            </div>
                        </li>
                    @endif

                    {{-- Pages --}}
                    @if ($allowedPermissions['pages'] || $allowedPermissions['blogs'])
                        <li
                            class="nav-item dropdown {{ request()->is('admin/pages') || request()->is('admin/blogs') || request()->is('admin/categories') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown"
                                role="button" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-settings" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path
                                            d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z">
                                        </path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('Website') }}
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                <div class="dropdown-menu-columns">
                                    <div class="dropdown-menu-column">
                                        {{-- Pages --}}
                                        @if ($allowedPermissions['pages'])
                                            <a href="{{ route('admin.pages') }}"
                                                class="dropdown-item">{{ __('Pages') }}</a>
                                        @endif

                                        {{-- Blogs --}}
                                        @if ($allowedPermissions['blogs'])
                                            <div class="dropend">
                                                <a class="dropdown-item dropdown-toggle"
                                                    href="#sidebar-authentication" data-bs-toggle="dropdown"
                                                    data-bs-auto-close="outside" role="button"
                                                    aria-expanded="false">
                                                    {{ __('Blogs') }}
                                                </a>
                                                <div class="dropdown-menu">
                                                    <a href="{{ route('admin.blogs') }}"
                                                        class="dropdown-item">{{ __('Blogs') }}</a>
                                                    <a href="{{ route('admin.blog.categories') }}"
                                                        class="dropdown-item">{{ __('Categories') }}</a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif

                    {{-- Users --}}
                    @if ($allowedPermissions['users'])
                        <li class="nav-item {{ request()->is('admin/users') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.users') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('Users') }}
                                </span>
                            </a>
                        </li>
                    @endif

                    {{-- Settings --}}
                    @if (
                        $allowedPermissions['general_settings'] ||
                            $allowedPermissions['translations'] ||
                            $allowedPermissions['sitemap'] ||
                            $allowedPermissions['invoice_tax'] ||
                            $allowedPermissions['software_update']
                    )
                        <li class="nav-item dropdown {{ request()->is('admin/settings') ? 'active' : '' }}">
                            <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown"
                                role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="icon icon-tabler icon-tabler-settings" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path
                                            d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z">
                                        </path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </span>
                                <span class="nav-link-title">
                                    {{ __('Settings') }}
                                </span>
                            </a>
                            <div class="dropdown-menu">
                                {{-- General Settings --}}
                                @if ($allowedPermissions['general_settings'])
                                    <a href="{{ route('admin.settings') }}"
                                        class="dropdown-item">{{ __('General Settings') }}</a>
                                @endif

                                {{-- Translations --}}
                                @if ($allowedPermissions['translations'])
                                    <a href="{{ asset('languages') }}"
                                        class="dropdown-item">{{ __('Translations') }}</a>
                                @endif

                                {{-- Sitemap --}}
                                @if ($allowedPermissions['sitemap'])
                                    <a href="{{ route('admin.generate.sitemap') }}"
                                        class="dropdown-item">{{ __('Generate Sitemap') }}</a>
                                @endif

                                {{-- Invoice & Tax --}}
                                @if ($allowedPermissions['invoice_tax'])
                                    <a href="{{ route('admin.tax.setting') }}"
                                        class="dropdown-item">{{ __('Invoice & Tax') }}</a>
                                @endif

                                {{-- Software Update --}}
                                @if ($allowedPermissions['software_update'])
                                    <a href="{{ route('admin.check') }}"
                                        class="dropdown-item">{{ __('Software Update') }}</a>
                                @endif
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
