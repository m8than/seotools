@php ($current_user = \Facades\App\Helpers\Authentication::user())
<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SEOTools by Nathan- @yield('title')</title>

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
        <script
            src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
            crossorigin="anonymous"></script>

        <link href="{{ asset('css/app.css?v=4453') }}" rel="stylesheet" />
    </head>
    <body>
        <div class="page-container @yield('page-modifiers')">
            <div class="header">
                <h1 class="header__logo"><a href="/dashboard">SEOTools</a></h1>
                <div class="header-buttons">
                    @if($current_user !== null)
                        <div class="header-buttons__button">
                            <a href="/logout"><i class="fas fa-sign-out-alt"></i></a>
                        </div>
                    @endif
                </div>
            </div>
            
            @include('layouts.flash-messages')
            @if($current_user !== null)
                @include('layouts.nav')
            @endif
            <div class="content">
                @yield('content')
            </div>
            <div class="footer">
                SEOTools by Nathan <i class="fas fa-smile"></i>
            </div>
        </div>
    </body>
</html>
