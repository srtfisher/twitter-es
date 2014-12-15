
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Twitter for NJIT</title>

  <!-- Bootstrap core CSS -->
  <link href="/vendor/bootstrap/dist/css/bootstrap.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body>
  <div class="container">
    <h1 class="page-title">Twitter Search for <a href="http://twitter.com/njit">NJIT</a></h1>

    <form action="/" method="get">
      <input type="text" name="q" value="{{Input::get('q', '')}}" class="form-control input-lg" placeholder="Search for...">
      <br>
      <input type="text" name="user" value="{{Input::get('user', '')}}" class="form-control" placeholder="User">
      <br>
      <button type="submit" class="btn btn-primary">
        Search
      </button>
      <br>
      <br>
    </form>

    <table class="table table-striped">
      <tbody>
        @if ($results->count() < 1)
        <tr>
          <td>No results found.</td>
        </tr>
        @endif

        @foreach($results as $result)
        <tr>
          <td>{{Twitter::linkify($result->message)}}</td>

          <td>
            <a href="http://twitter.com/{{$result->user}}">{{'@'.$result->user}}</a>
          </td>
          <td>
            <a href="http://twitter.com/{{$result->user}}/status/{{$result->id}}">
              {{Carbon\Carbon::parse($result->timestamp)->diffForHumans()}}
            </a>
          </td>

        </tr>

        @endforeach
    </table>
  </div>

  <script type="text/javascript" src="vendor/jquery/dist/jquery.min.js"></script>
  <script>
  $(function() {

  });
  </script>
</body>
</html>
