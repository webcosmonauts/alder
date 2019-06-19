@switch($root->input_type)
    @case('text')
    <label for="{{ $root->slug }}">{{ $root->title }}</label>
    <div class="input-group mb-4">
        <input type="text" name="{{ $root->slug }}" id="{{ $root->slug }}" class="form-control"
               placeholder="{{ $root->title }}" aria-label="{{ $root->title }}" aria-describedby="{{ $root->title }}"
               value="{{ is_array($root->value) ? json_encode($root->value) : $root->value }}">
    </div>
    @break
    @case('number')
    <label for="{{ $root->slug }}">{{ $root->title }}</label>
    <div class="input-group mb-4">
        <input type="number" name="{{ $root->slug }}" id="{{ $root->slug }}" class="form-control"
               placeholder="{{ $root->title }}" aria-label="{{ $root->title }}" aria-describedby="{{ $root->title }}"
               value="{{ $root->value }}">
    </div>
    @break
    @case('select')
    <label for="{{ $root->slug }}">{{ $root->title }}</label>
    <div class="input-group mb-4">
        <select class="custom-select" name="{{ $root->slug }}" id="{{ $root->slug }}"
                placeholder="{{ $root->title }}" aria-label="{{ $root->title }}" aria-describedby="{{ $root->title }}">
            @foreach($root->options as $value)
                <option value="{{ $value }}" {{ $root->value == $value ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
    </div>
    @break
    @case('select-multiple')
    <label for="{{ $root->slug }}">{{ $root->title }}</label>
    <div class="input-group mb-4">
        <select multiple class="custom-select" name="{{ $root->slug }}" id="{{ $root->slug }}"
                placeholder="{{ $root->title }}" aria-label="{{ $root->title }}" aria-describedby="{{ $root->title }}">
            @foreach($root->options as $value)
                <option value="{{ $value }}" {{ in_array($value, $root->value) ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
    </div>
    @break
    @case('checkbox')
    <div class="input-group mb-4">
        <div class="input-group-prepend">
            <div class="input-group-text">
                <input type="checkbox" name="{{ $root->slug }}" id="{{ $root->slug }}"
                       placeholder="{{ $root->title }}" aria-label="{{ $root->title }}" aria-describedby="{{ $root->title }}"
                        {{ $root->value === true ? 'checked' : '' }}>
            </div>
        </div>
        <input type="text" class="form-control" readonly
               placeholder="{{ $root->title }}" aria-label="{{ $root->title }}" aria-describedby="{{ $root->title }}"
               value="{{ $root->title }}">
    </div>
    @break
    @case('radio')
    <label for="{{ $root->slug }}">{{ $root->title }}</label>
    @for($i = 0; $i < count($root->options); $i++)
        <div class="input-group mb-{{ $i == count($root->options)-1 ? '4' : '2' }}">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input type="radio" name="{{ $root->slug }}"
                           placeholder="{{ $root->title }}" aria-label="{{ $root->title }}" aria-describedby="{{ $root->title }}"
                           value="{{ $root->options[$i] }}" {{ $root->value === $root->options[$i] ? 'checked' : '' }}>
                </div>
            </div>
            <input type="text" class="form-control" readonly
                   placeholder="{{ $root->title }}" aria-label="{{ $root->title }}" aria-describedby="{{ $root->title }}"
                   value="{{ $root->options[$i] }}">
        </div>
    @endfor
    @break
    @case('password')
    <label for="{{ $root->slug }}">{{ $root->title }}</label>
    <div class="input-group mb-4">
        <input type="password" name="{{ $root->slug }}" id="{{ $root->slug }}" class="form-control"
               placeholder="{{ $root->title }}" aria-label="{{ $root->title }}" aria-describedby="{{ $root->title }}"
               value="{{ $root->value }}">
    </div>
    @break
    @case('file')
    @php
        $extensions = '';
        if (isset($root->options)) {
            foreach ($root->options as $option)
                $extensions .= Str::start($option, '.') . ',';
            $extensions = rtrim($extensions, ',');
        }
    @endphp
    <label for="{{ $root->slug }}">{{ $root->title }}</label>
    <div class="input-group mb-4">
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="{{ $root->slug }}" aria-describedby="{{ $root->title }}"
                    {{ !empty($extensions) ? 'accept='.$extensions : '' }}>
            <label class="custom-file-label" for="{{ $root->slug }}"></label>
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
    <label for="{{ $root->slug }}">{{ $root->title }}</label>
    <div class="input-group mb-4">
        <div class="custom-file">
            <input multiple type="file" class="custom-file-input" id="{{ $root->slug }}" aria-describedby="{{ $root->title }}"
                    {{ !empty($extensions) ? 'accept='.$extensions : '' }}>
            <label class="custom-file-label" for="{{ $root->slug }}"></label>
        </div>
    </div>
    @break
    @case('date')
    <label for="{{ $root->slug }}">{{ $root->title }}</label>
    <div class="input-group mb-4">
        <input type="text" name="{{ $root->slug }}" id="{{ $root->slug }}" class="form-control datepicker"
               placeholder="{{ $root->title }}" aria-label="{{ $root->title }}" aria-describedby="{{ $root->title }}"
               value="{{ $root->value }}">
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