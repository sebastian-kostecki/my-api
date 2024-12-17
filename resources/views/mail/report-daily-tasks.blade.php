<x-mail::message>
<div style="background-color: #f2f2f2; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
    <h2 style="margin: 0; font-size: 20px; color: #333; background-color: transparent">Raport: {{$date}}</h2>
</div>
<div style="background-color: #f2f2f2; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
    <h3 style="margin: 0; font-size: 16px; color: #333; background-color: transparent">Today</h3>
</div>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px;">
    <tr>
        <th style="background-color: #f2f2f2; padding: 10px; text-align: left; font-weight: bold;">No</th>
        <th style="background-color: #f2f2f2; padding: 10px; text-align: left; font-weight: bold;">Task</th>
        <th style="background-color: #f2f2f2; padding: 10px; text-align: center; font-weight: bold;">Milestone</th>
        <th style="background-color: #f2f2f2; padding: 10px; text-align: right; font-weight: bold;">Status</th>
    </tr>
    @foreach($dailyTasks as $task)
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $loop->iteration }}</td>
            <td style="padding: 10px; border: 1px solid #ddd; word-wrap: break-word; max-width: 200px;">
                <a href="{{$task['url']}}" target="_blank" style="color: #007bff; text-decoration: none;">{{$task['name']}}</a>
            </td>
            <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">{{ $task['milestone'] }}</td>
            <td style="padding: 10px; border: 1px solid #ddd; text-align: right;">
  <span style="background-color: {{$task['status']['color']}}; padding: 2px 5px; border-radius: 5px; color: white; box-shadow: 1px 1px 3px rgba(0,0,0,0.1);">
    {{$task['status']['name']}}
  </span>
            </td>
        </tr>
    @endforeach
</table>
<div style="background-color: #f2f2f2; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
    <h3 style="margin: 0; font-size: 16px; color: #333; background-color: transparent">Next</h3>
</div>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px;">
    <tr>
        <th style="background-color: #f2f2f2; padding: 10px; text-align: left; font-weight: bold;">No</th>
        <th style="background-color: #f2f2f2; padding: 10px; text-align: left; font-weight: bold;">Task</th>
        <th style="background-color: #f2f2f2; padding: 10px; text-align: center; font-weight: bold;">Milestone</th>
        <th style="background-color: #f2f2f2; padding: 10px; text-align: center; font-weight: bold;">Priority</th>
        <th style="background-color: #f2f2f2; padding: 10px; text-align: right; font-weight: bold;">Status</th>
    </tr>
    @foreach($nextTasks as $task)
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $loop->iteration }}</td>
            <td style="padding: 10px; border: 1px solid #ddd; word-wrap: break-word; max-width: 200px;">
                <a href="{{$task['url']}}" target="_blank" style="color: #007bff; text-decoration: none;">{{$task['name']}}</a>
            </td>
            <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">{{ $task['milestone'] }}</td>
            <td style="padding: 10px; border: 1px solid #ddd; text-align: center;">
  <span style="background-color: {{$task['priority']['color']}}; padding: 2px 5px; border-radius: 5px; color: white; box-shadow: 1px 1px 3px rgba(0,0,0,0.1);">
    {{$task['priority']['name']}}
  </span>
            </td>
            <td style="padding: 10px; border: 1px solid #ddd; text-align: right;">
  <span style="background-color: {{$task['status']['color']}}; padding: 2px 5px; border-radius: 5px; color: white; box-shadow: 1px 1px 3px rgba(0,0,0,0.1);">
    {{$task['status']['name']}}
  </span>
            </td>
        </tr>
    @endforeach
</table>

<div style="opacity: 0.6; padding-bottom: 20px;">
    Sebastian Kostecki - PanelAlpha Back-end Developer<br/>
    Next level WordPress automation for web hosting with <a href="https://www.panelalpha.com"
                                                            title="Explore PanelAlpha!"><strong style="color:#000;">Panel</strong><strong
            style="color:#4bad47;">Alpha</strong></a>
</div>
</x-mail::message>
