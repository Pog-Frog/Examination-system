<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <link rel="icon" type="image/png"
            href="{{ asset('img/logo.ico') }}" />
        <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i&amp;display=swap">
        <link rel="stylesheet" href="{{asset('fonts/simple-line-icons.min.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.1/baguetteBox.min.css">
        <link rel="stylesheet" href="{{asset('css/vanilla-zoom.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/all.min.css')}}">
        <title>@yield('title')</title>
    </head>

    <style>
        main{
            min-height: 100vh;
            padding-top: 2%;
        }
        
        .clean-block{
            min-height: 100vh;
        }

        .clean-block{
            padding-top: 3%;
        }
    </style>

    <body>
        @include('includes.navbar')
        @include('includes.flashMessage')
        @yield('content')
    </body>

    @include('includes.footer')

    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.1/baguetteBox.min.js"></script>
    <script src="{{asset('js/vanilla-zoom.js')}}"></script>
    <script src="{{asset('js/theme.js')}}"></script>
    <script src="{{asset('js/all.min.js')}}"></script>
</html>
