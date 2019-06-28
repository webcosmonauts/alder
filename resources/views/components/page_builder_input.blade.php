<div class="mb-2">
    <label>{{$label}}</label>
    @switch($type)

        @case("color")
        @case("number")
        @case("text")
        <input type="{{$type}}" class="form-control" name="{{$name}}" value="{{$value}}">
        @break

        @case("textarea")
        <textarea class="form-control" name="{{$name}}">{!! $value !!}</textarea>
        @break

        @case("file")
        <div class="input-group">
            <input type="text" class="image_label form-control" name="{{$name}}"
                   aria-label="Image"
                   aria-describedby="button-image" value="{{$value}}">
            <div class="input-group-append">
                <button class="btn btn-sm mb-0 mt-0 btn-outline-secondary button-image" style="height: 35px;" type="button">{{ __('alder::generic.choose') }}
                </button>
            </div>
        </div>
        @break

    @endswitch
</div>