<!-- pdf.blade.php -->
@php
    use App\Models\Station;
@endphp
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/app.css">
  </head>
  <body>
      @php
          $i=0;
      @endphp
      @foreach($tickets as $ticket)
        <div>
            Passenger name : {{$ticket->passenger_name}}
        </div>
        <div>
            Boarding Station :
            @php
                echo Station::find($ticket->boarding_station)->name." - ".Station::find($ticket->boarding_station)->wilaya;
            @endphp
        </div>
        <div>
            Landing Station :
            @php
                echo Station::find($ticket->landing_station)->name." - ".Station::find($ticket->landing_station)->wilaya;
            @endphp
        </div>
        <div>
            Traveling Class :
            @if($ticket->travel_class=='F')
                First Class
            @else
                Second Class
            @endif
        </div>
        <div>
            Payed Price : {{$ticket->price}}
        </div>
        <br>
        <br>
        <div class="mb-3">
            {!! DNS2D::getBarcodeHTML($ticket->qrcode_token, 'QRCODE', 3, 3) !!}
        </div>

        @php
            $i++;
        @endphp
        @if($i != count($tickets))
            <div style="page-break-before: always;"></div>
        @endif
        @endforeach

  </body>
</html>
