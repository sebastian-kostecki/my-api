<x-mail::message>
# Raport: {{$date}}

Cześć,<br> przesyłam codzienny raport:

## Dzisiaj
<ul>
@foreach($dailyTasks as $task)
    <li>{!! $task !!}</li>
@endforeach
</ul>

## Następne
<ul>
@foreach($nextTasks as $task)
    <li>{!! $task !!}</li>
@endforeach
</ul>

Pozdrawiam,<br>
<div style="opacity: 0.6; padding-bottom: 20px;">
    Sebastian Kostecki - PanelAlpha Back-end Developer<br/>
    Next level WordPress automation for web hosting with <a href="https://www.panelalpha.com" title="Explore PanelAlpha!"><strong style="color:#000;">Panel</strong><strong style="color:#4bad47;">Alpha</strong></a>
</div>
</x-mail::message>
