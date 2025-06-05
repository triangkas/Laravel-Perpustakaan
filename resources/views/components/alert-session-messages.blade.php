@props(['messages'])
@props(['type'])

@if($type == 'success')
    @php($attributes['class'] = 'alert-success')
@elseif($type == 'info')
    @php($attributes['class'] = 'alert-info')
@elseif($type == 'warning')
    @php($attributes['class'] = 'alert-warning')
@elseif($type == 'error' || $type == 'danger')
    @php($attributes['class'] = 'alert-danger')
@endif

@if ($messages)
    <div {{ $attributes->merge(['class' => 'mb-4 mt-4 font-medium text-sm alert-icon-area alert alert-dismissible fade show']) }} role="alert">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
        <div class="alert-content">
            @foreach ((array) $messages as $message)
                {{ $message }} <br />
            @endforeach
        </div>
    </div>
@endif
