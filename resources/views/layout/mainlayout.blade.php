<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>马来西亚院校数据库</title>
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.0.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/front.css')}}" rel="stylesheet">
    @yield('head_ext')
</head>

<body>
    @include('layout.partials.header')
    @include('layout.partials.nav')
    @yield('content')
    @include('layout.partials.footer')
    @yield('script')
</body>

</html>
