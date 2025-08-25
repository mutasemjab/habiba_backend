<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                    <i class="bx bx-menu bx-sm"></i>
                </a>
            </div>
              <li class="nav-item me-2">
                <a href="{{ route('admin.products.sync-prices') }}" class="btn btn-primary" id="sync-prices-btn">
                    <i class="bx bx-refresh me-1"></i> {{ __('Sync Prices') }}
                </a>
            </li>
            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                <ul class="navbar-nav flex-row align-items-center ms-auto">
                    <li class="nav-item navbar-dropdown dropdown-user dropdown">
                        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                            data-bs-toggle="dropdown">
                            <div class="avatar">
                                <i class="fa-solid fa-bell alert-icon"></i>
                                <span id="notification-counter" class="notification-counter">{{App\Models\DashboardNotification::where('is_read',false)->count()}}</span>
                                {{-- <span id="notification-counter" class="badge badge-danger">0</span> --}}

                            </div>
                        </a>
                        <ul class="dropdown-menu" style="">
                            @foreach (App\Models\DashboardNotification::where('is_read', false)->get() as $notifi)
                                <li>
                                    <a class="dropdown-item" href="{{route('read_notification',$notifi->id)}}">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <p class="mb-1" style="font-weight: bold;">{{$notifi->notification_title}}</p>
                                                <p class="mb-0">{{$notifi->notification_body}}</p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('img/language-switcher.png') }}" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <a class="dropdown-item"
                                href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                <img src="{{ asset('img/' . $localeCode . '.png') }}" alt="{{ $properties['native'] }}"
                                    style="width: 20px; height: auto; margin-right: 5px;">
                                {{ $properties['native'] }}
                            </a>
                        @endforeach
                    </li>
                </ul>
            </li>
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('img/logoSvg.svg') }}" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('img/logoSvg.svg') }}" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>

                    <li>
                        <a class="dropdown-item" href="{{ route('admin.logout') }}">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">{{ __('messages.log_out') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            
        </ul>
    </div>
</nav>
