<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Music Sound'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
                
            </main>
        </div>
    <!-- jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- DataTables -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
    <!-- Đoạn mã JavaScript DataTables -->
    <script>
        $(document).ready( function () {
            $('#musicTable').DataTable({
                "lengthChange": false, // Turn off "Show [X] entries"
                "pageLength": 10 // By default, displays 10 lines at a time
            });
        } );
    </script>

    <script>
        $(document).ready(function() {
            // Get a list of all audio tags
            const audioPlayers = document.querySelectorAll('.audio-player');
        
            // Listen for the event when playback starts
            audioPlayers.forEach(function(player) {
                player.addEventListener('play', function() {
                    audioPlayers.forEach(function(otherPlayer) {
                        if (otherPlayer !== player && !otherPlayer.paused) {
                            otherPlayer.pause();
                        }
                    });
                });
            });
        });
    </script>

    </body>
</html>
