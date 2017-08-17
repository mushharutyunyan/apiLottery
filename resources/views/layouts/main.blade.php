<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Droid+Serif:400,400italic|Open+Sans:400,300,600,700" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/assets/css/style.css" type="text/css" />
    <link rel="stylesheet" href="/assets/css/responsive.css" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', '') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right" style="font-size: 14px;">
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                    @if(Auth::user()['role'] == \App\Models\User::ADMIN)
                    <li><a href="{{ url('/user') }}">Users</a></li>
                    <li><a href="{{ url('/plan') }}">Change Plan</a></li>
                    @endif
                    <li><a href="{{ url('/payments') }}">Payments</a></li>
                    <li><a href="{{ url('/plans') }}">Plans</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                     @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')
</div>
    {{--<div id="copyrights" class="copyrights-dark">--}}

        {{--<div class="container clearfix">--}}


            {{--<div class="col_half">--}}

                {{--Copyrights &copy; 2017 &amp; All Rights Reserved.--}}

            {{--</div>--}}

        {{--</div>--}}

    {{--</div>--}}

    {{--<div id="gotoTop" class="fa fa-angle-up"></div>--}}
<!-- Scripts -->
<script src="/assets/js/jquery.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script>
    $('.watch-user').on("click",function(){
        $.ajax({
            url: '/user/history',
            type: 'POST',
            data: {id:$(this).attr('data-id'),_token:$(this).parent().children('input[name="_token"]').val()},
            dataType: 'json',
            success: function(data){
                $('.payment-history-table tbody').html('');
                $('.history-list').html('');
                $('.history-list').append('<li class="list-group-item">Calls left: '+data.count_requests+'</li>');
                var calls = 0;
                if(data.history.length){
                    calls = data.history.calls;
                }
                $('.history-list').append('<li class="list-group-item">Used Calls: '+calls+'</li>');
                if(data.payments.length){
                    $.each(data.payments,function(key,value){
                        var cart = '';
                        if(value.cart){
                            cart = value.cart;
                        }
                        $('.payment-history-table tbody').append(
                            '<tr>' +
                                '<td>'+value.plan+'</td>' +
                                '<td>'+value.calls+'</td>' +
                                '<td>'+value.paymentId+'</td>' +
                                '<td>'+cart+'</td>' +
                                '<td>'+value.status+'</td>' +
                            '</tr>'
                        )
                    });
                }
                $("#userHistoryModal").modal('show');
            }
        })
    })
</script>
</body>
</html>
