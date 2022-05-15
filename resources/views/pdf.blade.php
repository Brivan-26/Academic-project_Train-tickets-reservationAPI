<!-- pdf.blade.php -->

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/app.css">
  </head>
  <body>
      @foreach($tickets as $ticket)
        <div>
            {{$ticket->passenger_name}}
        </div>
        <div>
            {{$ticket->travel_class}}
        </div>
        <div>
            {{$ticket->price}}
        </div>
        <div>
            {{$ticket->id}}
        </div>
        <hr>
      @endforeach
  </body>
</html>
