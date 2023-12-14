<x-mail::message>
# Raport: {{$date}}

Cześć,<br> przesyłam codzienny raport:

## Dzisiaj
<x-mail::table>
    |Lp| Task | Issue | Status |
    |:---|:------------- |:-------------| --------:|
    @foreach($dailyTasks as $task)
        | {{ $loop->iteration }} | {{$task['name']}}     | {{ $task['issue'] }} | <x-mail::chip :color="$task['status']['color']">{{$task['status']['name']}}</x-mail::chip>|
    @endforeach
</x-mail::table>

## Następne
<x-mail::table>
    |Lp| Task | Issue | Priority |
    |:---|:------------- |:-------------| --------:|
    @foreach($nextTasks as $task)
        | {{ $loop->iteration }} | {{$task['name']}}     | {{ $task['issue'] }} | <x-mail::chip :color="$task['priority']['color']">{{$task['priority']['name']}}</x-mail::chip>|
    @endforeach
</x-mail::table>

Pozdrawiam,<br>
<div style="opacity: 0.6; padding-bottom: 20px;">
    Sebastian Kostecki - PanelAlpha Back-end Developer<br/>
    Next level WordPress automation for web hosting with <a href="https://www.panelalpha.com"
                                                            title="Explore PanelAlpha!"><strong style="color:#000;">Panel</strong><strong
            style="color:#4bad47;">Alpha</strong></a>
</div>
</x-mail::message>
