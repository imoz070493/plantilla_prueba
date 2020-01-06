<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset=utf-8>
  <meta http-equiv=X-UA-Compatible content="IE=edge,chrome=1">
  <meta name=renderer content=webkit>
  <meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
  <link rel=icon href=/favicon.ico> <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{env('APP_NAME')}}</title>
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>

<body>
  <div id="app">
    <app></app>
  </div>
  <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>