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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/html-docx-js@0.3.1/dist/html-docx.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    </body>
</html>
