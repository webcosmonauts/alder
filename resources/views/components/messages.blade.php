@if(session()->has('message'))
    <div class="card mb-4 border-left-{{ session()->get('alert-type', 'primary') }}">
        <div class="card-body">
            {{ session()->get('message') }}
            @if(session()->has('exception') && !empty(session()->get('exception')) && config('alder.show_additional_message_info'))
                <hr/>
                {{ session()->get('exception') }}
            @endif
        </div>
    </div>
@endif
