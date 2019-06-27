@switch($root->input_type)
    @case('text')
    <div class="mb-2 w-100">
        <label for="{{ $root->slug }}">{{ $root->title }}</label>
        <div class="input-group mb-4">
            <input type="text" name="{{ $root->slug }}" id="{{ $root->slug }}" class="form-control"
                   placeholder="{{ $root->title }}" aria-label="{{ $root->title }}"
                   aria-describedby="{{ $root->title }}"
                   value="{{ is_array($root->value) ? json_encode($root->value) : $root->value }}">
        </div>
    </div>
    @break
    @case('number')
    <div class="mb-2 w-100">
        <label for="{{ $root->slug }}">{{ $root->title }}</label>
        <div class="input-group mb-4">
            <input type="number" name="{{ $root->slug }}" id="{{ $root->slug }}" class="form-control"
                   placeholder="{{ $root->title }}" aria-label="{{ $root->title }}"
                   aria-describedby="{{ $root->title }}"
                   value="{{ $root->value }}">
        </div>
    </div>
    @break
    @case('select')
    <div class="mb-2 w-100">
        <label for="{{ $root->slug }}">{{ $root->title }}</label>
        <div class="input-group mb-4">
            <select class="custom-select" name="{{ $root->slug }}" id="{{ $root->slug }}"
                    placeholder="{{ $root->title }}" aria-label="{{ $root->title }}"
                    aria-describedby="{{ $root->title }}">
                @foreach($root->options as $value)
                    <option value="{{ $value }}" {{ $root->value == $value ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @break

    @case('select-multiple')
    <div class="mb-2 w-100">
        <label for="{{ $root->slug }}">{{ $root->title }}</label>
        <div class="input-group mb-4">
            <select multiple class="custom-select" name="{{ $root->slug }}" id="{{ $root->slug }}"
                    placeholder="{{ $root->title }}" aria-label="{{ $root->title }}"
                    aria-describedby="{{ $root->title }}">
                @foreach($root->options as $value)
                    <option value="{{ $value }}" {{ in_array($value, $root->value) ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @break

    @case('checkbox')
    <div class="mb-2">
        <div class="custom-control custom-checkbox pl-0">
            <input type="checkbox" class="custom-control-input" name="{{ $root->slug }}" id="{{ $root->slug }}"
                   placeholder="{{ $root->title }}"
                   aria-label="{{ $root->title }}" aria-describedby="{{ $root->title }}"
                    {{ $root->value === "true" ? 'checked' : '' }}>
            <label class="custom-control-label pl-4"
                   for="{{ $root->slug }}"> {{ $root->title }}</label>
        </div>
    </div>
    @break

    @case('radio')
    <div class="mb-2 w-100">
        <label for="{{ $root->slug }}">{{ $root->title }}</label>
        @for($i = 0; $i < count($root->options); $i++)
            <div class="input-group mb-{{ $i == count($root->options)-1 ? '4' : '2' }}">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="radio" name="{{ $root->slug }}"
                               placeholder="{{ $root->title }}" aria-label="{{ $root->title }}"
                               aria-describedby="{{ $root->title }}"
                               value="{{ $root->options[$i] }}" {{ $root->value === $root->options[$i] ? 'checked' : '' }}>
                    </div>
                </div>
                <input type="text" class="form-control" readonly
                       placeholder="{{ $root->title }}" aria-label="{{ $root->title }}"
                       aria-describedby="{{ $root->title }}"
                       value="{{ $root->options[$i] }}">
            </div>
    </div>
    @endfor
    @break

    @case('password')
    <div class="mb-2 w-100">
        <label for="{{ $root->slug }}">{{ $root->title }}</label>
        <div class="input-group mb-4">
            <input type="password" name="{{ $root->slug }}" id="{{ $root->slug }}" class="form-control"
                   placeholder="{{ $root->title }}" aria-label="{{ $root->title }}"
                   aria-describedby="{{ $root->title }}"
                   value="{{ $root->value }}">
        </div>
    </div>
    @break

    @case('file')
    <div class="mb-2 w-100">
        <div>{{$root->title}}</div>
        <div class="input-group mb-4">
            <input type="text" class="image_label form-control" name="{{$root->slug}}"
                   aria-describedby="button-image" value="{{$root->value}}">
            <div class="input-group-append">
                <button class="btn btn-sm btn-outline-secondary button-image"
                        type="button">{{ __('alder::generic.choose') }}</button>
            </div>
        </div>
    </div>
    @break

    @case('file-multiple')
    @php
        $extensions = '';
        if (isset($root->options)) {
            foreach ($root->options as $option)
                $extensions .= Str::start($option, '.') . ',';
            $extensions = rtrim($extensions, ',');
        }
    @endphp
    <div class="mb-2 w-100">
        <label for="{{ $root->slug }}">{{ $root->title }}</label>
        <div class="input-group mb-4">
            <div class="custom-file">
                <input multiple type="file" class="custom-file-input" id="{{ $root->slug }}"
                       aria-describedby="{{ $root->title }}"
                        {{ !empty($extensions) ? 'accept='.$extensions : '' }}>
                <label class="custom-file-label" for="{{ $root->slug }}"></label>
            </div>
        </div>
    </div>
    @break

    @case('date')
    <div class="mb-2 w-100">
        <label for="{{ $root->slug }}">{{ $root->title }}</label>
        <div class="input-group mb-4">
            <input type="text" name="{{ $root->slug }}" id="{{ $root->slug }}" class="form-control datepicker"
                   placeholder="{{ $root->title }}" aria-label="{{ $root->title }}"
                   aria-describedby="{{ $root->title }}"
                   value="{{ $root->value }}">
        </div>
    </div>
    @break
    @case('datetime-local')
    @break
    @case('time')
    @break
    @case('month')
    @break
    @case('color')
    @break
    @default
@endswitch