<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <style>
        
        main h1 {
          font-size: 50px;
          font-weight: 700;
          color: #3f51b5;
        }
        
        input.form-control,
        select.form-control, textarea.form-control {
          border: 2px solid red;
        }
        
        input.form-control::placeholder {
          color: #9e9e9e;
        }
        
         




        .card, .container {
            margin-top: 10px;
          background-color: #ffffff;
          border: none;
          box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
          border-radius: 12px;
          transition: transform 0.2s ease;
          padding: 10px;
        }
        
        .card:hover {
          transform: translateY(-4px);
        }
        
        .card-title {
          color: #3f51b5;
          font-weight: 600;
        }
        
        .card-text {
          color: #555;
        }
        
        .btn-primary {
          background-color: #3f51b5;
          border-color: #3f51b5;
          border-radius: 6px;
          transition: background-color 0.2s ease;
        }
        
        .btn-primary:hover {
          background-color: #303f9f;
          border-color: #303f9f;
        }

        
        #fixedCard {
        position: fixed;
        top: 90px;
        left: 20px;
        width: 450px;
        height: 750px;
        overflow: auto;
        background: #fff;
        transition: width 0.3s ease; /* smooth animation */
        z-index: 10;
        }
        
        #toggleBtn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        padding: 10px 16px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }
        
        #toggleBtn:hover {
        background: #0056b3;
        }
        </style>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

        <style>
            /* Select2 Bootstrap 4 Theme adjustments */
            .select2-container--bootstrap4 .select2-selection--single {
                height: calc(1.5em + .75rem + 2px) !important;
                border: 1px solid #ced4da;
                border-radius: .25rem;
            }
            .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
                line-height: calc(1.5em + .75rem) !important;
                padding-left: .75rem !important;
                color: #495057;
            }
            .select2-container--bootstrap4 .select2-selection--single .select2-selection__placeholder {
                color: #6c757d;
            }
            .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
                top: 50% !important;
                transform: translateY(-50%) !important;
                right: 8px !important;
            }
            .select2-container--bootstrap4 .select2-dropdown {
                border-color: #80bdff;
                box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,.15);
            }
            .select2-search--dropdown .select2-search__field {
                border-radius: 4px;
                padding: 6px 10px;
            }
        </style>

        <!-- jQuery Full (Required for Select2) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen ">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>

        <!-- Bootstrap JS and dependencies -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/html-docx-js@0.3.1/dist/html-docx.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        @stack('scripts')
    </body>
</html>
