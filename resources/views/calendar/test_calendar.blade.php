@extends('default')

@section('title')
Login As
@stop

@section('styles')  
<style>

</style>
<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
<link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
@stop

@section('content')

<div class="calendar" id="calendar"></div>
@stop


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script> 


    $( document ).ready(function() {
      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'timeGridWeek',
          themeSystem: 'bootstrap5',
          events: [
          {
            "title": "Test 1",
            "start": "2023-10-10T15:00",
            "end": "2023-10-10T15:30"
        },
        {
            "title": "Test 2",
            "start": "2023-10-23T13:00",
            "end": "2023-10-23T14:00"
        },
        {
            "title": "Test 3",
            "start": "2023-10-31T16:00",
            "end": "2023-10-31T20:00"
        }
        ],
      });
      calendar.render();
  });


</script>
@stop