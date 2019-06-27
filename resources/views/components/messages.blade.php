@if(session()->has('message'))
    <div class="alert alert-{{ session()->get('alert-type', 'primary') }} alert-dismissible fade show" role="alert">
           {{ session()->get('message') }}
            @if(session()->has('exception') && !empty(session()->get('exception')) && config('alder.show_additional_message_info'))
                <hr/>
                {{ session()->get('exception') }}
            @endif
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
