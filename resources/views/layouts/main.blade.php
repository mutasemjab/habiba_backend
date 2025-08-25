<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-menu-fixed" dir="ltr"
    data-theme="theme-default">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ __('messages.habiba_store') }} | @yield('title')</title>

    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logoSvg.svg') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" />
    <!-- Add these in layouts.main.blade.php in the head section -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        Pusher.logToConsole = true;
        var pusher = new Pusher('a176a55ab802f43a0536', {
            cluster: 'mt1'
        });
        var channel = pusher.subscribe('orders');
        
        // Function to play notification sound
        function playNotificationSound() {
            const sound = document.getElementById('notification-sound');
            if (sound) {
                // Reset the audio to the beginning if it's already playing
                sound.pause();
                sound.currentTime = 0;
                
                // Play the sound
                sound.play().catch(error => {
                    console.warn('Failed to play notification sound:', error);
                    // Browser may require user interaction before playing audio
                });
            }
        }
    
        function updateNotificationCounter() {
            fetch('/notifications/count')
                .then(response => response.json())
                .then(data => {
                    const counterElement = document.getElementById('notification-counter');
                    if (counterElement) {
                        counterElement.innerText = data.count;
                    } else {
                        console.warn('Notification counter element not found in the DOM.');
                    }
                })
                .catch(error => console.error('Error updating notification counter:', error));
        }
        
        // Bind to order.created
        channel.bind('order.created', function(data) {
            const message = `
                <b>العميل:</b> ${data.client_name}<br>
                <b>حالة الطلب الحالية:</b> ${data.status}<br>
                <b>قيمة الطلب:</b> ${data.total_price}<br>
            `;
            toastr.success(message, 'طلب شراء جديد ', {
                timeOut: 0,
                extendedTimeOut: 0,
                progressBar: true,
                positionClass: 'toast-top-right',
            });
            playNotificationSound(); // Play sound for new order
            updateNotificationCounter();
        });
    
        channel.bind('order.cancelled', function(data) {
            const message = `
                <strong>قام العميل  ${data.client_name}</strong><br>
                <b>بالغاء طلبة رقم:</b> ${data.order_id}<br>`;
    
            toastr.error(message, 'عملية الغاء طلب  ', {
                timeOut: 0,
                extendedTimeOut: 0,
                progressBar: true,
                positionClass: 'toast-top-right',
            });
            playNotificationSound(); // Play sound for cancelled order
            updateNotificationCounter();
        });
    
        channel.bind('order.refused', function(data) {
            const message = `
                <b>السائق:</b> ${data.driver_name}<br>
                <b>رفض توصيل طلب رقم:</b> ${data.order_id}<br>
                <b>الخاص بالعميل :</b> ${data.client_name}<br>`;
    
            toastr.error(message, 'رفض عملية توصيل طلب ', {
                timeOut: 0,
                extendedTimeOut: 0,
                progressBar: true,
                positionClass: 'toast-top-right',
            });
            playNotificationSound(); // Play sound for refused delivery
            updateNotificationCounter();
        });
    
        // Bind to order.status_change
        channel.bind('order.status_change', function(data) {
            const message = `
                <b>تغير حالة الطلب رقم :</b> ${data.order_id}<br>
                <b>الى :</b> ${data.status}<br>
            `;
            toastr.info(message, 'تغيير حالة طلب ', {
                timeOut: 0,
                extendedTimeOut: 0,
                progressBar: true,
                positionClass: 'toast-top-right',
            });
            playNotificationSound(); // Play sound for status change
            updateNotificationCounter();
        });
    });
    </script>


    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <style>
        .avatar {
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            position: relative;
        }

        .notification-counter {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            /* Red color for the counter */
            color: white;
            font-size: 12px;
            font-weight: bold;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <!-- Add this audio element for notification sounds -->
<audio id="notification-sound" preload="auto">
    <source src="{{ asset('sounds/notification.wav') }}" type="audio/mpeg">
</audio>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @yield('sidebar')
            <div class="layout-page">
                @yield('navbar')
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">

                        @yield('content')
                    </div>

                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
    </div>
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    @yield('scripts')
</body>

</html>
