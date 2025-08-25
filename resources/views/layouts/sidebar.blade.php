<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" class="app-brand-link">
            <span class="app-brand-logo demo text-center">
                <img src="{{ asset('img/logoSvg.svg') }}" alt="" class="w-25 m-auto">
            </span>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">
        <li class="menu-item active">
            <a href="{{ route('home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">{{ __('messages.dashboard') }}</div>
            </a>
        </li>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('messages.management') }}</span>
        </li>
        @canany(['roles_list', 'roles_create'])
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="Layouts">{{ __('messages.roles') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('roles_list')
                        <li class="menu-item">
                            <a href="{{ route('roles.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.all_roles') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('roles_create')
                        <li class="menu-item">
                            <a href="{{ route('roles.create') }}" class="menu-link">
                                <div data-i18n="Without navbar">{{ __('messages.new_role') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @canany(['users_list', 'users_create'])
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="Layouts">{{ __('messages.users') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('users_list')
                        <li class="menu-item">
                            <a href="{{ route('users.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.all_users') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('users_create')
                        <li class="menu-item">
                            <a href="{{ route('users.create') }}" class="menu-link">
                                <div data-i18n="Without navbar">{{ __('messages.new_user') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
        @can('clients_list')
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="Layouts">{{ __('messages.clients') }}</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('clients.index') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ __('messages.all_clients') }}</div>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan
        @canany(['categories_list', 'categories_create'])
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="Layouts">{{ __('messages.categories') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('categories_list')
                        <li class="menu-item">
                            <a href="{{ route('categories.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.all_categories') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('categories_create')
                        <li class="menu-item">
                            <a href="{{ route('categories.create') }}" class="menu-link">
                                <div data-i18n="Without navbar">{{ __('messages.new_category') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
        @canany(['sub_categories_list', 'sub_categories_create'])
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="Layouts">{{ __('messages.sub_categories') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('sub_categories_list')
                        <li class="menu-item">
                            <a href="{{ route('sub_categories.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.all_sub_categories') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('sub_categories_create')
                        <li class="menu-item">
                            <a href="{{ route('sub_categories.create') }}" class="menu-link">
                                <div data-i18n="Without navbar">{{ __('messages.new_sub_category') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
        @canany(['brands_list', 'brands_create'])

            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="Layouts">{{ __('messages.brands') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('brands_list')
                        <li class="menu-item">
                            <a href="{{ route('brands.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.all_brands') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('brands_create')
                        <li class="menu-item">
                            <a href="{{ route('brands.create') }}" class="menu-link">
                                <div data-i18n="Without navbar">{{ __('messages.new_brand') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
        @canany(['offers_list', 'offers_create'])

            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="Layouts">{{ __('messages.offers') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('offers_list')
                        <li class="menu-item">
                            <a href="{{ route('offers.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.all_offers') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('offers_create')
                        <li class="menu-item">
                            <a href="{{ route('offers.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.new_offer') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
        @canany(['products_list', 'products_create'])
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="Layouts">{{ __('messages.products') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('products_list')
                        <li class="menu-item">
                            <a href="{{ route('products.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.all_products') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('products_create')
                        <li class="menu-item">
                            <a href="{{ route('products.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.new_product') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
        @canany(['drivers_list', 'drivers_create'])
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="Layouts">{{ __('messages.drivers') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('drivers_list')
                        <li class="menu-item">
                            <a href="{{ route('drivers.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.all_drivers') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('drivers_create')
                        <li class="menu-item">
                            <a href="{{ route('drivers.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.new_driver') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
        @canany(['orders_list', 'orders_change_status'])
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="Layouts">{{ __('messages.orders') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('orders_list')
                        <li class="menu-item">
                            <a href="{{ route('orders.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.orders') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
        @canany(['slider_images_list'])
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="Layouts">{{ __('messages.slider_images') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('slider_images_list')
                        <li class="menu-item">
                            <a href="{{ route('slider_images.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.slider_images_index') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('slider_images_create')
                        <li class="menu-item">
                            <a href="{{ route('slider_images.create') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.add_slider_image') }}</div>
                            </a>
                        </li>
                    @endcan
                     <li class="menu-item">
                            <a href="{{ route('slider_products.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.slider_products_index') }}</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('slider_products.create') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.add_slider_products') }}</div>
                            </a>
                        </li>
                </ul>
            </li>
        @endcanany
        @canany(['coupons_list', 'coupons_create'])
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="Layouts">{{ __('messages.coupons') }}</div>
                </a>
                <ul class="menu-sub">
                    @can('coupons_list')
                        <li class="menu-item">
                            <a href="{{ route('coupons.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.coupons_index') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('coupons_create')
                        <li class="menu-item">
                            <a href="{{ route('coupons.create') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.add_coupon') }}</div>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
        @canany(['store_settings_list', 'contact_requests_list', 'account_deletion_list', 'empty_driver_petty_cash'])
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div data-i18n="Layouts">{{ __('messages.site_generals') }}</div>
                </a>
                <ul class="menu-sub">

                    @can('store_settings_list')
                        <li class="menu-item">
                            <a href="{{ route('site_generals.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.site_generals') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('contact_requests_list')
                        <li class="menu-item">
                            <a href="{{ route('contactus.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.contact_us_requests') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('app_ratings_list')
                        <li class="menu-item">
                            <a href="{{ route('app_rate.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.app_ratings') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('account_deletion_list')
                        <li class="menu-item">
                            <a href="{{ route('account_delete_requests.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.accounts_delete_requests') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('branches_list')
                        <li class="menu-item">
                            <a href="{{ route('branches.index') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.branches') }}</div>
                            </a>
                        </li>
                    @endcan
                    @can('empty_driver_petty_cash')
                        <li class="menu-item">
                            <a href="{{ route('get_petty_cash') }}" class="menu-link">
                                <div data-i18n="Without menu">{{ __('messages.empty_driver_petty_cash') }}</div>
                            </a>
                        </li>
                    @endcan
                    {{-- <li class="menu-item">
                        <a href="{{ route('send_client_notification_form') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ __('messages.client_notification') }}</div>
                        </a>
                    </li> --}}
                    <li class="menu-item">
                        <a href="{{ route('send_global_notification_form') }}" class="menu-link">
                            <div data-i18n="Without menu">{{ __('messages.global_notification') }}</div>
                        </a>
                    </li>
                </ul>
            </li>

        @endcanany
    </ul>
</aside>
