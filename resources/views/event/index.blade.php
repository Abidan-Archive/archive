Events Index
<br />
@foreach($events as $event)
    <p>{{ $event->name }}</p>
    <p>{{ $event->date }}</p>
    <p>{{ $event->location }}</p>
@endforeach
