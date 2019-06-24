<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Studienleistungen</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}" />
</head>
<body>
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">Studienleistung</a>
        </div>
        <ul class="nav navbar-nav">
          <li><a href="{{ action('GradingController@index') }}">Studenten</a></li>
          <li><a href="{{ action('CourseController@index') }}">Lehrer</a></li>
        </ul>
      </div>
    </nav>
    <div id="tucal-content">
      <div class="container">
        <div class="row"></div>
        <div id="tucal-edge"></div>
        <div id="top" class="col-sm-9 col-xs-12 tucal-canvas">
          <div class="row">
            <main class="col-xs-12 page-content">
              <div id="content" class="container">
                @yield('content')
                @yield('pagination')
              </div>
            </main>
          </div>
        </div>
      </div>
    </div>

    <footer></footer>
  {{-- JQuery, BootstrapJS-Plugins --}}
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="{{ URL::asset('js/ajax.js') }}"></script>


</body>
</html>
